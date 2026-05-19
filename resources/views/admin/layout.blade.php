<!DOCTYPE html>
<html>
<head>
@stack('styles')

    <title>Cab Admin Panel</title>
   <!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">

<!-- Custom Admin CSS -->
<link href="{{ asset('css/admin.css') }}" rel="stylesheet">


    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f1f5f9;
        }

.sidebar {
  
    box-sizing: border-box;
    width: 230px;
    height: 100vh;
    background: linear-gradient(180deg, #0f172a, #020617);
    color: #fff;
    position: fixed;
    top: 0;
    left: 0;
    padding: 25px 20px;
    z-index: 1000;
    transition: transform 0.3s ease;
}

.menu-toggle {
    display: none;
  
}

.sidebar-overlay {
    display: none;
}

        .sidebar h2 {
            margin-bottom: 40px;
            font-size: 22px;
            letter-spacing: 1px;
        }

        .sidebar a {
            display: block;
            padding: 12px 15px;
            margin-bottom: 10px;
            color: #cbd5f5;
            text-decoration: none;
            border-radius: 6px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #1e293b;
            color: #fff;
        }


        .content {
             margin-left: 230px;
    padding: 30px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin: 0;
            font-size: 16px;
            color: #475569;
        }

        .card .number {
            margin-top: 10px;
            font-size: 36px;
            font-weight: bold;
            color: #0f172a;
        }

        .footer {
            margin-top: 50px;
            color: #64748b;
            font-size: 14px;
        }
@media (max-width: 768px) {

    body {
        overflow-x: hidden;
    }

    .menu-toggle {
        display: block;
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 2000;
        background: #0f172a;
        color: white;
        border: none;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 22px;
        cursor: pointer;
    }

    .sidebar {
     
        left: 0 !important;
        right: auto !important;
        margin: 0 !important;
        transform: translateX(-120%) !important;
        width: 250px;
        height: 100vh;
        padding-top: 70px;
        transition: transform 0.3s ease;
    }

   .sidebar.active {
        transform: translateX(0) !important;
    }

    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.45);
        z-index: 900;
    }

    .sidebar-overlay.active {
        display: block;
    }

    .content {
        
        margin-left: 0 !important;
        padding: 80px 15px 15px !important;

        width: 100%;
    }
}
    </style>

<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#0f172a">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="Cab Admin">
<link rel="apple-touch-icon" href="/images/icon-192.png">

</head>

<body>
<button class="menu-toggle" onclick="toggleSidebar()">
    ☰
</button>

<div class="sidebar-overlay" onclick="toggleSidebar()"></div>
<div class="sidebar">
    <h2>🚗 Cab Admin</h2>
    <a href="/">Dashboard</a>
    <a href="/admin/companies">Companies</a>
    <a href="/admin/cars">Cars</a>
    <a href="{{ route('admin.bookings.calendar') }}">Bookings</a>
</div>

<div class="content">
    @yield('content')
    <div class="footer">
        © {{ date('Y') }} Cab Booking System
    </div>
</div>

@if(session('success'))
<div class="fixed bottom-5 right-5 bg-green-600 text-white px-4 py-2 rounded shadow">
    {{ session('success') }}
</div>
@endif
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
if ("serviceWorker" in navigator) {
    window.addEventListener("load", function () {
        navigator.serviceWorker.register("/service-worker.js")
            .then(function () {
                console.log("Service Worker Registered");
            });
    });
}
</script>
<script>
function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('active');
    document.querySelector('.sidebar-overlay').classList.toggle('active');
}
</script>

@if(session('success'))



<script>
document.addEventListener("DOMContentLoaded", function () {
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: "{{ session('success') }}",
        confirmButtonColor: '#3085d6'
    });
});
</script>
@endif
@stack('scripts')
@yield('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</body>
</html>
