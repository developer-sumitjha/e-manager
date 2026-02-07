<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trial Expired - E-Manager</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .error-container {
            background: white;
            border-radius: 16px;
            padding: 48px;
            max-width: 500px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .error-icon {
            font-size: 64px;
            color: #f59e0b;
            margin-bottom: 24px;
        }
        h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
        }
        p {
            color: #6b7280;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
            margin: 0 8px;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
        }
        .btn-secondary {
            background: #6b7280;
        }
        .btn-secondary:hover {
            box-shadow: 0 8px 20px rgba(107, 114, 128, 0.4);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⏰</div>
        <h1>Trial Period Expired</h1>
        <p>Your trial period has ended. Please subscribe to a plan to continue using the service.</p>
        <div style="margin-top: 24px;">
            <a href="{{ url('/pricing') }}" class="btn">View Plans</a>
            <a href="{{ url('/') }}" class="btn btn-secondary">Go to Homepage</a>
        </div>
    </div>
</body>
</html>
