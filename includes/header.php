<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clock It</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .app-navbar {
            width: 100%;
            background: #2EB28A;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            border-bottom: 1px solid #e6e9ee;
        }

        .app-navbar-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 36px;
            width: 100%;
            box-sizing: border-box;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo {
            height: 90px; 
            width: 200px; 
            object-fit: cover;
            margin-top: -8px; 
            margin-bottom: -8px; 
            background: #ffffff;
            border-radius: 4px;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .nav-link {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
            padding: 8px 0;
        }

        .nav-link:hover {
            color: #d9f5ec;
        }

        .active {
            border-bottom: 2px solid #ffffff;
        }

        .logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 25px;
            border: none;
            background: white;
            color: #2EB28A;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #249a77;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="app-navbar">
        <div class="app-navbar-inner">
            <div class="nav-left">
                <div class="logo"></div>
            </div>

            <div class="nav-right">
                <a href="#" class="nav-link active">Dashboard</a>
                <a href="#" class="nav-link active">User Guide</a>
                <button class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </button>
            </div>
        </div>
    </nav>
</body>
</html>