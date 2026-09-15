<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Al Shaqra Transport ERP</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont,
                "Segoe UI", Arial, sans-serif;
            background:
                radial-gradient(
                    circle at 82% 8%,
                    #1d4ed8 0%,
                    #0f172a 36%,
                    #07111f 100%
                );
            color: #0f172a;
        }

        .login-page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 0.9fr 1.1fr;
            overflow: hidden;
        }

        /* LEFT SIDE */

        .login-left {
            padding: 72px 78px;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .brand-logo {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            object-fit: contain;
            background: #fff;
            padding: 5px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, .20);
        }

        .brand-title {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: .2px;
            line-height: 1.15;
        }

        .brand-subtitle {
            margin-top: 5px;
            color: #aebbd0;
            font-size: 12px;
            font-weight: 500;
        }

        .hero-content {
            margin-top: 110px;
        }

        .hero-content h1 {
            max-width: 620px;
            font-size: 54px;
            line-height: .98;
            letter-spacing: -.05em;
            font-weight: 800;
            color: #fff;
        }

        .hero-content p {
            max-width: 590px;
            margin-top: 24px;
            color: #cbd5e1;
            font-size: 18px;
            line-height: 1.7;
        }

        .feature-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 34px;
        }

        .feature-pill {
            height: 38px;
            display: inline-flex;
            align-items: center;
            padding: 0 14px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, .15);
            background: rgba(255, 255, 255, .08);
            color: #e2e8f0;
            font-size: 13px;
            font-weight: 700;
            backdrop-filter: blur(8px);
        }

        .feature-pill.active {
            background: #0f172a;
            border-color: #0f172a;
            color: #fff;
        }

        /* RIGHT SIDE */

        .login-right {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .login-card {
            width: 460px;
            max-width: 100%;
            background: #fff;
            border-radius: 32px;
            padding: 34px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, .35);
        }

        .login-card h2 {
            margin: 0;
            color: #0f172a;
            font-size: 28px;
            font-weight: 800;
        }

        .login-card-subtitle {
            margin-top: 6px;
            color: #64748b;
            font-size: 14px;
        }

        .login-form {
            margin-top: 24px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            margin-bottom: 7px;
            color: #334155;
            font-size: 13px;
            font-weight: 700;
        }

        .login-input {
            width: 100%;
            height: 54px;
            padding: 0 16px;
            border: 1px solid #dbe3ef;
            border-radius: 16px;
            background: #fff;
            color: #1e293b;
            font-size: 14px;
            outline: none;
            transition: .2s ease;
        }

        .login-input::placeholder {
            color: #94a3b8;
        }

        .login-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .10);
        }

        .login-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-top: 16px;
        }

        .remember-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #475569;
            font-size: 13px;
            font-weight: 600;
        }

        .remember-wrap input {
            width: 15px;
            height: 15px;
            accent-color: #2563eb;
            cursor: pointer;
        }

        .forgot-link {
            color: #2563eb;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            height: 54px;
            margin-top: 20px;
            border: 0;
            border-radius: 16px;
            background: #2563eb;
            color: #fff;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 10px 22px rgba(37, 99, 235, .22);
            transition: .2s ease;
        }

        .login-btn:hover {
            background: #1d4ed8;
        }

        .language-note {
            margin-top: 20px;
            text-align: center;
            color: #64748b;
            font-size: 12px;
        }

        .language-note .arabic {
            font-family: Arial, sans-serif;
            direction: rtl;
        }

        .error-box {
            margin-top: 16px;
            padding: 11px 13px;
            border-radius: 10px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            font-size: 13px;
            font-weight: 600;
        }

        /* RESPONSIVE */

        @media (max-width: 1050px) {
            .login-page {
                grid-template-columns: 1fr;
            }

            .login-left {
                padding: 50px 40px 30px;
            }

            .hero-content {
                margin-top: 55px;
            }

            .hero-content h1 {
                font-size: 44px;
            }

            .login-right {
                padding: 30px 40px 60px;
            }
        }

        @media (max-width: 600px) {
            .login-left {
                padding: 35px 22px 25px;
            }

            .brand-logo {
                width: 52px;
                height: 52px;
            }

            .brand-title {
                font-size: 16px;
            }

            .hero-content {
                margin-top: 45px;
            }

            .hero-content h1 {
                font-size: 38px;
            }

            .hero-content p {
                font-size: 15px;
            }

            .login-right {
                padding: 20px 22px 40px;
            }

            .login-card {
                padding: 26px 22px;
                border-radius: 24px;
            }

            .login-options {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<div class="login-page">

    <!-- LEFT SIDE -->

    <div class="login-left">

        <div class="brand">

            <img
                src="{{ asset('images/logo.jpeg') }}"
                alt="Al Shaqra Transport Logo"
                class="brand-logo"
            >

            <div>
                <div class="brand-title">
                    AL SHAQRA TRANSPORT
                </div>

                <div class="brand-subtitle">
                    Truck Rental & Fleet Management ERP
                </div>
            </div>

        </div>

        <div class="hero-content">

            <h1>
                Manage every truck, driver and client from one platform.
            </h1>

            <p>
                Modern bilingual ERP for UAE transport companies:
                fleet utilization, driver visa tracking, assignment control,
                invoices, payroll and profitability analytics.
            </p>

            <div class="feature-pills">

                <div class="feature-pill active">
                    English
                </div>

                <div class="feature-pill">
                    العربية
                </div>

                <div class="feature-pill">
                    UAE VAT Ready
                </div>

            </div>

        </div>

    </div>

    <!-- RIGHT SIDE -->

    <div class="login-right">

        <div class="login-card">

            <h2>
                Sign in
            </h2>

            <p class="login-card-subtitle">
                Access your fleet command center
            </p>

            <form
                action="{{ route('login') }}"
                method="POST"
                class="login-form"
            >

                @csrf

                <div class="form-group">

                    <label
                        for="email"
                        class="form-label"
                    >
                        Email address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="login-input"
                        placeholder="Enter your email address"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                    >

                </div>

                <div class="form-group">

                    <label
                        for="password"
                        class="form-label"
                    >
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="login-input"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    >

                </div>
                <div class="login-options">
    <label class="remember-wrap">
        <input type="checkbox" name="remember" value="1">
        <span>Remember me</span>
    </label>

    <span class="forgot-link">Forgot password?</span>
</div>

<button type="submit" class="login-btn">
    Login to Dashboard
</button>
</form>

@if($errors->any())
    <div class="error-box">
        {{ $errors->first() }}
    </div>
@endif

<div class="language-note">
    English interface ·
    <span class="arabic">العربية</span>
    supported
</div>

</div>
</div>
</div>

</body>
</html>