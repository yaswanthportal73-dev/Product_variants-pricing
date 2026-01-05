<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>404 Page Not Found</title>
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
            <h1>404 - Page Not Found</h1>
        </div>
        
        <div class="content">
            <div class="message">
                <h3>The page you are looking for was not found.</h3>
                <p>The page you requested could not be found on this server.</p>
            </div>

            <div class="actions">
                <a href="javascript:history.back()" class="btn">Go Back</a>
                <a href="<?php echo base_url(); ?>" class="btn">Go to Homepage</a>
            </div>
        </div>
    </div>
</body>
</html>