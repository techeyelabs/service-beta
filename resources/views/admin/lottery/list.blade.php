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
                        <h2>Lottery Eligible Affiliator List</h2>
                        <div class="header-dropdown m-r-0"></div>
                    </div>
                    <div class="header">
                        <h2>Total Budget: {{$total_for_lottery}}</h2>
                    </div>
                    <div class="body table-responsive">
                       
                        <table class="table" id="data-table">
                            <thead>
                                <tr>
                                    <th class="text-center"> Pic </th>
                                    <th class="text-center"> ID </th>
                                    <th class="text-center"> Name </th>
                                    <th class="text-center"> Email </th>
                                    <th class="text-center"> Purchase Amount</th>
                                    <th class="text-center"> Balance</th>
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
<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Lottery winners</h2>
                        <div class="header-dropdown m-r-0"></div>
                    </div>
                    @if($errors->any())
                        <div class="header header-dropdown m-r-0"><h4>{{$errors->first()}}</h4></div>
                    @endif
                    <div class="body table-responsive">
                        <div style="margin-left: 35%">
                            <input style="display: none" id="count" name="count" type="number" value = '0'/>
                            <form method="post" id="winners" action="{{url('admin/lottery/add-winners')}}" enctype="multipart/form-data">
                                <table style="width: 100%">
                                    <tbody>
                                        <tr><td colspan="2">&nbsp;</td></tr>
                                        <tr>
                                            <div>
                                                <div></div>
                                                <div class="text-left">
                                                    <input id="winner0" name="winner0" type="number" required/>&nbsp;<input id="amount0" name="amount0" type="number" />&nbsp;
                                                    <button id="add_btn" style="border-radius: 5px">add more</button>
                                                </div><br/>
                                                <div id="more" class="text-left">
                                                <div>
                                            </div>
                                        </tr>
                                        <tr><td colspan="2">&nbsp;</td></tr>
                                        <tr>
                                            <td colspan="2" style="text-align: left; padding-left: 10%">
                                                <button id="postbutton" name="postbutton" type="submit" style="padding: 4px; border-radius: 5px; width: 50px">Send</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('custom_js')
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function(){
            var dataTable = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth : false,
                //bSort: false,
                // order: [ [5, 'desc'] ],
                ajax: "{{route('admin-lottery-list-data')}}",
                columns: [
                    { data: 'profile_pic', name: 'profile_pic' },
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'total_purchase_amount', name: 'total_purchase_amount' },
                    { data: 'balance', name: 'balance' }
                ]
            });
        });
        $("#add_btn").click(function(e){ 
            e.preventDefault(); 
            var count_prev = $('#count').val();
            $('#count').val(++count_prev);
            var count = $('#count').val();
            var x = '<div><input id=winner'+count+' name=winner'+count+' type="number" val="" required/>&nbsp;<input id=amount'+count+' name=amount'+count+' type="number" val="" required />&nbsp;<button style="border-radius: 5px" id=btn'+count+' onclick="remove(event,'+count+')">remove</button><br/></div>';
            $("#more").append(x);
        });
        function remove(ev, x)
        {
            ev.preventDefault();
            var name1 = 'winner'+x;
            var name2 = 'amount'+x;
            var btn_name = 'btn'+x;
            $('#'+name1).parent('.div').remove();
            $('#'+name2).parent('.div').remove();
            $('#'+name1).remove();
            $('#'+name2).remove();
            $('#'+btn_name).remove();
        }
        // function send(e)
        // {
        //     e.preventDefault();
        //     $( "#winners" ).submit();
        // }
    </script>
@endsection
