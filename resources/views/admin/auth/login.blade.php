<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Fit & Go</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            width: 100%;
            max-width: 420px;
        }
        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo i { font-size: 3rem; color: #6366f1; }
        .logo h1 { font-size: 1.5rem; margin-top: 0.5rem; }
        .logo p { color: #64748b; font-size: 0.9rem; }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-weight: 500; margin-bottom: 0.5rem; }
        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        .btn {
            width: 100%;
            padding: 0.875rem;
            background: #6366f1;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn:hover { background: #4f46e5; }
        .alert {
            padding: 0.875rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            font-size: 0.9rem;
        }
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .back-link a {
            color: #64748b;
            text-decoration: none;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo">
            <i class="fas fa-dumbbell"></i>
            <h1>Fit & Go Admin</h1>
            <p>Masuk ke panel administrator</p>
        </div>

        @if($errors->any())
            <div class="alert">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="admin@fitandgo.com" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn">
                <i class="fas fa-sign-in-alt"></i> Masuk
            </button>
        </form>

        <div class="back-link">
            <a href="{{ route('home') }}"><i class="fas fa-arrow-left"></i> Kembali ke Toko</a>
        </div>
    </div>
</body>
</html>
