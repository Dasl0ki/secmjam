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
            {if $openOrders != NULL}
                {foreach item=order from=$openOrders}
                    <a href="overview.php?dn={$order.dn}">
                        {if $order.locked == 1}
                            <span class="glyphicon glyphicon-lock"></span>
                        {/if}
                        {$order.date_output} - {$order.category|ucfirst} - {$order.ownerFullname}
                    </a><br>
                {/foreach}
            {/if}
            <br>
            <a href="create_order.php" class="btn btn-primary">Neue Bestellung anlegen</a>
            <br>
            <br>
            Meine letzten 10 Abgeschlossenen Bestellungen:<br><br>
            {foreach item=order from=$orders}
                <a href='overview.php?dn={$order.dn}'>{$order.date} - {$order.category|ucfirst}</a><br>
            {/foreach}
        </div>
        {/nocache}
    </body>
</html>