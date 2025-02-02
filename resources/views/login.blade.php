<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <img src="{{ asset('images/tourisme_pc_website.jpeg') }}" alt="Login Image" class="login-image">
    <form action="{{ route('login') }}" method="POST" class="login-form">
        @csrf
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login">
        <a href="/signup" class="signup-link">You don't have an account?</a>

        @if ($errors->has('login'))
            <span class="error-message">{{ $errors->first('login') }}</span>
        @endif
    </form>
    <script src="{{ asset('js/login.js') }}" defer></script>
</body>
</html>
