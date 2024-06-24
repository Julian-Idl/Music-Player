<?php
session_start();

// Load users from the file (in a real application, you would load from a database)
$users = json_decode(file_get_contents('users.json'), true);

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    if (isset($users[$username]) && password_verify($password, $users[$username]['password'])) {
        $_SESSION['username'] = $username;
        
        if ($remember) {
            // Set a cookie for 30 days
            setcookie('username', $username, time() + (86400 * 30), "/", "", true, true);
        }
        
        // Redirect to the welcome page
        header("Location: ..\main\index.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
        input[type=text], input[type=password] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type=submit] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type=submit]:hover {
            background-color: #45a049;
        }
        .remember-me {
            margin-top: 10px;
        }
        .link {
            display: block;
            text-align: center;
            margin-top: 10px;
        }
        .toggle-password {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <div class="toggle-password">
                <input type="password" id="password" name="password" required>
                <input type="checkbox" onclick="togglePassword()"> Show Password
            </div>

            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember Me</label>
            </div>

            <input type="submit" value="Login">
        </form>
        <a class="link" href="register.html">Register</a>
    </div>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>
</body>
</html>
