<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Registration Successful</title>

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
                    <table style="width: 60%">
                        <tbody>
                            <tr>
                                <td style="width: 100%">{{$name}}さん</td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td style="width: 100%">share-workのご利用、いつもありがとうございます。</td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td style="width: 100%">パスワードを再設定するには、下記のURLをクリックしてパスワード変更のページにアクセスしていただく必要があります。</td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td style="width: 100%">ページの案内に従って、パスワードを再設定してください。</td>
                            </tr>
                            <tr>
                                <td style="width: 100%">{{$link}}</td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td style="width: 100%">※こちらのメールは送信専用のメールアドレスより送信しています。恐れ入りますが、直接返信しないようお願いします。</td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td style="width: 100%">今後ともshare-work をどうぞよろしくお願いいたします。</td>
                            </tr>
                            <tr>
                                <td style="width: 100%"></td>
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
                        ※ ご不明な点やお問い合せ<br/>
                   </span>
                    <span>
                        https://share-work.jp/contact-us<br/>
                   </span>
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
