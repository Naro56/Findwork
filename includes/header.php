<?php
// If session hasn't been started yet, start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection if not already included
if (!function_exists('isLoggedIn')) {
    require_once __DIR__ . '/db.php';
    
    // Function to check if user is logged in
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    // Function to check if user is a recruiter
    function isRecruiter() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'recruiter';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindWork | Job Portal</title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header styles */
        .main-header {
            background-color: #2563eb;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo a {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ffffff;
            text-decoration: none;
        }

        .nav-links a {
            color: #ffffff;
            text-decoration: none;
            margin-left: 1.5rem;
            font-weight: 500;
        }

        /* Job card styles */
        .job-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            padding: 2rem 0;
        }

        .job-card {
            background: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .job-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        /* Button styles */
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background-color: #2563eb;
            color: #fff;
        }

        .btn-secondary {
            background-color: #e5e7eb;
            color: #4b5563;
        }

        /* Form styles */
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        /* Alert styles */
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .alert-info {
            background-color: #eff6ff;
            border: 1px solid #dbeafe;
            color: #1e40af;
        }

        /* Dashboard styles */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                padding: 1rem;
            }

            .nav-links {
                margin-top: 1rem;
            }

            .job-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="main-header">
        <nav class="nav-container">
            <div class="logo">
                <a href="/job-portal/index.php">FindWork</a>
            </div>
            <div class="nav-links">
                <a href="/job-portal/index.php">Home</a>
                
                <?php if (isLoggedIn()): ?>
                    <?php if (isRecruiter()): ?>
                        <a href="/job-portal/recruiter/dashboard.php">Dashboard</a>
                        <a href="/job-portal/post-job.php">Post Job</a>
                    <?php else: ?>
                        <a href="/job-portal/dashboard.php">My Applications</a>
                    <?php endif; ?>
                    <a href="/job-portal/logout.php">Logout</a>
                <?php else: ?>
                    <a href="/job-portal/login.php">Login</a>
                    <a href="/job-portal/signup.php">Sign Up</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <main class="container">
