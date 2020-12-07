@extends('admin.layouts.main')


@section('custom_css')
@endsection



@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    
                    <div class="header">
                        <h2>Service Details</h2>
                        <div class="header-dropdown m-r-0"></div>
                    </div>
                    
                    <div class="body table-responsive">


                       
                        <table class="table" id="data-table">
                            <thead>
                                <tr>
                                    <td style="width: 35%">Title:</td>
                                    <td style="width: 65%">{{$data->title}}</td>
                                </tr>

                                <tr>
                                    <td>Image:</td>
                                    <td>
                                        <img src="/assets/images/products/{{$data->image_name}}" alt="" width="280" height="auto">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Price:</td>
                                    <td>{{$data->price->price}}</td>
                                </tr>
                                <tr>
                                    <td>Category:</td>
                                    <td>{{$data->category->cat_name}}</td>
                                </tr>
                                <tr>
                                    <td>Sub Category:</td>
                                    <td>{{$data->subCategory->cat_name}}</td>
                                </tr>
                                <tr>
                                    <td>Seller:</td>
                                    <td>{{$data->seller->first_name.' '.$data->seller->last_name}}</td>
                                </tr>
                                <tr>
                                    <td>Short Desc:</td>
                                    <td>{{$data->short_desc}}</td>
                                </tr>
                                <tr>
                                    <td>Long Desc:</td>
                                    <td>{!! $data->long_desc !!}</td>
                                </tr>
                                <tr>
                                    <td>Transaction Fee Excluding Vat:</td>
                                    <td>{{$data->price->price*20/100}}</td>
                                </tr>
                                {{-- <tr>
                                    <td>Affiliator Commission:</td>
                                    <td>{{$data->affiliator_commission}}</td>
                                </tr> --}}
                                <tr>
                                    <td>Affiliator Commission Amount:</td>
                                    <td>{{$data->affiliator_commission_amount}}</td>
                                </tr>
                                {{-- <tr>
                                    <td>Product Link:</td>
                                    <td>{{$data->product_link}}</td>
                                </tr> --}}
                                <tr>
                                    <td>Delivery Format:</td>
                                    <td>
                                        <?php
                                            if($data->delivery_format == 'video'){
                                                echo '動画';
                                            }elseif($data->delivery_format == 'pdf'){
                                                echo 'PDF';
                                            }elseif($data->delivery_format == 'audio'){
                                                echo '音声';
                                            }elseif($data->delivery_format == 'chat'){
                                                echo 'チャット';
                                            }else{
                                                echo 'N/A';
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status:</td>
                                    <td>{{$data->status}}</td>
                                </tr>
                                <tr>
                                    <td>Expected Delivery Days:</td>
                                    <td>{{$data->expected_delivery_days}}</td>
                                </tr>
                                <tr>
                                    <td>No Of Order At A Time:</td>
                                    <td>{{$data->no_of_order_at_atime}}</td>
                                </tr>

                                <tr>
                                    <td>Estimate Or Customizable:</td>
                                    <td>{{$data->is_estimate_or_customizable}}</td>
                                </tr>

                                <tr>
                                    <td>Self Affiliate:</td>
                                    <td>{{$data->self_affiliate}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="col-md-12">
                                            @foreach($data->multipleImages as $multis)
                                                <div class="col-md-3">
                                                    <img src="/assets/images/products/{{$multis->image}}" style="width: 280px" />
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                            <?php
                                            if ($data->status == 'INACTIVE'){
                                                echo '<a href="'.route('admin-service-change-status', ['id' => $data->id, 'status'=> 'ACTIVE']).'" class="btn btn-sm btn-info">Activate</a> ';
                                            }elseif($data->status == 'ACTIVE'){
                                                echo '<a href="'.route('admin-service-change-status', ['id' => $data->id, 'status'=> 'INACTIVE']).'" class="btn btn-sm btn-danger">Deactivate</a> ';
                                            }
                                            ?>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('custom_js')
    
@endsection
