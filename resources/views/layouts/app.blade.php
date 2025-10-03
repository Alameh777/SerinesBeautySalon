<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beauty Salon</title>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">



    <style>
        :root {
            --pink-50: #fff5f8;
            --pink-100: #ffe8f0;
            --pink-200: #ffcee0;
            --pink-300: #ffa2c2;
            --pink-400: #ff73a5;
            --pink-500: #ff4f90;
            --pink-600: #e63c7d;
            --pink-700: #c22e69;
            --gray-25: #fcfcfd;
            --gray-50: #fafafa;
            --gray-100: #f4f4f5;
            --gray-200: #e7e7ea;
            --gray-300: #d6d6db;
            --gray-500: #71717a;
            --gray-700: #3f3f46;
            --blue-500: #3b82f6;
        }

        * { box-sizing: border-box; }
        body { font-family: 'Poppins', Arial, sans-serif; background-color: var(--gray-50); color: #222; margin: 0; padding: 0; }

        /* Topbar */
        .topbar { position: sticky; top: 0; z-index: 50; background: linear-gradient(90deg, var(--pink-500), var(--pink-400)); color: white; padding: 14px 24px; box-shadow: 0 2px 10px rgba(255,79,144,0.25); }
        .brand { display: inline-flex; align-items: center; gap: 10px; font-weight: 600; letter-spacing: .3px; }
        .brand-badge { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; background: rgba(255,255,255,0.2); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.25); }
        .nav { display: inline-flex; gap: 16px; margin-left: 28px; }
        .nav a { color: #fff; text-decoration: none; font-weight: 500; padding: 8px 12px; border-radius: 8px; transition: background-color .2s ease, box-shadow .2s ease; }
        .nav a:hover { background: rgba(255,255,255,0.18); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.22); }

        /* Active nav link */
        .nav a.active {
            background: rgba(255,255,255,0.35);
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.4);
        }

        /* Container */
        .container { width: min(1200px, 92%); margin: 24px auto 40px auto; }
        .container.fullheight {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 70px); /* Adjust for topbar height */
            gap: 20px;
        }

        /* Cards, buttons, tables, forms – existing styles */
        .card { background: #fff; border-radius: 14px; padding: 20px; box-shadow: 0 6px 24px rgba(0,0,0,0.06); }
        .subtle-card { background: #fff; border-radius: 14px; padding: 16px; box-shadow: 0 4px 18px rgba(0,0,0,0.05); border: 1px solid var(--gray-200); }
        .btn { display: inline-block; padding: 12px 20px; background: var(--pink-500); color: #fff; border-radius: 8px; text-decoration: none; border: none; cursor: pointer; font-weight: 500; font-size: 14px; box-shadow: 0 2px 8px rgba(255,79,144,0.25); transition: all 0.2s ease; }
        .btn:hover { background: var(--pink-600); box-shadow: 0 4px 12px rgba(255,79,144,0.35); transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
        .btn-sm { padding: 8px 16px; font-size: 13px; }
        .btn-danger { background: #dc3545; }
        .btn-success { background: #28a745; }
        .btn-primary { background: #007bff; }
        .btn-outline { background: transparent; color: var(--pink-500); border: 2px solid var(--pink-500); }
        .pill { display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:20px; background: var(--gray-100); color:#222; font-size:13px; cursor:pointer; border:1px solid var(--gray-200); }
        .pill.active { background: var(--blue-500); color:#fff; border-color: var(--blue-500); }

        table { width: 100%; border-collapse: collapse; background-color: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); margin-bottom: 20px; }
        th, td { padding: 16px 20px; border-bottom: 1px solid var(--gray-200); text-align: left; vertical-align: middle; }
        th { background: linear-gradient(135deg, var(--pink-50), #fff); color: var(--gray-700); font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; }
        tr:hover { background-color: var(--gray-50); }
        tr:last-child td { border-bottom: 0; }
        
        input[type="text"], input[type="datetime-local"], input[type="number"], input[type="email"], textarea, select { width: 100%; padding: 12px 16px; border: 2px solid var(--gray-200); border-radius: 8px; outline: none; background: #fff; font-size: 14px; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        input:focus, textarea:focus, select:focus { border-color: var(--pink-400); box-shadow: 0 0 0 3px rgba(255,115,165,0.15), 0 2px 8px rgba(0,0,0,0.1); transform: translateY(-1px); }
        .badge-black {
    color: #000 !important;
    background-color: #d1ecf1; /* optional: keep badge background if needed */
}

    </style>
    
    @stack('styles')
</head>

<body>
    <div class="topbar">
        <div class="brand">
            <span class="brand-badge">   <img src="{{ asset('images/violetlogo.png') }}" alt="Brand Logo" style="width:20px; height:20px;"></span>
            <span>Serine's Salon</span>
        </div>
        <div class="nav">
            <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('clients.index') }}" class="{{ request()->routeIs('clients.*') ? 'active' : '' }}">Clients</a>
            <a href="{{ route('employees.index') }}" class="{{ request()->routeIs('employees.*') ? 'active' : '' }}">Employees</a>
            <a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.*') ? 'active' : '' }}">Services</a>
            <a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.index') ? 'active' : '' }}">Bookings</a>
            <a href="{{ route('bookings.schedule') }}" class="{{ request()->routeIs('bookings.schedule') ? 'active' : '' }}">Schedule</a>
        </div>
    </div>

    <div class="container @yield('fullheight', '')">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
