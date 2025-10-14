<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

     

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js for data visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=almarai:400,500,700&display=swap" rel="stylesheet"/>

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-light: #3b82f6;
            --primary-dark: #1d4ed8;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --background-color: #f8fafc;
            --surface-color: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --border-radius: 12px;
            --border-radius-sm: 8px;
        }

        * {
            transition: all 0.2s ease-in-out;
        }

        body { 
            font-family: 'Almarai', sans-serif; 
            background-color: var(--background-color);
            color: var(--text-primary);
            font-weight: 400;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            height: 100vh;
            background: var(--surface-color);
            position: fixed;
            top: 0;
            right: -280px;
            transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-lg);
            z-index: 1050;
            overflow-y: auto;
            border-left: 1px solid var(--border-color);
        }

        .sidebar.show {
            right: 0;
        }

        .sidebar-header {
            padding: 2rem 1.5rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            position: relative;
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            text-align: center;
        }

        .sidebar-close {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: var(--border-radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            backdrop-filter: blur(10px);
        }

        .sidebar-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        .sidebar-nav {
            padding: 1.5rem 0;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            padding: 0 1.5rem 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: var(--border-radius-sm);
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .nav-link:hover {
            background: var(--background-color);
            color: var(--primary-color);
            transform: translateX(-2px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            box-shadow: var(--shadow-md);
        }

        .nav-link i {
            width: 20px;
            margin-left: 0.75rem;
            font-size: 1.1rem;
        }

        .nav-submenu {
            margin-right: 1rem;
            margin-top: 0.5rem;
            border-right: 2px solid var(--border-color);
            padding-right: 1rem;
        }

        .nav-submenu .nav-link {
            padding: 0.625rem 1rem;
            font-size: 0.9rem;
            font-weight: 400;
        }

        /* Main Content Styles */
        .content {
            transition: margin-right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
        }

        .content.shifted {
            margin-right: 280px;
        }

        /* Top Navbar Styles */
        .top-navbar {
            background: var(--surface-color);
            box-shadow: var(--shadow-sm);
            padding: 1rem 2rem;
            display: flex;
            justify-content: between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .menu-toggle {
            background: var(--background-color);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            width: 44px;
            height: 44px;
            border-radius: var(--border-radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .menu-toggle:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: scale(1.05);
        }

        .search-container {
            position: relative;
            width: 300px;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            background: var(--background-color);
            font-size: 0.9rem;
            outline: none;
        }

        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .search-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        .navbar-center h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-btn {
            position: relative;
            background: var(--background-color);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            width: 44px;
            height: 44px;
            border-radius: var(--border-radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .notification-btn:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            left: -5px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: var(--background-color);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            cursor: pointer;
        }

        .user-profile:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .logout-btn {
            background: var(--danger-color);
            border: 1px solid var(--danger-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius-sm);
            font-weight: 500;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: #dc2626;
            border-color: #dc2626;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        /* Dashboard Content Styles */
        .dashboard-content {
            padding: 2rem;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 4px;
            height: 100%;
            background: var(--primary-color);
        }

        .stat-card.success::before { background: var(--success-color); }
        .stat-card.warning::before { background: var(--warning-color); }
        .stat-card.danger::before { background: var(--danger-color); }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-title {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--border-radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .stat-icon.primary { background: rgba(37, 99, 235, 0.1); color: var(--primary-color); }
        .stat-icon.success { background: rgba(16, 185, 129, 0.1); color: var(--success-color); }
        .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: var(--warning-color); }
        .stat-icon.danger { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .stat-change {
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .stat-change.positive { color: var(--success-color); }
        .stat-change.negative { color: var(--danger-color); }

        .chart-container {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .chart-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                right: -100%;
            }

            .content.shifted {
                margin-right: 0;
            }

            .search-container {
                display: none;
            }

            .navbar-center h1 {
                font-size: 1.25rem;
            }

            .dashboard-content {
                padding: 1rem;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 9999;
        }

        .toast {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-sm);
            padding: 1rem;
            margin-bottom: 0.5rem;
            box-shadow: var(--shadow-lg);
            min-width: 300px;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        :root {
      /* Primary Colors */
      --argon-primary: #5e72e4;
      --argon-secondary: #8392ab;
      --argon-success: #2dce89;
      --argon-info: #11cdef;
      --argon-warning: #fb6340;
      --argon-danger: #f5365c;
      
      /* Gradient Colors */
      --argon-gradient-primary: linear-gradient(310deg, #7928CA 0%, #FF0080 100%);
      --argon-gradient-success: linear-gradient(310deg, #17ad37 0%, #98ec2d 100%);
      --argon-gradient-info: linear-gradient(310deg, #2152ff 0%, #21d4fd 100%);
      --argon-gradient-warning: linear-gradient(310deg, #f53939 0%, #fbcf33 100%);
      
      /* Background Colors */
      --argon-bg-light: #f8f9fe;
      --argon-bg-white: #ffffff;
      --argon-bg-gray: #f7fafc;
      
      /* Text Colors */
      --argon-text-dark: #344767;
      --argon-text-muted: #67748e;
      --argon-text-light: #8392ab;
      
      /* Border Colors */
      --argon-border-light: rgba(0, 0, 0, 0.05);
      --argon-border-color: #dee2e6;
      
      /* Shadow */
      --argon-shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      --argon-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
      --argon-shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
      
      /* Border Radius */
      --argon-border-radius: 0.75rem;
      --argon-border-radius-sm: 0.5rem;
      --argon-border-radius-lg: 1rem;
    }

    /* Import Google Fonts - Poppins (similar to Argon Dashboard) */
    /* @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap'); */

    /* Global Styles */
    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--argon-bg-gray);
      color: var(--argon-text-dark);
      line-height: 1.6;
    }

    /* Container Improvements */
    .container {
      max-width: 1200px;
      margin: 0 auto;
    }

    /* Header Styles */
    h1, h2, h3, h4, h5, h6 {
      font-weight: 600;
      color: var(--argon-text-dark);
      margin-bottom: 1rem;
    }

    .text-xl {
      font-size: 1.5rem;
      font-weight: 600;
    }

    /* Card Styles - Argon Dashboard Style */
    .card {
      background-color: var(--argon-bg-white);
      border: none;
      border-radius: var(--argon-border-radius);
      box-shadow: var(--argon-shadow);
      margin-bottom: 2rem;
      transition: all 0.3s ease-in-out;
    }

    .card:hover {
      transform: translateY(-2px);
      box-shadow: var(--argon-shadow-lg);
    }

    .card-header {
      background-color: var(--argon-bg-light);
      border-bottom: 1px solid var(--argon-border-light);
      padding: 1.5rem 2rem;
      border-top-left-radius: var(--argon-border-radius);
      border-top-right-radius: var(--argon-border-radius);
      font-weight: 600;
      color: var(--argon-text-dark);
      font-size: 1.1rem;
    }

    .card-body {
      padding: 2rem;
    }

    /* Form Styles */
    .form-control {
      border: 1px solid var(--argon-border-color);
      border-radius: var(--argon-border-radius-sm);
      padding: 0.75rem 1rem;
      font-size: 0.875rem;
      transition: all 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
      background-color: var(--argon-bg-white);
      color: var(--argon-text-dark);
    }

    .form-control:focus {
      border-color: var(--argon-primary);
      box-shadow: 0 0 0 0.2rem rgba(94, 114, 228, 0.25);
      outline: none;
    }

    .form-control::placeholder {
      color: var(--argon-text-light);
    }

    /* Label Styles */
    label {
      font-weight: 500;
      color: var(--argon-text-dark);
      margin-bottom: 0.5rem;
      display: block;
    }

    /* Button Styles - Argon Dashboard Style */
    .btn {
      border-radius: var(--argon-border-radius-sm);
      padding: 0.75rem 1.5rem;
      font-weight: 600;
      font-size: 0.875rem;
      border: none;
      transition: all 0.15s ease-in-out;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
    }

    .btn-primary {
      background: var(--argon-gradient-primary);
      color: white;
      box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .btn-primary:hover {
      transform: translateY(-1px);
      box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
      color: white;
    }

    .btn-success {
      background: var(--argon-gradient-success);
      color: white;
      box-shadow: 0 4px 6px rgba(45, 206, 137, 0.2);
    }

    .btn-info {
      background: var(--argon-gradient-info);
      color: white;
      box-shadow: 0 4px 6px rgba(17, 205, 239, 0.2);
    }

    .btn-warning {
      background: var(--argon-gradient-warning);
      color: white;
      box-shadow: 0 4px 6px rgba(251, 99, 64, 0.2);
    }

    /* Table Styles */
    .table {
      background-color: var(--argon-bg-white);
      border-radius: var(--argon-border-radius-sm);
      overflow: hidden;
      box-shadow: var(--argon-shadow-sm);
    }

    .table thead th {
      background-color: var(--argon-bg-light);
      border-bottom: 1px solid var(--argon-border-light);
      padding: 1rem 1.5rem;
      font-weight: 600;
      color: var(--argon-text-dark);
      font-size: 0.875rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .table tbody td {
      padding: 1rem 1.5rem;
      border-bottom: 1px solid var(--argon-border-light);
      color: var(--argon-text-dark);
      font-size: 0.875rem;
    }

    .table tbody tr:hover {
      background-color: var(--argon-bg-light);
    }

    .table tbody tr:last-child td {
      border-bottom: none;
    }

    /* Table Responsive */
    .table-responsive {
      border-radius: var(--argon-border-radius-sm);
      box-shadow: var(--argon-shadow-sm);
    }

    /* Form Row Improvements */
    .row.g-3 {
      margin-bottom: 1.5rem;
    }

    .col-md-3, .col-md-2 {
      margin-bottom: 1rem;
    }

    /* Filter Section Specific Styles */
    .filter-section {
      background: linear-gradient(135deg, var(--argon-bg-light) 0%, var(--argon-bg-white) 100%);
    }

    .filter-section .card-header {
      background: var(--argon-gradient-primary);
      color: white;
      text-align: center;
      font-size: 1.2rem;
    }

    /* Status Indicators */
    .status-present {
      color: var(--argon-success);
      font-weight: 600;
    }

    .status-absent {
      color: var(--argon-danger);
      font-weight: 600;
    }

    .status-pending {
      color: var(--argon-warning);
      font-weight: 600;
    }

    /* Icon Styles */
    .icon {
      width: 1.2rem;
      height: 1.2rem;
      margin-right: 0.5rem;
      vertical-align: middle;
    }

    /* Pagination Styles */
    .pagination {
      justify-content: center;
      margin-top: 2rem;
    }

    .page-link {
      color: var(--argon-primary);
      border: 1px solid var(--argon-border-color);
      border-radius: var(--argon-border-radius-sm);
      padding: 0.5rem 0.75rem;
      margin: 0 0.25rem;
      transition: all 0.2s ease-in-out;
    }

    .page-link:hover {
      background-color: var(--argon-primary);
      color: white;
      border-color: var(--argon-primary);
    }

    .page-item.active .page-link {
      background-color: var(--argon-primary);
      border-color: var(--argon-primary);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .card-body {
        padding: 1.5rem;
      }
      
      .card-header {
        padding: 1rem 1.5rem;
      }
      
      .table thead th,
      .table tbody td {
        padding: 0.75rem 1rem;
        font-size: 0.8rem;
      }
      
      .btn {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
      }
      
      .container {
        padding: 0 1rem;
      }
    }

    /* Animation Classes */
    .fade-in {
      animation: fadeIn 0.5s ease-in-out;
    }

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

    /* Loading States */
    .loading {
      opacity: 0.7;
      pointer-events: none;
    }

    .loading::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 20px;
      height: 20px;
      margin: -10px 0 0 -10px;
      border: 2px solid var(--argon-primary);
      border-radius: 50%;
      border-top-color: transparent;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    /* Custom Utility Classes */
    .text-argon-primary {
      color: var(--argon-primary) !important;
    }

    .text-argon-success {
      color: var(--argon-success) !important;
    }

    .text-argon-warning {
      color: var(--argon-warning) !important;
    }

    .text-argon-danger {
      color: var(--argon-danger) !important;
    }

    .bg-argon-gradient-primary {
      background: var(--argon-gradient-primary) !important;
    }

    .bg-argon-gradient-success {
      background: var(--argon-gradient-success) !important;
    }

    .shadow-argon {
      box-shadow: var(--argon-shadow) !important;
    }

    .shadow-argon-lg {
      box-shadow: var(--argon-shadow-lg) !important;
    }

    .rounded-argon {
      border-radius: var(--argon-border-radius) !important;
    }

    .rounded-argon-sm {
      border-radius: var(--argon-border-radius-sm) !important;
    }
  </style>

  @livewireStyles 
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم</h2>
            <button id="close-btn" class="sidebar-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        
          <div class="sidebar-nav">
    <!-- قسم المبيعات الجاهزة -->
                <div class="nav-section">
                    <div class="nav-section-title">المبيعات الجاهزة</div>
                    <div class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#sales-menu" role="button" aria-expanded="false">
                            <i class="fas fa-shopping-cart"></i>
                            إدارة المبيعات
                            <i class="fas fa-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse nav-submenu" id="sales-menu">
                            <a href="{{ route('ready_sales.index') }}" class="nav-link">
                                <i class="fas fa-cash-register"></i>
                                المبيعات
                            </a>
                            <a href="{{ route('returns.index') }}" class="nav-link">
                                <i class="fas fa-undo"></i>
                                الإرجاع
                            </a>
                            <a href="{{ route('exchanges.index') }}" class="nav-link">
                                <i class="fas fa-exchange-alt"></i>
                                الاستبدال
                            </a>
                        </div>
                    </div>
                </div>
            </div>

          <div class="nav-section">
            <div class="nav-section-title">إدارة الطلبات</div>
            <div class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#orders-menu" role="button" aria-expanded="false">
                    <i class="fas fa-clipboard-list"></i>
                    إدارة الطلبات
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse nav-submenu" id="orders-menu">
                    <a href="{{ url('order-wizard/step-1') }}" class="nav-link">
                        <i class="fas fa-plus-circle"></i>
                        إنشاء طلب جديد
                    </a>
                    <a href="{{ url('custom-orders') }}" class="nav-link">
                        <i class="fas fa-list"></i>
                        عرض قائمة الطلبات
                    </a>
                    <a href="{{ url('delivered-orders') }}" class="nav-link">
                        <i class="fas fa-check-circle"></i>
                        الطلبات التي تم تسليمها
                    </a>
                    <a href="{{ url('customers') }}" class="nav-link">
                        <i class="fas fa-users"></i>
                        ادارة العملاء و المقاســات
                    </a>
                    <a href="{{ url('tracking') }}" class="nav-link">
                        <i class="fas fa-spinner"></i>
                        متابعة الطلبات
                    </a>
                    
                     <a href="{{ route('reports.employee-orders') }}" class="nav-link">
                        <i class="fas fa-file-alt"></i>
                        تقرير الطلبات حسب الموظف
                    </a>
                     <a href="{{ route('custom-orders.delivery.today') }}" class="nav-link">
                            <i class="fas fa-calendar-day"></i>
                            طلبات اليوم (جاري التنفيذ)
                        </a>
                        <a href="{{ route('custom-orders.delivery.tomorrow') }}" class="nav-link">
                            <i class="fas fa-calendar-plus"></i>
                            طلبات الغد (جاري التنفيذ)
                        </a>
                </div>
            </div>
        </div>
          <div class="nav-section">
            <div class="nav-section-title">إدارة المشغل</div>
            <div class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#workshop-menu" role="button" aria-expanded="false">
                    <i class="fas fa-industry"></i>
                    إدارة المشغل
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse nav-submenu" id="workshop-menu">
                    <a href="{{ route('workshop.board') }}" class="nav-link">
                        <i class="fas fa-tasks"></i>
                        إدارة الطلبات  
                    </a>
                    <a href="{{ route('tailors.index') }}" class="nav-link">
                        <i class="fas fa-user-cog"></i>
                        إدارة العمال   
                    </a>
                    <a href="{{ route('tailor_accounting.index') }}" class="nav-link">
                        <i class="fas fa-calculator"></i>
                        إدارة الحسابات 
                    </a>
                    <a href="{{ route('quality_reports.index') }}" class="nav-link">
                        <i class="fas fa-check-double"></i>
                        إدارة الجودة
                    </a>
                    <a href="{{ route('tailor_advances.index') }}" class="nav-link">
                        <i class="fas fa-hand-holding-usd"></i>
                        إدارة السلفيات
                    </a>
                    <a href="{{ route('tailor_payments.index') }}" class="nav-link">
                        <i class="fas fa-money-check-alt"></i>
                        إدارة الدفعات
                    </a>
                    <a href="{{ route('piece_rate_definitions.index') }}" class="nav-link">
                        <i class="fas fa-tags"></i>
                        أسعار الإنتاج
                    </a>
                    <a href="{{ route('tailor_production_logs.index') }}" class="nav-link">
                        <i class="fas fa-cogs"></i>
                        إدارة الإنتاج
                    </a>
                </div>
            </div>
        </div>

        <div class="sidebar-nav">
            <!-- قسم المحاسبة والماليات -->
            <div class="nav-section">
                <div class="nav-section-title">المحاسبة والماليات</div>
                <div class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#finance-menu" role="button" aria-expanded="false">
                        <i class="fas fa-coins"></i>
                        إدارة الماليات
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse nav-submenu" id="finance-menu">
                        
                        <a href="{{ route('expenses.index') }}" class="nav-link">
                            <i class="fas fa-wallet"></i>
                            المنصرفات
                        </a>

                        <a href="{{ route('admin.deductions.index') }}" class="nav-link">
                            <i class="fas fa-minus-circle"></i>
                            الخصومات
                        </a>

                        <a href="{{ route('admin.rewards.index') }}" class="nav-link">
                            <i class="fas fa-gift"></i>
                            الحوافز
                        </a>

                        <a href="{{ route('journal_entries.index') }}" class="nav-link">
                            <i class="fas fa-book"></i>
                            دفتر اليومية
                        </a>

                        <a href="{{ route('admin.salaries.index') }}" class="nav-link">
                            <i class="fas fa-money-bill-wave"></i>
                            المرتبات
                        </a>

                        <a href="{{ route('debts.index') }}" class="nav-link">
                            <i class="fas fa-hand-holding-usd"></i>
                            المديونية
                        </a>
                        <a href="{{ route('ledger.index') }}" class="nav-link">
                            <i class="fas fa-book"></i>
                            دفتر الاستاذ
                        </a>
                        <a href="{{ route('trial_balance.index') }}" class="nav-link">
                            <i class="fas fa-book"></i>
                            ميزان المراجعة
                        </a>
                        <a href="{{ route('income_statement.index') }}" class="nav-link">
                            <i class="fas fa-book"></i>
                            قائمة الدخل 
                        </a>
                        <a href="{{ route('balance_sheet.index') }}" class="nav-link">
                            <i class="fas fa-book"></i>
                             الميزانية العمومية 
                        </a>

                    </div>
                </div>
            </div>
        </div>

        <div class="sidebar-nav">
            <!-- قسم الموارد البشرية -->
            <div class="nav-section">
                <div class="nav-section-title">الموارد البشرية</div>
                <div class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#hr-menu" role="button" aria-expanded="false">
                        <i class="fas fa-users"></i>
                        إدارة الموارد البشرية
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse nav-submenu" id="hr-menu">
                        <a href="{{ route('branches.index') }}" class="nav-link">
                            <i class="fas fa-building"></i>
                            الفروع
                        </a>
                        <a href="{{ route('employees.index') }}" class="nav-link">
                            <i class="fas fa-user-tie"></i>
                            الموظفين
                        </a>
                        <a href="{{ route('admin.attendance.index') }}" class="nav-link">
                            <i class="fas fa-clock"></i>
                            الحضور والانصراف
                        </a>
                        <a href="{{ route('users.assign') }}" class="nav-link">
                            <i class="fas fa-link"></i>
                            ربط المستخدمين بالموظفين
                        </a>
                        <a href="{{ route('admin.activity_logs.index') }}" class="nav-link">
                            <i class="fas fa-history"></i>
                            سجل النشاطات
                        </a>
                    </div>
                </div>
            </div>

            <div class="nav-section">
              <div class="nav-section-title">المشتريات والمخزون</div>
              <div class="nav-item">
                  <a class="nav-link" data-bs-toggle="collapse" href="#inventory-menu" role="button" aria-expanded="false">
                      <i class="fas fa-warehouse"></i>
                      إدارة المخزون والمشتريات
                      <i class="fas fa-chevron-down ms-auto"></i>
                  </a>
                  <div class="collapse nav-submenu" id="inventory-menu">
                      <a href="{{ route('admin.products.index') }}" class="nav-link">
                          <i class="fas fa-box"></i>
                          المنتجات
                      </a>
                      <a href="{{ route('admin.product-categories.index') }}" class="nav-link">
                          <i class="fas fa-tags"></i>
                          تصنيفات المنتجات
                      </a>
                      <a href="{{ route('admin.purchases.index') }}" class="nav-link">
                          <i class="fas fa-file-invoice"></i>
                          فواتير الشراء
                      </a>
                      <a href="{{ route('admin.purchase_returns.index') }}" class="nav-link">
                          <i class="fas fa-undo-alt"></i>
                          إرجاع المشتريات
                      </a>
                       <a href="{{ route('admin.branch_stocks.index') }}" class="nav-link">
                            <i class="fas fa-boxes"></i>
                            عرض المخزون في الفروع
                        </a>
                         <a href="{{ route('admin.production_orders.index') }}" class="nav-link">
                          <i class="fas fa-box"></i>
                          انتاج المشغل للجاهز
                      </a>
                      <a href="{{ route('admin.distributions.index') }}" class="nav-link">
                          <i class="fas fa-share-alt"></i>
                          توزيع المنتجات على الفروع
                      </a>
                      <a href="{{ route('admin.stock_transfers.index') }}" class="nav-link">
                          <i class="fas fa-exchange-alt"></i>
                          تحويل المخزون بين الفروع
                      </a>
                      <a href="{{ route('admin.suppliers.index') }}" class="nav-link">
                          <i class="fas fa-truck"></i>
                          الموردين
                      </a>
                      <a href="{{ route('admin.offers.index') }}" class="nav-link">
                          <i class="fas fa-offer"></i>
                          العروض
                      </a>
                  </div>
              </div>
          </div>





        </div>
    </nav>

    <!-- Main Content -->
    <main class="content flex-fill w-100">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="navbar-left">
                <button id="menu-btn" class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div class="navbar-center">
                <h1>لوحة التحكم الرئيسية</h1>
            </div>

            <div class="navbar-right">
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>

                <div class="user-profile">
                    <div class="user-avatar">
                        {{ auth()->user()->name[0] ?? '؟' }}
                    </div>
                    <span>{{ auth()->user()->name ?? 'مستخدم' }}</span>
                    <i class="fas fa-chevron-down"></i>
                </div>


                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </nav>

       
            <!-- Slot for additional content -->
            <div id="additional-content">
                {{ $slot }}
                
            </div>
        </div>
    </main>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<!-- Scripts -->
<script>
    // Sidebar Toggle
    document.getElementById('menu-btn').addEventListener('click', function () {
        document.getElementById('sidebar').classList.add('show');
        document.querySelector('.content').classList.add('shifted');
    });

    document.getElementById('close-btn').addEventListener('click', function () {
        document.getElementById('sidebar').classList.remove('show');
        document.querySelector('.content').classList.remove('shifted');
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const menuBtn = document.getElementById('menu-btn');
        
        if (window.innerWidth <= 768 && 
            !sidebar.contains(event.target) && 
            !menuBtn.contains(event.target) && 
            sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
            document.querySelector('.content').classList.remove('shifted');
        }
    });

    
    // Toast Notification Function
    function showToast(message, type = 'info') {
        const toastContainer = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle me-2"></i>
                <span>${message}</span>
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        toastContainer.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    // Active navigation highlighting
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function() {
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>
  @livewireScripts
</body>
</html>

