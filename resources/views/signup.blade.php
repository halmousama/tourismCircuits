<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="{{ asset('css/signup.css') }}">
</head>
<body>
    <div class="signup-container">
    <img src="{{ asset('images/tourisme_pc_website.jpeg') }}" alt="Sign Up Image" class="signup-image">
    <form action="/signup" method="POST">
        @csrf
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        @if ($errors->has('username'))
            <span class="error-message">{{ $errors->first('username') }}</span>
        @endif
        <br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        @if ($errors->has('email'))
            <span class="error-message">{{ $errors->first('email') }}</span>
        @endif
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <input type="submit" value="Sign Up">
        <a href="/login" class="signup-link">Already have an account?</a>
    </form>
    </div>
    <script src="{{ asset('js/signup.js') }}" defer></script>
</body>
</html>
