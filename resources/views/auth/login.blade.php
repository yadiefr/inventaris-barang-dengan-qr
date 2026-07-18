<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Inventaris Barang QR</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap">
    
    <style>
        :root {
            --bg-main: #0b0f19;
            --bg-card: rgba(22, 30, 47, 0.65);
            --border-color: rgba(36, 48, 73, 0.8);
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --primary: #06b6d4;
            --primary-glow: rgba(6, 182, 212, 0.3);
            --secondary: #8b5cf6;
            --secondary-glow: rgba(139, 92, 246, 0.3);
            --danger: #ef4444;
            --font-sans: 'Inter', sans-serif;
            --font-heading: 'Outfit', sans-serif;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            font-family: var(--font-sans);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
            position: relative;
        }

        /* Decorative Background Glows */
        body::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, var(--primary-glow) 0%, transparent 70%);
            top: -100px;
            left: -100px;
            z-index: 0;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, var(--secondary-glow) 0%, transparent 70%);
            bottom: -100px;
            right: -100px;
            z-index: 0;
            pointer-events: none;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            z-index: 10;
            position: relative;
        }

        /* Glassmorphism Card */
        .login-card {
            background: var(--bg-card);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4), 
                        inset 0 1px 1px rgba(255, 255, 255, 0.05);
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo and Header */
        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-box {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.4);
            animation: pulse 3s infinite alternate;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 15px rgba(6, 182, 212, 0.3);
            }
            100% {
                box-shadow: 0 0 25px rgba(139, 92, 246, 0.5);
            }
        }

        .logo-box svg {
            width: 32px;
            height: 32px;
            stroke: white;
            fill: none;
            stroke-width: 2.5;
        }

        .login-header h1 {
            font-family: var(--font-heading);
            font-size: 26px;
            font-weight: 700;
            background: linear-gradient(to right, #ffffff, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }

        .login-header p {
            color: var(--text-muted);
            font-size: 14px;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
            transition: var(--transition);
        }

        .input-icon svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        .form-control {
            width: 100%;
            padding: 13px 16px 13px 44px;
            background-color: rgba(11, 15, 25, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-main);
            font-family: var(--font-sans);
            font-size: 15px;
            outline: none;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 10px rgba(6, 182, 212, 0.2), 
                        inset 0 0 5px rgba(6, 182, 212, 0.1);
        }

        .form-control:focus + .input-icon {
            color: var(--primary);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.2);
        }

        /* Validation Alerts */
        .invalid-feedback {
            color: var(--danger);
            font-size: 12px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
            font-weight: 500;
        }

        .invalid-feedback svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        .alert {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.4s ease;
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #a7f3d0;
        }

        .alert svg {
            flex-shrink: 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Checkbox & Options */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
            font-size: 14px;
            color: var(--text-muted);
        }

        .remember-me input {
            display: none;
        }

        .checkbox-custom {
            width: 18px;
            height: 18px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            background-color: rgba(11, 15, 25, 0.6);
        }

        .remember-me input:checked + .checkbox-custom {
            background-color: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 0 8px rgba(6, 182, 212, 0.3);
        }

        .checkbox-custom svg {
            width: 12px;
            height: 12px;
            stroke: white;
            fill: none;
            stroke-width: 3;
            display: none;
        }

        .remember-me input:checked + .checkbox-custom svg {
            display: block;
        }

        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: var(--transition);
        }

        .forgot-password:hover {
            color: #22d3ee;
            text-shadow: 0 0 8px rgba(6, 182, 212, 0.3);
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 10px;
            color: white;
            font-family: var(--font-sans);
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);
            filter: brightness(1.1);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Footer Info */
        .login-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: var(--text-muted);
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 24px 20px;
            }
            .login-header h1 {
                font-size: 22px;
            }
            .login-header {
                margin-bottom: 24px;
            }
            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-card">
            
            <div class="login-header">
                <div class="logo-box">
                    <svg viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2m10 0h2a2 2 0 0 1 2 2v2m0 10v2a2 2 0 0 1-2 2h-2m-10 0H5a2 2 0 0 1-2-2v-2M7 12h10M12 7v10"/></svg>
                </div>
                <h1>QR Inventory</h1>
                <p>Silakan masuk untuk mengelola inventaris barang</p>
            </div>

            <!-- Flash Success Message -->
            @if(session('success'))
                <div class="alert alert-success">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14M22 4L12 14.01l-3-3"/></svg>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            <!-- General Error Alert -->
            @if($errors->has('email') && !$errors->has('password'))
                <!-- We display it below the email field directly, but if there's any other error, it can show here -->
            @endif

            <form action="{{ route('login') }}" method="POST" autocomplete="off">
                @csrf

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" id="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               placeholder="nama@email.com" 
                               value="{{ old('email') }}" 
                               required autofocus autocomplete="email">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </span>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="••••••••" 
                               required autocomplete="current-password">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="checkbox-custom">
                            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span>Ingat saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">
                    <span>Masuk</span>
                    <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.5" fill="none"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
            </form>

            <div class="login-footer">
                <p>&copy; {{ date('Y') }} QR Inventory System. All rights reserved.</p>
            </div>

        </div>
    </div>

</body>
</html>
