@extends('authentication.email.template')
@section('content')

    <img src="http://crofun.jp/assets/front/img/logo.png" alt="">

    <p>
        クラウドファンディングFundをご利用いただき、誠にありがとうございます。
    </p>
    <p>
        下記のURLをクリックしてパスワードを再設定してください。
    </p>
    <br>
    <p style="text-align:center;">
        <a style="background-color: #3097D1;color: #fff;padding:10px 20px;border-radius:3px;clear:both;" href="">パソワード再設定</a>
    </p>
    <br>
    <p>
        ＊パスワードの再設定の操作をした憶えがない場合は、本メールは無視していただくか、
    </p>
    <p>
        Fundのお問い合わせページよりお問い合わせください。
    </p>


@endsection
