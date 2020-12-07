<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="" style="text-align: left">

            <div class="content" style="text-align: left">
                <div>
                   <span>
                        [share-work]　管理者用<br/>
                   </span>
                </div>
                <div>
                    <span>
                        下記、お問合せがありました。
                    </span>
                </div>
                <div><br/></div>
                <div>
                   <span>
                        ----------------------------------------------------------------<br/>
                        お問い合せ内容<br/>
                        ----------------------------------------------------------------<br/>
                   </span>
                </div>
                <div><br/></div>
                <div>
                    <table style="width: 60%">
                        <tbody>
                            <tr>
                                <td style="width: 10%">お名前</td>
                                <td style="width: 90%">{{$name}}</td>
                            </tr>
                            <tr>
                                <td style="width: 10%">メールアドレス</td>
                                <td style="width: 90%">{{$mail}}</td>
                            </tr>
                            <tr style="vertical_align: top">
                                <td style="width: 10%, vertical_align: top">お問い合わせ内容</td>
                                <td style="width: 90%">
                                    <div style="vertical_align: top">
                                        <div>
                                            {{$title}}
                                        </div>
                                        <div>
                                            {{$content}}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div><br/></div>
                <div>
                    ---------------------------------------------<br/>
                    ※本メールは配信専用です。<br/>
                    このメールに返信いただいても対応できませんのであらかじめご了承ください。<br/>
                    <br/>
                    Copyright © 2020 株式会社C-group, All rights reserved.
                </div>
                </div>
            </div>
        </div>
    </body>
</html>
