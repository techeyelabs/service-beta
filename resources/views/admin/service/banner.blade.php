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
                        <h2>Service Banners</h2>
                        <div class="header-dropdown m-r-0"></div>
                    </div>
                    
                    <div class="body table-responsive">
                        <table class="table" id="data-table">
                            <thead>
                                <tr>
                                    <td>Serial</td>
                                    <td>Image name</td>
                                    <td>Service Link</td>
                                    <td style="text-align: center">Status</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; foreach($data as $d) {?>
                                    <tr>
                                        <td style="vertical-align: middle">{{$i++}}</td>
                                        <td style="vertical-align: middle">
                                            <table style="border: none">
                                                <tr style="border: none">
                                                    <td style="width: 65%; border-top: none; border-bottom: none">{{$d->image_name}}&nbsp;</td>
                                                    <td style="width: 35%; border-top: none; border-bottom: none"><img style="height: 45px; width: auto" src="/assets/topslider/<?php echo $d->image_name;?>"/></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style="vertical-align: middle">{{$d->link}}</td>
                                        <td style="vertical-align: middle; text-align: center">
                                            <?php if($d->status == 'ACTIVE') {?>
                                                <a href="{{route('admin-banner-change-status', $d->id)}}?{{time()}}">
                                                    <i class="fa fa-trash-o"></i>
                                                </a> 
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <div>&nbsp;</div>
                        <?php if(count($data) <= 15) {?>
                        <form method="post" id="upload_form" action="{{url('admin/service/save-banner')}}" enctype="multipart/form-data">
                        <table style="width: 100%">
                            <tbody>
                                <tr>
                                    <td style="width: 50%; text-align: center">
                                        Banner Image
                                    </td>
                                    <td style="width: 50%">
                                        Service Link
                                    </td>
                                </tr>
                                <tr><td colspan="2">&nbsp;</td></tr>
                                <tr>
                                    <td style="width: 50%; text-align: center; padding-left: 20%">
                                        <input id="banner_file" name="banner_file" type="file" onchange="checkSize()" required/>
                                    </td>
                                    <td style="width: 50%">
                                        <textarea id="link" name="link" style="width: 320px; border: 1px solid #d2d1d1; border-radius: 4px" type="text" ></textarea>
                                    </td>
                                </tr>
                                <tr><td colspan="2">&nbsp;</td></tr>
                                <tr>
                                    <td colspan="2" style="text-align: center">
                                        <button id="postbutton" name="postbutton" type="submit" style="padding: 4px; border-radius: 5px; width: 50px">Send</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('custom_js')

    <script>
        function checkSize()
        {
            var upl = document.getElementById("banner_file");
            if(upl.files[0].size > 3145728){
                alert("Image cannot be bigger then 3MB");
                $('#banner_file').val('');
            } else if(upl.files[0].size < 20971) {
                alert("Image cannot be smaller then 20KB");
                $('#banner_file').val('');
            }
        }
    </script>

@endsection