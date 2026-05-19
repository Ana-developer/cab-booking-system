<!DOCTYPE html>
<html>
<head>
    <title>Cab Booking Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 30px;
        }

        .main-content {
    margin-left: 260px;
    padding: 20px;
}

.sidebar {
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

        .footer {
            margin-top: 50px;
            color: #64748b;
            font-size: 14px;
        }
        h2 {
            text-align: center;
            margin-bottom: 40px;
            font-weight: 600;
        }

   
        /* Cards */
        .dashboard {
             width: 100%;
    max-width: 1200px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
            transition: 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin: 0;
            font-size: 28px;
        }

        .card p {
            margin-top: 10px;
            color: #64748b;
            font-size: 14px;
        }

        .blue { border-top: 4px solid #3b82f6; }
        .green { border-top: 4px solid #22c55e; }
        .red { border-top: 4px solid #ef4444; }
        .gray { border-top: 4px solid #64748b; }

        /* Section */
        .section {
              width: 100%;
    max-width: 1200px;

            margin: 50px auto 0;
        }

        .section h3 {
            margin-bottom: 20px;
            font-weight: 600;
        }

        /* Table */
      .table-container {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.05);
    overflow-x: auto;
}
tr {
    transition: 0.2s ease;
}
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8fafc;
            text-align: left;
            padding: 15px;
            font-size: 14px;
            color: #334155;
        }

        td {
            padding: 15px;
            border-top: 1px solid #f1f5f9;
            font-size: 14px;
        }

        tr:hover {
            background-color: #f1f5f9;
    transition: 0.2s;
        }

        .status-paid {
            color: #16a34a;
            font-weight: 600;
        }

        .status-pending {
            color: #dc2626;
            font-weight: 600;
        }

        /* Chart Box */
        .chart-box {
            background: #fff;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
        }

 
       @media (max-width: 768px) {

    body {
        padding: 0;
    }

.menu-toggle {
        display: block;
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1100;
        background: #0f172a;
        color: white;
        border: none;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 22px;
        cursor: pointer;
    }
    .sidebar {
        left: -270px;
        width: 250px;
        height: 100%;
    }

    .sidebar.active {
        left: 0;
    }

    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        z-index: 999;
    }

    .sidebar-overlay.active {
        display: block;
    }

        .main-content {
        margin-left: 0;
        width: 100%;
        padding: 15px;
    }

    .dashboard {
        grid-template-columns: 1fr;
    }
    .section {
        width: 100%;
    }

      .sidebar {
        transform: translateX(-100%);
        padding-top: 70px;
    }

     .sidebar.active {
        transform: translateX(0);
    }

    table {
        min-width: 600px;
    }
}

    </style>
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
<div class="main-content">

<h2>🚖 Cab Booking Dashboard</h2>

<div class="dashboard">

    <div class="card blue">
        <h3>{{ $totalBookings }}</h3>
        <p>Total Bookings</p>
    </div>

    <div class="card green">
        <h3>{{ $todayBookings }}</h3>
        <p>Today's Bookings</p>
    </div>

    <div class="card gray">
        <h3>{{ $totalInvoices }}</h3>
        <p>Total Invoices</p>
    </div>

    <div class="card red">
        <h3>₹ {{ number_format($pendingPayments, 2) }}</h3>
        <p>Pending Payments</p>
    </div>

    <div class="card green">
        <h3>₹ {{ number_format($totalRevenue, 2) }}</h3>
        <p>Total Revenue</p>
    </div>

</div>

<!-- Recent Invoices -->
<div class="section">
    <h3>Recent Invoices</h3>

    <div class="table-container">
        <table>



            <tr>
                <th>Invoice No</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>

            @foreach($recentInvoices as $invoice)
<tr onclick="window.location='{{ route('billing.show', $invoice->booking_id) }}'"
    style="cursor:pointer;">
    <td style="color:#2563eb; font-weight:600;">
    {{ $invoice->invoice_number ?? 'N/A' }}
</td>
                
                <td>₹ {{ number_format($invoice->total_amount, 2) }}</td>
                <td class="status-{{ $invoice->status }}">
                    {{ ucfirst($invoice->status) }}
                </td>
                <td>{{ $invoice->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

<!-- Revenue Chart -->
<div class="section">
    <h3>Revenue (Last 7 Days)</h3>

    <div class="chart-box">
        <canvas id="revenueChart"></canvas>
    </div>
</div>
</div>

<script>
function toggleSidebar() {

    document.querySelector('.sidebar')
        .classList.toggle('active');

    document.querySelector('.sidebar-overlay')
        .classList.toggle('active');
}
</script>

<script>
    const revenues = @json($revenues);

    const labels = revenues.map(r => r.date);
    const totals = revenues.map(r => r.total);

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
    label: 'Revenue',
    data: totals,
    borderColor: '#2563eb',
    backgroundColor: 'rgba(37, 99, 235, 0.1)',
    borderWidth: 3,
    fill: true,
    tension: 0.4
}]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>

</body>
</html>