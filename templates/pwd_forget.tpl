<!DOCTYPE html>
<!-- Concept, design and code by Alexander Dominikus (alexander.dominikus@gmail.com) -->
<html>
<head>
    <title>SEC-Mjam</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <link rel="apple-touch-icon" sizes="120x120" href="/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/img/favicon/manifest.json">
    <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/img/favicon/favicon.ico">
    <meta name="msapplication-config" content="/img/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body style="margin-top: 5px;">
<div class="container">
    <!-- Menue for Desktop Start-->
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs hidden-xs hidden-sm" style="margin-bottom: 20px;">
                <li class="active"><a href="index.php">Login</a></li>
            </ul>
            <!-- Menue for Desktop End-->
            <!-- Menue for Phone Start-->
            <nav class="navbar navbar-default hidden-md hidden-lg">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed pull-left" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false" style="margin-left: 15px;">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li><a href="index.php">Login</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Menue for Phone End-->
    <div class="row">
        <div class="col-md-7">
            <form class="form-horizontal" method="post" action="pwd_forget.php?page=send">
                <div class="form-group">
                    <label for="email" class="col-sm-3 control-label">E-Mail Adresse:</label>
                    <div class="col-sm-7">
                        <input class="form-control" type="text" name="email" id="email" placeholder="E-Mail">
                    </div>
                </div>
                <div class="form-group">
                    <label for="submit" class="col-sm-3 control-label"></label>
                    <div class="col-sm-7">
                        <td><input class="form-control btn btn-primary" type="submit" value="Senden">
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
</body>
</html>