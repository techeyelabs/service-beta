@extends('admin.layouts.main')


@section('custom_css')
<style>
    .chat_window {
        /* position: absolute;
    width: calc(100% - 20px);
    max-width: 800px;
    height: 500px;
    border-radius: 10px;
    background-color: #fff;
    left: 50%;
    top: 50%;
    transform: translateX(-50%) translateY(-50%); */
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        background-color: #f8f8f8;
        overflow: hidden;
    }

    .top_menu {
        background-color: #fff;
        width: 100%;
        padding: 20px 0 15px;
        box-shadow: 0 1px 30px rgba(0, 0, 0, 0.1);
    }

    .top_menu .buttons {
        margin: 3px 0 0 20px;
        position: absolute;
    }

    .top_menu .buttons .button {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 10px;
        position: relative;
    }

    .top_menu .buttons .button.close {
        background-color: #f5886e;
    }

    .top_menu .buttons .button.minimize {
        background-color: #fdbf68;
    }

    .top_menu .buttons .button.maximize {
        background-color: #a3d063;
    }

    .top_menu .title {
        text-align: center;
        color: #bcbdc0;
        font-size: 20px;
    }

    .messages {
        position: relative;
        list-style: none;
        padding: 20px 10px 0 10px;
        margin: 0;
        height: 347px;
        overflow: scroll;
    }

    .messages .message {
        clear: both;
        overflow: hidden;
        margin-bottom: 20px;
        transition: all 0.5s linear;
        opacity: 0;
    }

    .messages .message.left .avatar {
        /* background-color: #f5886e; */
        background-color: #DDD;
        float: left;
    }

    .messages .message.left .text_wrapper {
        /* background-color: #ffe6cb; */
        background-color: #DDD;
        margin-left: 20px;
    }

    .messages .message.left .text_wrapper::after,
    .messages .message.left .text_wrapper::before {
        right: 100%;
        /* border-right-color: #ffe6cb; */
        border-right-color: #DDD;
    }

    .messages .message.left .text {
        /* color: #c48843; */
        color: #000;
    }

    .messages .message.right .avatar {
        background-color: #fdbf68;
        float: right;
    }

    .messages .message.right .text_wrapper {
        background-color: #c7eafc;
        margin-right: 20px;
        float: right;
    }

    .messages .message.right .text_wrapper::after,
    .messages .message.right .text_wrapper::before {
        left: 100%;
        /* border-left-color: #c7eafc; */
        border-left-color: #c7eafc;
    }

    .messages .message.right .text {
        color: #45829b;
    }

    .messages .message.appeared {
        opacity: 1;
        width: 100%;
    }

    .messages .message .avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: inline-block;
    }

    .messages .message .text_wrapper {
        display: inline-block;
        padding: 20px;
        border-radius: 6px;
        width: calc(100% - 85px);
        min-width: 100px;
        position: relative;
        min-width: 200px;
    }

    .messages .message .text_wrapper::after,
    .messages .message .text_wrapper:before {
        top: 18px;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
    }

    .messages .message .text_wrapper::after {
        border-width: 13px;
        margin-top: 0px;
    }

    .messages .message .text_wrapper::before {
        border-width: 15px;
        margin-top: -2px;
    }

    .messages .message .text_wrapper .text {
        font-size: 18px;
        font-weight: 300;
    }

    .bottom_wrapper {
        position: relative;
        width: 100%;
        background-color: #fff;
        padding: 20px 20px;
        /* position: absolute; */
        bottom: 0;
    }

    .bottom_wrapper .message_input_wrapper {
        display: inline-block;
        height: 50px;
        border-radius: 25px;
        border: 1px solid #bcbdc0;
        width: calc(100% - 160px);
        position: relative;
        padding: 0 20px;
    }

    .bottom_wrapper .message_input_wrapper .message_input {
        border: none;
        height: 100%;
        box-sizing: border-box;
        width: calc(100% - 40px);
        position: absolute;
        outline-width: 0;
        color: gray;
    }

    .bottom_wrapper .send_message {
        width: 140px;
        height: 50px;
        display: inline-block;
        border-radius: 50px;
        background-color: #a3d063;
        border: 2px solid #a3d063;
        color: #fff;
        cursor: pointer;
        transition: all 0.2s linear;
        text-align: center;
        float: right;
    }

    .bottom_wrapper .send_message:hover {
        color: #a3d063;
        background-color: #fff;
    }

    .bottom_wrapper .send_message .text {
        font-size: 18px;
        font-weight: 300;
        display: inline-block;
        line-height: 48px;
    }

    .message_template {
        display: none;
    }

</style>
@endsection



@section('content')
<section class="content" id="app">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">

                    <div class="header">
                        <h2>Message Details</h2>
                        <div class="header-dropdown m-r-0"></div>
                    </div>

                    <div class="body table-responsive">


                        <div class="chat_window">
                            <div class="top_menu">
                                <div class="buttons">
                                    <div class="button close"></div>
                                    <div class="button minimize"></div>
                                    <div class="button maximize"></div>
                                </div>
                                <div class="title">{{$userInfo->first_name.' '.$userInfo->last_name}}</div>
                            </div>

                            <ul class="messages">

                                
                                    <li class="message appeared" v-for="message in messages" :class="message.receiver_id == 0?'right':'left'">
                                        <div class="avatar">
                                            <img v-if="message.receiver_id == 0" width="60" height="60" :src="'/assets/images/users/'+message.profile_pic" alt="">
                                            <img v-else width="60" height="60" src="{{request()->root().'/assets/images/users/blank.png'}}" alt="">
                                        </div>
                                        <div class="text_wrapper">
                                            <small>@{{message.created_at}}</small>
                                            <div class="text" v-html="message.content"></div>
                                        </div>
                                    </li>
                               
                                
                                
                                
                            </ul>

                            {{-- <form action=""> --}}
                                <div class="bottom_wrapper clearfix">
                                    <div class="message_input_wrapper">
                                        <textarea class="message_input"
                                                placeholder="Type your message here..." v-model="message">
                                        </textarea>
                                    </div>
                                    <button class="send_message" type="button" v-on:click="send">
                                        <div class="icon"></div>
                                        <div class="text">Send</div>
                                    </button>
                                </div>
                            {{-- </form> --}}
                        </div>
                        <div class="message_template">
                            <li class="message">
                                <div class="avatar"></div>
                                <div class="text_wrapper">
                                    <div class="text"></div>
                                </div>
                            </li>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('custom_js')
<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    var app = new Vue({
        el: '#app',
        data: {
            messages: [],
            message: ''
        },
        methods:{
            getMessageList(){
                var that = this;
                axios.get('{{route("admin-direct-message-details-list", ["user_id" => request()->user_id])}}')
                .then(function (response) {
                    if(response.data.status){
                        console.log(that.messages);
                        that.messages = that.messages.concat(response.data.data);
                        console.log('that.messages');
                        console.log(that.messages);
                    }
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                    alert('error sending message');
                })
                .finally(function () {
                    // always executed
                });
            },
            send(e){
                e.preventDefault();
                var that = this;
                if(that.message == ''){
                    alert('type your message');
                    return false;
                }
                axios.get('{{route("admin-direct-message-details-send")}}', {
                    
                    params: {
                        user_id: '{{request()->user_id}}',
                        message: that.message
                    }
                
                })
                .then(function (response) {
                    if(response.data.status){
                        that.messages = that.messages.concat({
                            'user_id' : 'request()->user_id',
                            'receiver_id' : 'request()->user_id',
                            'sender_id' : 0,
                            'content' : response.data.message,
                            'created_at' : '{{date("Y-m-d H:i:s")}}'
                        });
                    }
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .finally(function () {
                    // always executed
                    that.message = '';
                });
            }
        }
    });

    app.getMessageList();
</script>

@endsection
