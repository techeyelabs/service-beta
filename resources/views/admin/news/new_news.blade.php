@extends('admin.layouts.main')


@section('custom_css')
@endsection



@section('content')
<section class="content" id="app">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">

                    <div class="header">
                        <h2>New News</h2>
                        <div class="header-dropdown m-r-0"></div>
                    </div>

                    <div class="body">

                        @include('admin.layouts.message')

                        <div class="row">
                            <form action="" method="post">
                            {{ csrf_field() }}
                            
                            <div class="col-md-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2>News</h2>
                                            <div class="header-dropdown m-r-0"></div>
                                        </div>
                                        <div class="body table-responsive">
                                            {{-- <div class="form-group">
                                                <select name="message_type" id="" class="form-control">
                                                    <option value="CHAT" {{old('message_type') == 'CHAT' ? 'selected': ''}}>CHAT</option>
                                                    <option value="NOTIFICATION" {{old('message_type') == 'NOTIFICATION' ? 'selected': ''}}>NOTIFICATION</option>
                                                </select>
                                            </div> --}}
                                            <div class="form-group">
                                                <textarea name="message" id="" class="form-control" placeholder="type your news here..." required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Send</button>
                                        </div>
                                    </div>
                            </div>
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
    
@endsection
