<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>

    <style>
        body {
            background: #f5f5f5;
        }
        .container {
            margin: auto;
            width: 50%;
            border: 1px solid #000000;
            padding: 51px;
            background:  #ffffff;
            border-radius: 10px;
            text-align: center;
        }

        .button-send-mail {        
            margin-top: 10px;
            padding: 20px 25px 20px 25px;
            background: #CC85B3;
            color: #ffffff;
            border-radius: 7px;
            border: none;
            font-weight: 900;
            font-size: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <p>Hi {!! $name !!}</p>
        <p>Thank you for creating a Zenitha account. For your security, please verify your account.</p>
        <a href="{{ route('email_verification', ['user_id' => $id]) }}">
            <button class="button-send-mail">Verify My Account</button>
        </a>
    </div>
</body>
</html>