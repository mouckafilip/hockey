<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f4f7f6;
            color: #333;
            line-height: 1.6;
        }

        .navbar {
            background-color: #1a252f;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar .logo a {
            color: #fff;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .navbar .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .navbar .nav-links a {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .navbar .nav-links a:hover {
            color: #3498db;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .navbar .nav-links {
                flex-direction: column;
                gap: 10px;
                width: 100%;
            }

            .navbar .nav-links li {
                padding: 5px 0;
            }
        }

        .container {
            max-width: 1100px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .main-content {
            background: white;
            padding: 3rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            min-height: 400px;
        }

        footer {
            text-align: center;
            padding: 1.5rem;
            background-color: #1a252f;
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-top: 4rem;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">
            <a href="#"></a>
        </div>
        <ul class="nav-links">
            <li><a href="#"></a></li>
            <li><a href="#"></a></li>
            <li><a href="#"></a></li>
            <li><a href="#"></a></li>
            <li><a href="#"></a></li>
        </ul>
    </nav>

    <div class="container">
        <main class="main-content">
            
        </main>
    </div>

    <footer>
        <p></p>
    </footer>

</body>
</html>