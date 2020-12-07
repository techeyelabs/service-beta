<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\ApiErrorCodes;
use App\Models\ProductPrice;
use App\Models\ProductImage;
use App\Models\AffiliatorPoint;
use App\Models\PaidService;
use App\Models\Multiples;
use App\Models\ContactUs;
use App\Mail;
use App\Models\AffiliatorApplication;
use DB;

class UploadController extends Controller
{
    public function randomPrefix($length)
    {
        $random= "";
        $rand = rand(0,9999);
        $data = $rand."ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";

        for($i = 0; $i < $length; $i++)
        {
            $random .= substr($data, (rand()%(strlen($data))), 1);
        }

        return $random;
    }

     /**************************
        @Name: store uploaded product
        @URL: upload-product
        @Method: POST
    ***************************/
    public function store(Request $request)
    {
        // return response()->json($self_affiliate);   
        // Data allocation start
        $title = isset($request->title)? $request->title: '';
        $renewal_type = ($request->autoPayment == "true")? 2: 1;
        $seller_id = isset($request->seller_id)? $request->seller_id: '';
        $short_desc  = isset($request->short_desc)? $request->short_desc: '';
        $long_desc = isset($request->long_desc)? $request->long_desc: '';
        $pattern = "/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i";
        preg_replace('#[-a-zA-Z0-9@:%_\+.~\#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~\#?&//=]*)?#si', "(Link not found!)" , $long_desc);
        $long_desc = strip_tags($long_desc);
        $sub_category_id = isset($request->sub_category_id)? $request->sub_category_id: '';
        $category_id = isset($request->category_id)? $request->category_id: '';
        $price_int = isset($request->price_int)? $request->price_int: '';
        $commission_in_ex_vat = ($request->commission_in_ex_vat=='true')? 'INCLUDE_VAT': 'EXCLUDE_VAT';
        $affiliator_commission = isset($request->affiliator_commission)? $request->affiliator_commission: 0;
        $delivery_format  = isset($request->delivery_format)? $request->delivery_format: '';
        $expected_delivery_days = isset($request->expected_delivery_days)? $request->expected_delivery_days: '';
        $no_of_order_at_atime = isset($request->no_of_order_at_atime)? $request->no_of_order_at_atime: '';
        $is_estimate_or_customizable  = isset($request->is_estimate_or_customizable)? $request->is_estimate_or_customizable: 'NO';
        $self_affiliate = ($request->selfaff == 'true')? 'YES': 'NO';
        $is_draft = ($request->is_draft==0)? 'DRAFT': 'PUBLISHED';
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        // File allocation
        $file =  isset($_FILES['file']['name'])?$_FILES['file']['name']: null;
        $file_str = $request->file_string;
        // Allocation complete

        //processing cover
        $main = isset($_FILES['single_picture']['name'])?$_FILES['single_picture']['name']: null;
        $main_string = isset($request->single_picture_string)?$request->single_picture_string: null;
        //Cover proccessing ends
        
        // Validation start
        if($_FILES==null && $file_str==''){
            // print_r($file);
            $e = ApiErrorCodes::where('error_code',10048)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message,
                'error' => $_POST
            ];
        } else if($title==null || $title==''){
            $e = ApiErrorCodes::where('error_code',10031)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else if($seller_id==null || $seller_id=='') {
            $e = ApiErrorCodes::where('error_code',10032)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else if($short_desc==null || $short_desc=='') {
            $e = ApiErrorCodes::where('error_code',10033)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else if($long_desc==null || $long_desc=='') {
            $e = ApiErrorCodes::where('error_code',10034)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message,
                'response' => $request->all()
            ];
        } else if($sub_category_id==null || $sub_category_id=='') {
            $e = ApiErrorCodes::where('error_code',10036)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else if($category_id==null || $category_id=='') {
            $e = ApiErrorCodes::where('error_code',10035)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else if($price_int==null || $price_int=='') {
            $e = ApiErrorCodes::where('error_code',10037)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else if($delivery_format==null || $delivery_format=='') {
            $e = ApiErrorCodes::where('error_code',10039)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else if($expected_delivery_days==null || $expected_delivery_days=='') {
            $e = ApiErrorCodes::where('error_code',10040)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            // Validation complete
           
            
            // Calculation and price allocation starts
            $vat = $price_int * (20/100);
            if($affiliator_commission>0){
                $aff_percentage_all = AffiliatorPoint::where('id', $affiliator_commission)->first();
                $aff_percentage = $aff_percentage_all->point_value;
            } else {
                $aff_percentage = 0;
            }
            $aff_amount = ($price_int - $vat) * ($aff_percentage/100);
            if($commission_in_ex_vat=='INCLUDE_VAT'){              
                $price_int = $price_int;
            } else {
                $price_int = $price_int+$vat;
            }

            $is_exist = ProductPrice::where('price', $price_int)->first();
            if($is_exist){
                $priceid = $is_exist->id;
            } else {
                $pr = new ProductPrice();
                $pr->price = $price_int;
                $pr->save();
                $priceid =  $pr->id;
            }
            // Calculation and price allocation ends


            // Get file name
            if(!empty($file_str) && $file_str!='undefined'){
                $filename = $file_str;
            } else {
                $filename = '';
                $filename = time().$file;
            }
            // dd($filename);
            $title = $request->title;
            $category_id = $request->category_id;

            $id = isset($request->id)?$request->id:0;
            $ip = \Config::get('app.api_base_url');
            // Upload data
            if( $_FILES!=null || $file_str!=''){
                // if(empty($file_str) || $file_str=='undefined'){
                //     move_uploaded_file($_FILES['file']['tmp_name'],"assets/images/products/".$filename);
                // }
                if($id==0){
                    $u = new Product();
                    $u->service_id = $this->randomPrefix(6);
                    $u->title = $title;
                    $u->renewal_type = $renewal_type;
                    $u->seller_id = $request->seller_id;
                    $u->short_desc = $request->short_desc;
                    $u->long_desc = $request->long_desc;
                    $u->sub_category_id = $request->sub_category_id;
                    $u->category_id = $request->category_id;
                    $u->price_id = $priceid;
                    $u->commission_in_ex_vat = $commission_in_ex_vat;
                    $u->commision_include_vat_amount = $vat;
                    $u->affiliator_commission = $request->affiliator_commission;
                    $u->affiliator_commission_amount = $aff_amount;
                    $u->delivery_format = $request->delivery_format;
                    $u->expected_delivery_days = $request->expected_delivery_days;
                    $u->no_of_order_at_atime = $request->no_of_order_at_atime;
                    $u->is_estimate_or_customizable = $is_estimate_or_customizable;
                    $u->is_draft = $is_draft;
                    $u->self_affiliate = $self_affiliate;
                    $u->created_at = $created_at;
                    $u->updated_at = $updated_at;
                    $u->save();

                    $last_inserted_id = $u->id;
                    $what = 'add';

                    //Add main image
                    move_uploaded_file($_FILES['single_picture']['tmp_name'],"assets/images/products/".$_FILES['single_picture']['name']); 
                    Product::where('id', $last_inserted_id)
                    ->update(
                        [
                            'image_name' => $main
                        ]
                    );
                    // Add multiple images
                    for($a=0; $a<count($_FILES['file']['name']); $a++){
                        if(isset($_FILES['file']['name'][$a])){
                                $m = new Multiples();
                                $m->product_id = $last_inserted_id;
                                $m->image = $_FILES['file']['name'][$a];
                                $m->save();                          
                            move_uploaded_file($_FILES['file']['tmp_name'][$a],"assets/images/products/".$_FILES['file']['name'][$a]);     
                        }
                    } 
                } else {
                    Product::where('id', $id)
                    ->update(
                        [
                            'title' => $title,
                            'renewal_type' => $renewal_type,
                            'seller_id' => $request->seller_id,
                            'short_desc' => $request->short_desc,
                            'long_desc' => $request->long_desc,
                            'sub_category_id' => $request->sub_category_id,
                            'category_id' => $request->category_id,
                            'price_id' => $priceid,
                            'commission_in_ex_vat' => $commission_in_ex_vat,
                            'commision_include_vat_amount' => $vat,
                            'affiliator_commission' => $request->affiliator_commission,
                            'affiliator_commission_amount' => $aff_amount,
                            'delivery_format' => $request->delivery_format,
                            'expected_delivery_days' => $request->expected_delivery_days,
                            'is_draft' => $is_draft,
                            'no_of_order_at_atime' => $request->no_of_order_at_atime,
                            'is_estimate_or_customizable' => $is_estimate_or_customizable,
                            'self_affiliate' => $self_affiliate
                        ]
                    );
                    $what = 'edit';

                    //Add main image
                    if($main_string == null){
                        move_uploaded_file($_FILES['single_picture']['tmp_name'],"assets/images/products/".$_FILES['single_picture']['name']); 
                        Product::where('id', $id)
                        ->update(
                            [
                                'image_name' => $main
                            ]
                        );
                    }
                    

                    $flag = 0;
                    $res = Multiples::where('product_id', $id)->delete();
                    if(!empty($_FILES) && isset($_FILES['file']['name'])){
                        for($a=0; $a<count($_FILES['file']['name']); $a++){
                            // isset($_FILES['file']['name'])?$_FILES['file']['name']: null;
                            if(isset($_FILES['file']['name'][$a])){                               
                                    $m = new Multiples();
                                    $m->product_id = $id;
                                    $m->image = $_FILES['file']['name'][$a];
                                    $m->save();
                                move_uploaded_file($_FILES['file']['tmp_name'][$a],"assets/images/products/".$_FILES['file']['name'][$a]);     
                            }
                        }
                    } 
                    // DB::enableQueryLog();
                    // foreach($request->file_string as $f){
                    //     $mix = new Multiples();
                    //     $mix->product_id = $id;
                    //     $mix->image = $f;
                    //     $mix->save();
                       
                    //     dd(DB::getQueryLog());
                    // }
                    for($x=0; $x<count($request->file_string); $x++){
                        if($request->file_string[$x] == 'undefined')
                            continue;
                        $mix = new Multiples();
                        $mix->product_id = $id;
                        $mix->image = $request->file_string[$x];
                        $mix->save();
                    }
                }      
                
                // Data upload complete

                //Paid services upload start
                // foreach($service as $ser){
                //     if($ser->id==0){
                //         $n = new PaidService();
                //         $n->product_id = $last_inserted_id;
                //         $n->price = $ser->price;
                //         $n->service_name = $ser->serviceName;
                //         $n->save;
                //     } else {
                //         PaidService::where('id', $ser->id)
                //         ->update(
                //             [
                //                 'price' => $ser->price,
                //                 'service_name' => $ser->serviceName
                //             ]
                //         );
                //     }
                // }
                //Paid services upload ends

                //Multiple Image upload start
                
                //Multiple Image upload ends

                $data = [
                    'status'  => 200  ,
                    'message' => 'Product successfully uploaded',
                    // 'path'=> $ip."assets/images/products/".$filename,
                    'response' => $request->all(),
                    'what_happened' => $what
                ];
            }else{
                // Error response
                $e = ApiErrorCodes::where('error_code',10049)->first(); 
                $data = [
                    'status'  => $e->error_code  ,
                    'message' => $e->error_message
                ];
            }
            // Return to front
            return response()->json($data);   
        }
    }

    public function get_drop_downs_at_load()
    {
        $all_cats = []; $sub_cats = [];
        $categories = Category::select('id','cat_name','parent_id')->where('parent_id', null)->get();
        foreach($categories as $cats){
            $sub_cats = Category::select('id','cat_name','parent_id')->where('parent_id', $cats->id)->get();
            //DB::select('id,cat_name,parent_id')->table('category')->where('parent_id', $cats->id)->get();
            $all_cats[] = [
                'id' => $cats->id ,
                'category' => $cats->cat_name ,
                'sub_category' =>  ($sub_cats == null) ? "":$sub_cats
            ];
        }
        $aff_points = DB::table('affiliator_points')->get();
        $data = [
            'status'  => 200,
            'categories'  => $all_cats,
            'aff_points' => $aff_points
        ];
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function get_drop_downs_at_load_cat()
    {
        $categories = Category::select('id','cat_name','parent_id')->where('parent_id', null)->get();
        $aff_points = DB::table('affiliator_points')->get();
        $data = [
            'status'  => 200,
            'categories'  => $categories,
            'aff_points' => $aff_points
        ];
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function get_drop_down_onchange($id = null)
    {
        $category = $id;
        if($category!=null && $category!= ''){
            $category_sub = DB::table('category')->where('parent_id', $category)->get();
            if($category_sub!=null && $category_sub!=''){
                $data = [
                    'status'  => 200,
                    'category_sub' => $category_sub
                ];
            } else {
                $e = ApiErrorCodes::where('error_code',10030)->first(); 
                $data = [
                    'status'  => $e->error_code  ,
                    'message' => $e->error_message
                ];
            }
        } else {
            $e = ApiErrorCodes::where('error_code',10030)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        }

        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function get_for_edit($id=null)
    {
        $ip = \Config::get('app.api_base_url');
        $product = $id;
        if($product=='' || $product==null){
            $e = ApiErrorCodes::where('error_code',10030)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $pro = DB::table('products AS pro')
                        ->select('pro.*', 'cat.cat_name', 'pp.price AS price_inc',
                                DB::raw("(pp.price - pro.commision_include_vat_amount) AS price_ex"), 
                                DB::raw('CONCAT("'.$ip.'","assets/images/products/", pro.image_name) AS img'))
                        ->leftjoin('category AS cat', 'cat.id', '=', 'pro.sub_category_id')
                        ->leftjoin('product_prices AS pp', 'pp.id', '=', 'pro.price_id')
                        ->where('pro.id', $product)
                        ->first();
            $multiples = DB::table('multiple_image AS mi')
                        ->select('mi.id','mi.image', DB::raw('CONCAT("'.$ip.'","assets/images/products/", mi.image) AS img'))
                        ->where('mi.product_id', $product)
                        ->get();
            $data = [
                'status'  => 200,
                'payload' => $pro,
                'multiples' => $multiples
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function name_validation(Request $request)
    {
        $product_id = isset($request->product_id)?$request->product_id: 0;
        $name = $request->name;
        if($name=='' || $name==null){
            $e = ApiErrorCodes::where('error_code',10073)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            if($product_id == 0){
                $flag = Product::where('title', $name)->count();
                if($flag>0){
                    $data = [
                        'flag' => 1
                    ];
                } else {
                    $data = [
                        'flag' => 0
                    ];
                }
            } else {
                $flag = Product::where('title', $name)->where('id', $product_id)->count();
                if($flag>0){
                    $data = [
                        'flag' => 1
                    ];
                } else {
                    $data = [
                        'flag' => 0
                    ];
                }
            }
           
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function get_cat_name($id=null)
    {
        $cat = Category::where('id', $id)->first();
        $data = [
            'status'  => 200  ,
            'payload' => $cat
        ];
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function get_reward_amount($id=null)
    {
        $com = DB::table('affiliator_points')->select('point_value')->where('id', $id)->first();
        $data = [
            'status'  => 200  ,
            'payload' => $com
        ];
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function store_contact_us(Request $request)
    {
        $content = $request->content;
        $email = $request->email;
        $name = $request->name;
        $title = $request->title;

        $u = new ContactUs();
        $u->name = $request->name;
        $u->email = $request->email;
        $u->content = $request->content;
        $u->save();

        //Contact mail admin
        $data = [
            'link' => '',
            'name' => $request->name,
            'mail' => $email,
            'content' => $content,
            'title' => $title
        ];
        \Mail::send('contactadmin', $data, function($message) use ($request){
            $message->to('info@share-work.jp', 'test')->subject
               ('[share-work]　管理者用】新規問合せの通知');
            $message->from('noreply@share-work.jp','ShareWork');
         });


        //Contact mail user
        $data = [
            'link' => '',
            'name' => $request->name,
            'mail' => $email,
            'content' => $content,
            'title' => $title
        ];
        \Mail::send('contactuser', $data, function($message) use ($request){
            $message->to($request->email, 'test')->subject
               ('[share-work] お問い合わせ受付完了のお知らせ');
            $message->from('noreply@share-work.jp','ShareWork');
         });
        //Contact mail ends

        
        //Contact mail ends
        $data = [
            'status'  => 200  ,
            'payload' => $request
        ];
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function store_application(Request $request)
    {
        $content = $request->content;
        $email = $request->email;
        $name = $request->name;
        $title = $request->title;
        $new_app = new AffiliatorApplication();
        $new_app->name = $request->name;
        $new_app->email = $request->email;
        $new_app->app_body = $request->content;
        $new_app->save();
        $data = [
            'status'  => 200,
        ];
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

}

