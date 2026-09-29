<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Something Went Wrong | AL SHAQRA Transport ERP</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: #f4f7fb;
            font-family: Arial, Helvetica, sans-serif;
            color: #101d42;
        }

        .error-card {
            width: 100%;
            max-width: 620px;
            background: #ffffff;
            border-radius: 18px;
            padding: 42px 36px;
            text-align: center;
            box-shadow: 0 15px 45px rgba(16, 29, 66, 0.12);
        }

        .error-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(37, 99, 235, 0.10);
            color: #2563eb;
            font-size: 34px;
            font-weight: 700;
        }

        .error-card h1 {
            margin: 0 0 12px;
            font-size: 28px;
            font-weight: 700;
            color: #101d42;
        }

        .error-card p {
            margin: 0 auto 26px;
            max-width: 500px;
            color: #64748b;
            font-size: 15px;
            line-height: 1.7;
        }

        .error-note {
            margin-bottom: 28px;
            padding: 14px 18px;
            border-radius: 10px;
            background: #f8fafc;
            color: #475569;
            font-size: 14px;
        }

        .back-btn {
            display: inline-block;
            padding: 11px 22px;
            border-radius: 8px;
            background: #101d42;
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .back-btn:hover {
            background: #193b8f;
        }

        .brand {
            margin-top: 28px;
            color: #94a3b8;
            font-size: 12px;
            letter-spacing: 0.3px;
        }

        @media (max-width: 576px) {
            .error-card {
                padding: 32px 22px;
            }

            .error-card h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

    <div class="error-card">

        <div class="error-icon">
            !
        </div>

        <h1>Something Went Wrong</h1>

        <p>
            {{ $message ?? 'Something went wrong. Please try again.' }}
        </p>

        <div class="error-note">
            The issue has been recorded in the system for administrator review.
        </div>

        <a href="{{ url()->previous() }}" class="back-btn">
            Go Back
        </a>

        <div class="brand">
            AL SHAQRA Transport ERP
        </div>

    </div>

</body>
</html>