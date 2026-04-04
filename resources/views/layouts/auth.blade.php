<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — NoteApp</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-color: #F3F4F4; /* Default matching --color-bg */
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', sans-serif;
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(33, 52, 72, 0.1);
        }
        .auth-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 30px;
        }
        .auth-logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #547792, #94B4C1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .auth-logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: #213448;
        }
        .auth-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #213448;
            margin-bottom: 8px;
            text-align: center;
        }
        .auth-subtitle {
            font-size: 0.9rem;
            color: #7a96a8;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #547792;
            margin-bottom: 8px;
        }
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #c8d8e0;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: #547792;
            box-shadow: 0 0 0 3px rgba(84, 119, 146, 0.1);
        }
        .btn-auth {
            width: 100%;
            padding: 14px;
            background-color: #213448;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 10px;
        }
        .btn-auth:hover {
            background-color: #182636;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(33, 52, 72, 0.2);
        }
        .auth-footer {
            margin-top: 25px;
            text-align: center;
            font-size: 0.9rem;
            color: #7a96a8;
        }
        .auth-link {
            color: #547792;
            text-decoration: none;
            font-weight: 600;
        }
        .auth-link:hover {
            text-decoration: underline;
        }
        .error-message {
            color: #c0392b;
            font-size: 0.8rem;
            margin-top: 5px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">
            <div class="auth-logo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                    <path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm-7 3a1 1 0 110 2 1 1 0 010-2zm3 10H9a1 1 0 010-2h6a1 1 0 010 2zm0-4H9a1 1 0 010-2h6a1 1 0 010 2z"/>
                </svg>
            </div>
            <span class="auth-logo-text">NoteApp</span>
        </div>
        
        @yield('content')
    </div>
</body>
</html>
