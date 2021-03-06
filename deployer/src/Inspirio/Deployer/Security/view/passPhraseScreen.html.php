<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Project deployer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="public/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/font-awesome.min.css" rel="stylesheet">
    <link href="public/css/app.css" rel="stylesheet">
    <link rel="shortcut icon" href="public/favicon.ico">

    <style type="text/css">
        body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
        }

        .alert {
            text-align: left;
        }

        .form-signin {
            width: 400px;
            padding: 19px 29px 29px;
            margin: 0 auto 20px;
            background-color: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 5px;
            box-shadow: 0 1px 2px rgba(0,0,0,.05);
        }


    </style>
</head>

<body>
    <div class="container">
        <form class="form-signin form-inline" method="post">
            <h2 class="form-signin-heading">Authorization required</h2>

            <?php
                if (isset($error) && $error) {
                    echo "<div class=\"alert alert-error\">{$error}</div>";
                } else {
                    echo "<p>Please authorize your-self by entering a security pass-prase.</p>";
                }
            ?>

            <div class="input-append">
                <input type="password" name="security_phrase" class="input-xlarge">
                <button class="btn btn-primary" type="submit"><i class="icon-lock"></i> Authorize</button>
            </div>
        </form>
    </div>
</body>
</html>
