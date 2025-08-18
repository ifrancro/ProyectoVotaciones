<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Electoral - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        
        .login-header {
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: #2c3e50;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #7f8c8d;
            font-size: 16px;
            margin: 0;
        }
        
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        .form-label {
            color: #2c3e50;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-control {
            border: 2px solid #ecf0f1;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #2980b9 0%, #1f5f8b 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }
        
        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #ecf0f1;
            border-right: none;
            border-radius: 10px 0 0 10px;
        }
        
        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            padding: 12px 15px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background: #fee;
            color: #c33;
        }
        
        .login-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
        }
        
        .login-footer p {
            color: #7f8c8d;
            font-size: 14px;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1><i class="fas fa-vote-yea"></i> Sistema Electoral</h1>
            <p>Ingresa tus credenciales para acceder</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="username" class="form-label">
                    <i class="fas fa-user"></i> Usuario
                </label>
                <input type="text" 
                       class="form-control @error('username') is-invalid @enderror" 
                       id="username" 
                       name="username" 
                       value="{{ old('username') }}" 
                       required 
                       autofocus>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i> Contraseña
                </label>
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       required>
            </div>

            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
        </form>

        <div class="login-footer">
            <p><strong>Credenciales de prueba:</strong></p>
            <p><small>Admin: admin / admin123</small></p>
            <p><small>Usuario: mesa001 / 123456</small></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
