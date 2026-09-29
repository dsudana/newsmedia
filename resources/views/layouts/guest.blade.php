<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Admin Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes slideInRight {
                from {
                    opacity: 0;
                    transform: translateX(20px);
                }

                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            body {
                font-family: 'Figtree', sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
            }

            .login-container {
                animation: fadeIn 0.6s ease-out;
            }

            .login-card {
                background: white;
                border-radius: 12px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                overflow: hidden;
            }

            .login-header {
                background: linear-gradient(135deg, #ED1C29 0%, #b31422 100%);
                padding: 40px 20px;
                text-align: center;
                color: white;
            }

            .login-header h1 {
                font-size: 28px;
                font-weight: 700;
                margin: 10px 0 5px;
            }

            .login-header p {
                font-size: 14px;
                opacity: 0.9;
            }

            .login-body {
                padding: 40px;
            }

            .form-group {
                margin-bottom: 24px;
            }

            .form-label {
                display: block;
                font-size: 14px;
                font-weight: 600;
                color: #171717;
                margin-bottom: 8px;
            }

            .form-input {
                width: 100%;
                padding: 12px 14px;
                border: 2px solid #e5e7eb;
                border-radius: 8px;
                font-size: 14px;
                transition: all 0.3s ease;
                font-family: inherit;
            }

            .form-input:focus {
                outline: none;
                border-color: #ED1C29;
                box-shadow: 0 0 0 3px rgba(237, 28, 41, 0.1);
            }

            .form-input::placeholder {
                color: #9ca3af;
            }

            .btn-login {
                width: 100%;
                padding: 12px;
                background: linear-gradient(135deg, #ED1C29 0%, #b31422 100%);
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                margin-top: 10px;
            }

            .btn-login:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(237, 28, 41, 0.3);
            }

            .btn-login:active {
                transform: translateY(0);
            }

            .checkbox-group {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-top: -8px;
                margin-bottom: 20px;
            }

            .checkbox-group label {
                display: flex;
                align-items: center;
                font-size: 13px;
                color: #666;
                cursor: pointer;
            }

            .checkbox-group input {
                margin-right: 6px;
                cursor: pointer;
            }

            .forgot-password {
                font-size: 13px;
                color: #ED1C29;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            .forgot-password:hover {
                color: #b31422;
                text-decoration: underline;
            }

            .error-message {
                background-color: #fee2e2;
                border: 1px solid #fca5a5;
                color: #b91c1c;
                padding: 10px 12px;
                border-radius: 6px;
                font-size: 13px;
                margin-bottom: 16px;
            }

            .logo-icon {
                width: 60px;
                height: 60px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 15px;
                font-size: 32px;
            }
        </style>
    </head>

    <body>
        <div class="min-h-screen flex items-center justify-center px-4 py-6">
            <div class="login-container w-full max-w-md">
                <div class="login-card">
                    <div class="login-header">
                        <div class="logo-icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <h1>NEWSMEDIA</h1>
                        <p>Admin Dashboard</p>
                    </div>

                    <div class="login-body">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>
