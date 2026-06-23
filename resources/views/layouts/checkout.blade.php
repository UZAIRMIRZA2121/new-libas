<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Checkout - New Libas')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Simplified Header for Checkout -->
    <header class="header checkout-header">
        <div class="container header-main">
            <div class="logo">
                <a href="{{ url('/') }}" style="text-decoration: none;">
                    <h1>New<span>Libas</span></h1>
                </a>
            </div>
            <div class="checkout-secure-badge">
                <i class="fas fa-lock"></i> Secure Checkout
            </div>
        </div>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <!-- Simplified Footer -->
    <footer class="checkout-footer">
        <div class="container">
            <p>&copy; 2026 New Libas. All rights reserved.</p>
            <div class="footer-links">
                <a href="#">Refund policy</a>
                <a href="#">Shipping policy</a>
                <a href="#">Privacy policy</a>
                <a href="#">Terms of service</a>
            </div>
        </div>
    </footer>

</body>
</html>
