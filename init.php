<?php
define("UPLOAD_DIR", "./");
define("ERROR", "STOP! Error time! I have no idea what caused this.");
define("LOG_FILE", "./error_log.txt");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
?>
    <form action="init.php" method="post" enctype="multipart/form-data">
        <input type="file" name="myFile"/>
        <br/>
        <input type="submit" value="Upload"/>
    </form>
<?php
} else if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_FILES["myFile"])) {
    $myFile = $_FILES["myFile"];
    if ($myFile["error"] !== UPLOAD_ERR_OK) {
        logError($myFile["error"]);
        echo ERROR;
        exit;
    }

    $name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);

    $success = move_uploaded_file($myFile["tmp_name"], UPLOAD_DIR . $name);
    if (!$success) {
        logError("Failed to move uploaded file.");
        echo ERROR;
        exit;
    }
    echo "Uploaded file! <a href=\"$name\">Click</a> to execute/view ";
}

function logError($errorMessage) {
    $date = date('Y-m-d H:i:s');
    $message = "$date - ERROR: $errorMessage" . PHP_EOL;
    file_put_contents(LOG_FILE, $message, FILE_APPEND);
}
?>
