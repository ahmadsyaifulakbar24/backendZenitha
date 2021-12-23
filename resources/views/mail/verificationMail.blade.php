<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        .container {
            margin: auto;
            width: 50%;
            border: 1px solid #000000;
            padding: 51px;
            background:  #ffffff;
            border-radius: 10px;
            text-align: center;
            margin-top: 200px;
            color: #0f5132;
            background: #d1e7dd;
            border-color: #badbcc;
        }
        .alert p{
            font-size: 20px;
            margin: 0;
        }

        @media (max-width: 768px) {
            .container {
                width: 70%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="alert">
            <p>Hi {!! $user->name !!}</p>
            <p>
                Thank you for creating a Zenitha account. 
                @if (!empty($error))
                    {{ $error }}
                @else
                    Your account is already active please login
                @endif 
            </p>
        </div>
    </div>
</body>
</html>