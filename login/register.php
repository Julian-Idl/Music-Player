<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate password strength
    if (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[^A-Za-z0-9]/', $password)) {
        $error = "Password must contain letters, numbers, and special characters.";
    } else {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Load existing users
        $users = json_decode(file_get_contents('users.json'), true);

        // Check if the username already exists
        if (isset($users[$username])) {
            $error = "Username already exists. Please choose a different username.";
        } else {
            // Store the new user with basic preference data
            $users[$username] = [
                'password' => $hashed_password,
                'preferences' => [] // Example field for storing user preferences
            ];
            file_put_contents('users.json', json_encode($users));
            
            // Create a session for the user
            $_SESSION['username'] = $username;
            
            // Redirect to the welcome page after successful registration
            header("Location: ..\main\index.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        input[type=text], input[type=password] {
            width: 90%;
            padding: 10px;
            margin: 5px 0 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type=submit] {
            width: 100%;
            font-family: "Inter", sans-serif;
            font-optical-sizing:auto;
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
        <h2>Register</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="register.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <div class="toggle-password">
                <input type="password" id="password" name="password" required>
                <input type="checkbox" onclick="togglePassword()"> Show Password
            </div>

            <input type="submit" value="Register">
        </form>
        <a class="link" href="login.html">Login</a>
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
