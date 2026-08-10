<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Al Shaqra Transport ERP</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, Helvetica, sans-serif;
        }

        body{
            background:#0d1b2a;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }

        .login-box{
            width:420px;
            background:white;
            border-radius:15px;
            padding:35px;
            box-shadow:0 15px 40px rgba(0,0,0,.3);
            text-align:center;
        }

        .logo{
            width:220px;
            margin-bottom:20px;
        }

        h2{
            color:#1d3557;
            margin-bottom:25px;
        }

        input{
            width:100%;
            padding:13px;
            margin-bottom:18px;
            border:1px solid #ccc;
            border-radius:8px;
            font-size:15px;
        }

        button{
            width:100%;
            padding:14px;
            background:#d4a017;
            color:white;
            border:none;
            border-radius:8px;
            font-size:16px;
            cursor:pointer;
            font-weight:bold;
        }

        button:hover{
            background:#b8860b;
        }

        .footer{
            margin-top:18px;
            color:#777;
            font-size:13px;
        }
    </style>

</head>

<body>

<div class="login-box">

    <img src="{{ asset('images/logo.jpeg') }}" class="logo">

    <h2>Transport ERP Login</h2>

    <form action="{{ route('login') }}" method="POST">

    @csrf

    <input
        type="email"
        name="email"
        placeholder="Email Address"
        value="{{ old('email') }}"
        required>

    <input
        type="password"
        name="password"
        placeholder="Password"
        required>

    <button type="submit">Login</button>

</form>

@if($errors->any())

    <p style="color:red;margin-top:15px;">
        {{ $errors->first() }}
    </p>

@endif

    <div class="footer">
        © 2026 Al Shaqra Transport
    </div>

</div>

</body>
</html>