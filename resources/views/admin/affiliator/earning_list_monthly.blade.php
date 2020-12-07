@extends('admin.layouts.main')


@section('custom_css')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
@endsection



@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    
                    <div class="header">
                        <h2>Affiliator Earning List</h2>
                        <div class="header-dropdown m-r-0"></div>
                    </div>
                    <div class="header">
                        @if($source == 'SALE')
                            <h2>Sales Commission</h2>
                        @elseif($source == 'RECBONUS')
                            <h2>Recruiting Bonus</h2>
                        @elseif($source == 'RANKBONUS')
                            <h2>Ranking Bonus</h2>
                        @else($source == 'LOTTERY')
                            <h2>Lottery</h2>
                        @endif
                        
                    </div>
                    
                    <div class="body table-responsive">
                       
                        <table class="table" id="data-table">
                            <thead>
                                <tr>
                                    {{--<th class="text-center"> Name </th>
                                    <th class="text-center"> Product </th>
                                    <th class="text-center"> Price</th>
                                    <th class="text-center"> Earning Amount</th>
                                    <th class="text-center"> Percentage </th>
                                    <th class="text-center"> Created At </th>--}}
                                    {{--<th class="text-center"> Month </th>
                                    <th class="text-center"> Year </th>
                                    <th class="text-center"> Earning </th>--}}
                                    <th class="text-center"> Period </th>
                                    <th class="text-center"> Amount </th>
                                </tr>
                                @foreach ($aff_sales as $aff)
                                <tr>
                                    <?php  
                                        $monthName = date('F', mktime(0, 0, 0, $aff->month, 10)); 
                                        $real_month = $aff->month - 1;
                                        $range = date('F', mktime(0, 0, 0, $real_month, 10)); 
                                        $first_day = 1;
                                        if($real_month == 2){
                                            $last_day = 29;
                                        }else if($real_month == 4 || $real_month == 6 || $real_month == 9 || $real_month == 11){
                                            $last_day = 30;
                                        } else {
                                            $last_day = 31;
                                        } 
                                    ?>
                                    <td class="text-center">
                                        @if($source == 'SALE')
                                            <a href="{{route('admin-affiliator-earning-list', ['user_id' => $aff->child_affiliator_id, 'source' => $source, 'month' => $aff->month, 'year' => $aff->year])}}">
                                                {{$monthName}}, &nbsp;{{$aff->year}}
                                            </a>
                                        @elseif($source == 'RECBONUS')
                                            {{$first_day}} to {{$last_day}} {{$range}}, &nbsp;{{$aff->year}}
                                        @elseif($source == 'RANKBONUS')
                                            {{$first_day}} to {{$last_day}} {{$range}}, &nbsp;{{$aff->year}}
                                        @elseif($source == 'LOTTERY')
                                            {{$first_day}}, &nbsp;{{$aff->year}}
                                        @endif
                                    </td>
                                    <td class="text-center">{{$aff->earn}}</td>
                                </tr>
                                @endforeach
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





{{-- {{json_encode(['source' => request()->source, 'user_id' => request()->user_id])}} --}}
@endsection
@section('custom_js')
    <!-- <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function(){
            var dataTable = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth : false,
                //bSort: false,
                order: [ [5, 'desc'] ],
                ajax: {
                    url: "{{route('admin-affiliator-earning-list-data-monthly')}}",
                    data: {!!json_encode(['source' => request()->source, 'user_id' => request()->user_id])!!}
                },
                columns: [
                    { data: 'affiliator', name: 'affiliator' },
                    { data: 'product', name: 'product' },
                    { data: 'price', name: 'price' },
                    { data: 'earning_amount', name: 'earning_amount' },
                    { data: 'percent', name: 'percent' },
                    { data: 'created_at', name: 'created_at' }
                    // { data: 'month', name: 'month' },
                    // { data: 'year', name: 'year' },
                    // { data: 'earning', name: 'earning' },
                ]
            });
        });
    </script> -->
@endsection
