<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Electoral - @yield('title', 'Inicio')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            background-color: #2c3e50;
            color: white;
            height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px 0;
            overflow-y: auto;
        }
        
        .main-content {
            margin-left: 250px;
            background-color: #f8f9fa;
            min-height: 100vh;
            padding: 20px;
        }
        
        .sidebar-header {
            padding: 0 20px 20px 20px;
            border-bottom: 1px solid #34495e;
            margin-bottom: 20px;
        }
        
        .sidebar-nav {
            padding: 0 20px;
        }
        
        .nav-item {
            margin-bottom: 10px;
        }
        
        .nav-link {
            color: #ecf0f1;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: #34495e;
            color: white;
        }
        
        .nav-link i {
            margin-right: 10px;
            width: 20px;
        }
        
        .logout-link {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
        }
        
        .candidate-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .candidate-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.2);
        }
        
        .candidate-card.dark {
            background: linear-gradient(135deg, #3BA3BC 0%, #2c3e50 100%);
            color: white;
        }
        
        .candidate-card.medium {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
        }
        
        .candidate-card h5 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0;
        }
        
        .candidate-card h6 {
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        
        .candidate-card h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0;
        }
        
        .vote-input {
            background: white;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            font-size: 18px;
            text-align: center;
            width: 100%;
            margin-top: 10px;
        }
        
        .vote-input:focus {
            border-color: #3498db;
            outline: none;
        }
        
        .save-btn {
            background-color: #2c3e50;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 16px;
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }
        
        .save-btn:hover {
            background-color: #34495e;
            color: white;
        }
        
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .donut-chart {
            width: 200px;
            height: 200px;
            margin: 0 auto;
        }
        
        .legend {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
        }
        
        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            @auth
                @if(auth()->user()->isAdmin())
                    <h5>Administrador</h5>
                @else
                    <h5>Mesa {{ auth()->user()->mesa_number }}</h5>
                @endif
            @endauth
        </div>
        
        <div class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    Resultado
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('actas.create') }}" class="nav-link {{ request()->routeIs('actas.create') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    Agregar boleta
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('actas.search') }}" class="nav-link {{ request()->routeIs('actas.search*') ? 'active' : '' }}">
                    <i class="fas fa-search"></i>
                    Buscar Actas
                </a>
            </div>
        </div>
        
        <div class="logout-link">
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="nav-link" style="background: none; border: none; width: 100%; text-align: left;">
                    <i class="fas fa-sign-out-alt"></i>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
