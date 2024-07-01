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
            background-color: #2b2b30;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
            font-variation-settings:"slnt" 0;
            font-weight: bold;
        }
        .container {
            background-color: #12121673;
            border: 1px;
            color: whitesmoke ;
            border-color: #ccc;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(162, 159, 159, 0.1);
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
        input[type=text],input[type=password] {
            width: 90%;
            padding: 10px;
            margin: 5px 0px 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .checkbox{
            display: block;
            margin: 5px 0;
        }
        input[type=submit] {
            width: 100%;
            font-family: "Inter", sans-serif;
            font-optical-sizing:auto;
            font-style:normal;
            font-variation-settings:"slnt" 0;
            background-color: #2b2b30;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: color 0.3s, transform 0.3s;
        }
        input[type=submit]:hover {
            background-color: #676a68;
            transform: scale(1.01);

        }
        .remember-me {
            margin-top: 10px;
        }
        .link {
            display: block;
            text-align: center;
            margin-top: 10px;
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
            font-variation-settings:"slnt" 0;
            background-color: #2b2b30;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: color 0.3s, transform 0.3s;
            text-decoration: none;
        }
        .link:hover{
            background-color: #676a68;
            transform: scale(1.01);
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
