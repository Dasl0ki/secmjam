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
        <script src="css/less.min.js" type="text/javascript" /></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
    {nocache}
    <body style="margin-top: 5px;">
        <div class="container">
            {include 'nav.tpl'}
            <div class="row">
                <div class="col-md-12">
                    <span style="font-size: 24px;">Bestellungen für den {$date_display}</span><br>
                    Owner: {$ownerFullName}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="item" class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Bestellung</th>
                                    <th>Größe</th>
                                    <th>Extras</th>
                                    <th>Preis</th>
                                    <th>Optionen</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach item=delivery from=$deliverys}
                                    <tr>
                                        <td>{$delivery.fullname}</td>
                                        <td>{$delivery.delivery}</td>
                                        <td>{$delivery.size}</td>
                                        <td>{$delivery.extra}</td>
                                        <td>{$delivery.price}</td>
                                        <td><button class="btn btn-primary">Storno</button>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {if $jq_cat == 'schnitzel' OR $jq_cat == 'kebap'}
            <script type="text/javascript">
                $(document).ready(function () {
                    $('td:nth-child(3),th:nth-child(3)').hide();
                })
            </script>
        {/if}
    </body>
    {/nocache}
</html>
