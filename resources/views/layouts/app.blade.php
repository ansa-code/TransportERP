<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Transport ERP</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, Helvetica, sans-serif;
        }

        body{
            display:flex;
            background:#f4f6f9;
        }

        /* Sidebar */

        .sidebar{
             width:250px;
             height:100vh;
             background:#0d1b2a;
             color:white;
             position:fixed;
             left:0;
             top:0;
             overflow-y:auto;
             overflow-x:hidden;
}
          .sidebar::-webkit-scrollbar{
                       width:6px;
}

         .sidebar::-webkit-scrollbar-thumb{
                       background:#415a77;
                       border-radius:10px;
}

        .sidebar::-webkit-scrollbar-track{
                         background:#0d1b2a;
}

        .logo{
            text-align:center;
            padding:20px;
        }

        .logo img{
            width:160px;
        }

        .sidebar ul{
            list-style:none;
        }

        .sidebar ul li{
            padding:15px 25px;
            border-bottom:1px solid rgba(255,255,255,.08);
            transition:0.2s;
        }

        .sidebar ul li:hover{
            background:#1b263b;
            cursor:pointer;
            padding-left:30px;
        }

        /* Active Sidebar Item */

        .sidebar ul li.active{
            background:#0d6efd;
        }

        .sidebar ul li.active a{
            color:white;
            font-weight:bold;
        }

        .sidebar ul li a{
            color:white;
            text-decoration:none;
            display:block;
        }

        /* Main */

        .main{
            margin-left:250px;
            width:100%;
        }

        /* Navbar */

        .navbar{
            height:70px;
            background:white;
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:0 30px;
            box-shadow:0 3px 10px rgba(0,0,0,.08);
        }

        .navbar h2{
            color:#1d3557;
        }

        .profile{
            font-weight:bold;
        }

        /* Content */

        .content{
            padding:30px;
        }

    </style>

</head>

<body>

<div class="sidebar">

    <div class="logo">

        <img src="{{ asset('images/logo.jpeg') }}">

    </div>

    <ul>

        <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <a href="/dashboard">Dashboard</a>
        </li>

        <li class="{{ request()->is('vehicles*') ? 'active' : '' }}">
            <a href="/vehicles">Fleet</a>
        </li>

        <li class="{{ request()->is('drivers*') ? 'active' : '' }}">
            <a href="/drivers">Drivers</a>
        </li>

        <li class="{{ request()->is('clients*') ? 'active' : '' }}">
            <a href="/clients">Clients</a>
        </li>

        <li class="{{ request()->is('vendors*') ? 'active' : '' }}">
            <a href="/vendors">Vendors</a>
        </li>

        <li class="{{ request()->is('assignments*') ? 'active' : '' }}">
            <a href="/assignments">Assignments</a>
        </li>

        <li class="{{ request()->is('fuels*') ? 'active' : '' }}">
            <a href="/fuels">Fuels</a>
        </li>

        <li class="{{ request()->is('maintenances*') ? 'active' : '' }}">
            <a href="/maintenances">Maintenance</a>
        </li>

        <li class="{{ request()->is('expenses*') ? 'active' : '' }}">
            <a href="/expenses">Expenses</a>
        </li>

        <li class="{{ request()->is('payrolls*') ? 'active' : '' }}">
            <a href="/payrolls">Payroll</a>
        </li>

        <li class="{{ request()->is('invoices*') ? 'active' : '' }}">
            <a href="/invoices">Invoices</a>
        </li>

        <li class="{{ request()->is('reports*') ? 'active' : '' }}">
            <a href="/reports">Reports</a>
        </li>

        
        <li>
            <a href="/">Logout</a>
        </li>

    </ul>

</div>

<div class="main">

    <div class="navbar">

        <h2>Dashboard</h2>

        <div class="profile">
            Admin
        </div>

    </div>

    <div class="content">

        @if(session('success'))

            <div style="
                background:#d4edda;
                color:#155724;
                padding:12px;
                margin-bottom:20px;
                border-radius:6px;
                border:1px solid #c3e6cb;
            ">

                {{ session('success') }}

            </div>

        @endif

        @yield('content')

    </div>

</div>


</body>

</html>
