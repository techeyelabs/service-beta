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
                        {{$name}} さん
                   </span>
                </div>
                <div>
                    <span>
                        お問い合わせを受け付けました。
                    </span>
                </div>
                <div><br/></div>
                <div>
                   <span>
                        運営スタッフより回答させて頂きます。
                   </span>
                </div>
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
                                <td style="width: 20%">お名前</td>
                                <td style="width: 80%">{{$name}}</td>
                            </tr>
                            <tr>
                                <td style="width: 20%">メールアドレス</td>
                                <td style="width: 80%">{{$mail}}</td>
                            </tr>
                            <tr style="vertical_align: top">
                                <td style="width: 20%, vertical_align: top">お問い合わせ内容</td>
                                <td style="width: 80%">
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
                    <span>
                        ----------------------------------------------------------------<br/>
                   </span>
                </div>
                <div>
                    <span>
                        ※こちらのメールは送信専用のメールアドレスより送信しています。恐れ入りますが、直接返信しないようお願いします。<br/>
                   </span>
                </div>
                <div>
                    <span>
                        ＝＝＝ サポート業務について ＝＝＝<br/>
                   </span>
                </div>
                <div>
                    <span>
                        土日・祝日・弊社休業期間中にいただいたお問い合わせは、翌営業日以降に回答いたします。<br/>
                   </span>
                </div>
                <div>
                    <span>
                        迅速な対応を目指しておりますが、内容によって回答までにお時間をいただくことがございます。あらかじめご了承ください。<br/>
                   </span>
                </div>
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
