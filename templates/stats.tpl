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
    {nocache}
    <body style="margin-top: 5px;">
        <div class="container">
            {include "nav.tpl"}
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th colspan="2" align="center"><b>Top 5 Bestellungen</b></th>
                        </tr>
                        {assign var='counter' value=2}
                        {foreach item=item from=$count_arr}
                            {if $counter % 2 == 0}
                                <tr class='one'>
                                    {else}
                                <tr class='two'>
                            {/if}
                            <td align="center" style="width: 10%;">{$item.count}x</td><td>{$item.item}</td>
                            </tr>
                            {assign var='counter' value=$counter+1}
                        {/foreach}
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th colspan="2" align="center"><b>Persönliche Top 5 Bestellungen</b></th>
                        </tr>
                        {assign var='counter' value=2}
                        {foreach item=item from=$count_arr_pers}
                            {if $counter % 2 == 0}
                                <tr class='one'>
                                    {else}
                                <tr class='two'>
                            {/if}
                            <td align="center" style="width: 10%;">{$item.count}x</td><td>{$item.item}</td>
                            </tr>
                            {assign var='counter' value=$counter+1}
                        {/foreach}
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th colspan="2">Top Kategorien</th>
                        </tr>
                        {assign var='counter' value=2}
                        {foreach item=cat from=$cat_arr}
                            {if $counter % 2 == 0}
                                <tr class='one'>
                                    {else}
                                <tr class='two'>
                            {/if}
                            <td align="center" style="width: 10%;">{$cat.count}x</td>
                            <td>{$cat.category|ucfirst}</td>
                            </tr>
                            {assign var='counter' value=$counter+1}
                        {/foreach}
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th colspan="2">Persönliche Top Kategorien</th>
                        </tr>
                        {assign var='counter' value=2}
                        {foreach item=cat from=$cat_arr_pers}
                            {if $counter % 2 == 0}
                                <tr class='one'>
                                    {else}
                                <tr class='two'>
                            {/if}
                            <td align="center" style="width: 10%;">{$cat.count}x</td>
                            <td>{$cat.category|ucfirst}</td>
                            </tr>
                            {assign var='counter' value=$counter+1}
                        {/foreach}
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th colspan="3">Top 3 Bestellungen in einem einzigen Monat</th>
                        </tr>
                        {assign var='counter' value=2}
                        {foreach item=orderer from=$orderer_arr}
                            {if $counter % 2 == 0}
                                <tr class='one'>
                                    {else}
                                <tr class='two'>
                            {/if}
                            <td align="center" style="width: 10%;">{$orderer.count}x</td>
                            <td>{$orderer.firstname} {$orderer.lastname}</td>
                            <td>{$orderer.output_date}</td>
                            </tr>
                            {assign var='counter' value=$counter+1}
                        {/foreach}
                    </table>
                </div>
            </div>
        </div>
    </body>
    {/nocache}
</html>