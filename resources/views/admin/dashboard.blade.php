@extends('admin.layouts.main')

@section('custom_css')
    <style>
        
        .item{
            cursor: pointer;
        }
    
    </style>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <!--
            <div class="block-header">
                <h2>DASHBOARD</h2>
            </div>-->

        
        <div class="row clearfix">
            @if($rights->service_list == 1)
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 item">
                <a href="{{ route('admin-service-list') }}" style="cursor:pointer;text-decoration:none;">
                    <div class="info-box bg-pink hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text">TOTAL SERVICES</div>
                            <div class="number count-to" data-from="0" data-to="125" data-speed="15"
                                data-fresh-interval="20">{{ $total_services }}</div>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if($rights->total_sale == 1)
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="">
                <a href="{{ route('admin-sales-list') }}" style="cursor:pointer;text-decoration:none;">
                    <div class="info-box bg-cyan hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">help</i>
                        </div>
                        <div class="content">

                            <div class="text">TOTAL SALES</div>
                            <div class="number count-to" data-from="0" data-to="257" data-speed="1000"
                                data-fresh-interval="20">{{ \floor($total_sales) }} 円</div>

                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if($rights->withdraw_request == 1)
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 item">
                <a class="info-box bg-light-green hover-expand-effect" href="{{route('admin-withdraw-list')}}" style="cursor:pointer;text-decoration:none;">
                    <div class="icon">
                        <i class="material-icons">forum</i>
                    </div>
                    <div class="content">
                        <div class="text">WITHDRAW REQUESTS</div>
                        <div class="number count-to" data-from="0" data-to="243" data-speed="1000"
                            data-fresh-interval="20">{{ $withdraw_request }}</div>
                    </div>
                </a>
            </div>
            @endif

            @if($rights->buyers == 1)
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 item">
                <a href="{{route('admin-buyer-list')}}" style="cursor:pointer;text-decoration:none;">
                    <div class="info-box bg-black hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">person_add</i>
                        </div>
                        <div class="content">
                            <div class="text">BUYERS</div>
                            <div class="number count-to" data-from="0" data-to="1225" data-speed="1000"
                                data-fresh-interval="20">{{ $total_buyer }}</div>
                        </div>
                    </div>
                </a>
            </div>
            @endif
            
            @if($rights->sellers == 1)
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 item">
                <a href="{{route('admin-seller-list')}}" style="cursor:pointer;text-decoration:none;">
                    <div class="info-box bg-red hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text">SELLERS</div>
                            <div class="number count-to" data-from="0" data-to="125" data-speed="15"
                                data-fresh-interval="20">{{ $total_seller }}</div>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if($rights->affiliates == 1)
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 item">
                <a href="{{route('admin-affiliator-list')}}" style="cursor:pointer;text-decoration:none;">
                    <div class="info-box bg-blue hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">help</i>
                        </div>
                        <div class="content">

                            <div class="text">AFFILIATORS</div>
                            <div class="number count-to" data-from="0" data-to="257" data-speed="1000"
                                data-fresh-interval="20">{{ $total_affiliator }}</div>

                        </div>
                    </div>
                </a>
            </div>
            @endif
            
            @if($rights->direct_message == 1)
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 item">
                <a class="info-box bg-orange hover-expand-effect" href="{{route('admin-direct-message-list')}}" style="cursor:pointer;text-decoration:none;">
                    <div class="icon">
                        <i class="material-icons">person_add</i>
                    </div>
                    <div class="content">
                        <div class="text">DIRECT MESSAGE</div>
                        <div class="number count-to" data-from="0" data-to="1225" data-speed="1000"
                            data-fresh-interval="20">{{$direct_message}}</div>
                    </div>
                </a>
            </div>
            @endif

            @if($rights->lottery == 1)
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 item">
                <a class="info-box bg-purple hover-expand-effect" href="{{route('admin-lottery-list')}}" style="cursor:pointer;text-decoration:none;">
                    <div class="icon">
                        <i class="material-icons">person_add</i>
                    </div>
                    <div class="content">
                        <div class="text">LOTTERY</div>
                        <div class="number count-to" data-from="0" data-to="1225" data-speed="1000"
                        data-fresh-interval="20">{{$lottery}}</div>
                    </div>
                </a>
            </div>
            @endif

            @if($rights->contact_us == 1)
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 item">
                <a class="info-box bg-purple hover-expand-effect" href="{{route('admin-contact-us')}}" style="cursor:pointer;text-decoration:none;">
                    <div class="icon">
                        <i class="material-icons">person_add</i>
                    </div>
                    <div class="content">
                        <div class="text">CONTACT MESSAGES</div>
                        <div class="number count-to" data-from="0" data-to="1225" data-speed="1000"
                        data-fresh-interval="20">{{$contacts}}</div>
                    </div>
                </a>
            </div>
            @endif
        </div>
    </div>
</section>

@endsection
