<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            overflow: hidden;
        }

        .login-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Sección izquierda - Formulario */
        .login-form-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            position: relative;
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #718096;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f7fafc;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-input.error {
            border-color: #e53e3e;
        }

        .error-message {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .remember-me input {
            width: 18px;
            height: 18px;
            margin-right: 0.5rem;
            cursor: pointer;
        }

        .remember-me label {
            color: #4a5568;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 126, 0.4);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 126, 0.5);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Sección derecha - Background */
        .login-background-section {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .background-pattern {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0.1;
            background-image:
                radial-gradient(circle at 25% 25%, white 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, white 2px, transparent 2px);
            background-size: 50px 50px;
        }

        .background-content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: white;
            padding: 2rem;
        }

        .background-content h2 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .background-content p {
            font-size: 1.25rem;
            opacity: 0.9;
            max-width: 500px;
            margin: 0 auto;
        }

        .logo-container {
            margin-bottom: 2rem;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .logo-text {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .login-container {
                flex-direction: column;
            }

            .login-background-section {
                display: none;
            }

            .login-form-section {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }

            .login-form-wrapper {
                padding: 2rem;
            }
        }

        @media (max-width: 640px) {
            .login-form-wrapper {
                padding: 1.5rem;
                border-radius: 15px;
            }

            .login-header h1 {
                font-size: 1.5rem;
            }
        }

        /* Animaciones */
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

        .login-form-wrapper {
            animation: fadeIn 0.6s ease-out;
        }

        .background-content {
            animation: fadeIn 0.8s ease-out;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Sección Izquierda - Formulario -->
        <div class="login-form-section">
            <div class="login-form-wrapper">
                <div class="login-header">
                    <div class="logo-container">
                        <div class="logo">
                            <span class="logo-text">PS</span>
                        </div>
                    </div>
                    <h1>PlaySpot</h1>
                    <p>Sistema de Reservas de Canchas</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div style="background: #c6f6d5; color: #22543d; padding: 0.75rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="form-group">
                        <label class="form-label" for="email">Correo Electrónico</label>
                        <input
                            id="email"
                            class="form-input @error('email') error @enderror"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="tu@email.com"
                        />
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label" for="password">Contraseña</label>
                        <input
                            id="password"
                            class="form-input @error('password') error @enderror"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                        />
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="remember-me">
                        <input id="remember_me" type="checkbox" name="remember">
                        <label for="remember_me">Recordarme</label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-login">
                        Iniciar Sesión
                    </button>
                </form>
            </div>
        </div>

        <!-- Sección Derecha - Background -->
        <div class="login-background-section">
            <div class="background-pattern"></div>
            <div class="background-content">
                <h2>Bienvenido</h2>
                <p>Gestiona tus reservas de canchas deportivas de manera eficiente y en tiempo real</p>
            </div>
        </div>
    </div>
</body>
</html>

