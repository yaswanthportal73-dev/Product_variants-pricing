<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Database Error</title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }
        body {
            background: #f9f9f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: #e74c3c;
            color: white;
            padding: 20px;
            border-radius: 5px 5px 0 0;
            margin: -20px -20px 20px -20px;
        }
        .content {
            padding: 20px 0;
        }
        .message {
            background: #f8f9fa;
            border-left: 4px solid #e74c3c;
            padding: 15px;
            margin: 15px 0;
        }
        .debug {
            background: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-family: monospace;
            font-size: 14px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .actions {
            margin-top: 20px;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 10px;
        }
        .btn:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Database Error</h1>
        </div>
        
        <div class="content">
            <div class="message">
                <h3>An error occurred while connecting to the database</h3>
                <p>Please check your database configuration and try again.</p>
            </div>

            <?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>
                <div class="debug">
                    <p><strong>Error Message:</strong> <?php echo $message; ?></p>
                    <p><strong>Error Number:</strong> <?php echo isset($error_no) ? $error_no : 'N/A'; ?></p>
                    
                    <?php if (isset($sql) && !empty($sql)): ?>
                        <p><strong>SQL Query:</strong> <?php echo $sql; ?></p>
                    <?php endif; ?>
                    
                    <p><strong>Backtrace:</strong></p>
                    <?php 
                    if (function_exists('debug_backtrace')) {
                        $backtrace = debug_backtrace();
                        foreach ($backtrace as $trace) {
                            if (isset($trace['file']) && $trace['file'] != __FILE__) {
                                echo 'File: ' . $trace['file'] . ' (Line: ' . $trace['line'] . ')' . "\n";
                            }
                        }
                    }
                    ?>
                </div>
            <?php endif; ?>

            <div class="actions">
                <a href="javascript:history.back()" class="btn">Go Back</a>
                <a href="<?php echo base_url(); ?>" class="btn">Go to Homepage</a>
            </div>
        </div>
    </div>
</body>
</html>