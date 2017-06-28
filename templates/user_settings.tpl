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
    {nocache}
        <div class="container">
            {include 'nav.tpl'}
            <div class="row">
                {if $success == TRUE}
                    <div class="col-md-12">
                        <div class="alert alert-success" role="alert">
                            Einstellungen gespeichert
                        </div>
                    </div>
                {elseif $error == TRUE}
                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                            Einstellungen gespeichert
                        </div>
                    </div>
                {else}
                    <div class="col-md-7">
                        <form class="form-horizontal" method="post" action="user_settings.php?page=save">
                            <div class="form-group">
                                <label for="email" class="col-sm-4 control-label">E-mail Adresse:</label>
                                <div class="col-sm-8">
                                    <input type="email" name="email" class="form-control" id="email" value="{$user.email}" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="mail_check" class="col-sm-4 control-label">E-mail Benachrichtigung?</label>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="notify" id="notify" {if $user.notify != '1'} value="1" {else} checked{/if}>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="active" class="col-sm-4 control-label">Account Aktiv?</label>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="active" id="active" {if $user.active != '1'} value="1" {else} checked{/if}>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="submit" value="Speichern" class="btn btn-success btn-block" style="margin-top: 5px; margin-bottom: 5px;"">
                                </div>
                                <div class="col-md-6">
                                    <a href="changepwd.php" class="btn btn-primary btn-block" style="margin-top: 5px; margin-bottom: 5px;">Passwort ändern</a>
                                </div>
                            </div>
                        </form>
                    </div>
                {/if}
            </div>
        </div>
    {/nocache}
    </body>
</html>