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
    <style>
        .first {
            background: #f0e68c;
            background: -webkit-linear-gradient(left, #f0e68c, #ffffff);
            background: -moz-linear-gradient(left, #f0e68c, #ffffff);
            background: -ms-linear-gradient(left, #f0e68c, #ffffff);
            background: -o-linear-gradient(left, #f0e68c, #ffffff);
            background: linear-gradient(to right, #f0e68c, #ffffff);
        }

        .second {
            background: #c0c0c0;
            background:-webkit-linear-gradient(left, #c0c0c0, #ffffff);
            background: -moz-linear-gradient(left, #c0c0c0, #ffffff);
            background: -ms-linear-gradient(left, #c0c0c0, #ffffff);
            background: -o-linear-gradient(left, #c0c0c0, #ffffff);
            background: linear-gradient(to right, #c0c0c0, #ffffff);
        }

        .third {
            background: #d7995b;
            background:-webkit-linear-gradient(left, #d7995b, #ffffff);
            background: -moz-linear-gradient(left, #d7995b, #ffffff);
            background: -ms-linear-gradient(left, #d7995b, #ffffff);
            background: -o-linear-gradient(left, #d7995b, #ffffff);
            background: linear-gradient(to right, #d7995b, #ffffff);
        }
    </style>
</head>
<body style="margin-top: 5px;">
{nocache}
<div class="container">
    {include 'nav.tpl'}
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Platz</th>
                            <th>User</th>
                            <th>Punkte</th>
                            <th>Bestellungen</th>
                            <th>Quote</th>
                        </tr>
                    </thead>
                    <tbody>
                        {assign var=i value=1}
                        {foreach item=user from=$highscore}
                            {if $user.orders != '0'}
                                <tr class="{if $i == '1'}
                                        first
                                    {elseif $i == '2'}
                                        second
                                    {elseif $i == '3'}
                                        third
                                    {/if}">
                                    <td>#{$i}</td>
                                    <td>{$user.data.firstname} {$user.data.lastname}</td>
                                    <td>{$user.points}</td>
                                    <td>{$user.orders}</td>
                                    <td>{$user.quote}%</td>
                                </tr>
                                {assign var=i value=$i+1}
                            {/if}
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{/nocache}
</body>
</html>