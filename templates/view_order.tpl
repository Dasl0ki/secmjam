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
    {nocache}
    <body style="margin-top: 5px;">
        <div class="container">
            {include 'nav.tpl'}
            <div class="row" style="margin-bottom: 20px;">
                <div class="col-md-12">
                    <span style="font-size: 24px;">
                        {if $deliverys.0.locked == 1}
                            <span class="glyphicon glyphicon-lock"></span>
                        {/if}
                        Bestellungen für den {$date_display}
                    </span>
                    <br>
                    Owner: {$ownerFullName}<br>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="item" class="table table-striped table-bordered">
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
                                {assign var=extraOutput value=''}
                                {foreach item=delivery from=$deliverys}
                                    <tr>
                                        <td>{$delivery.fullname}</td>
                                        <td>{$delivery.delivery}</td>
                                        <td>{$delivery.size}</td>
                                        <td>
                                            {if $category == 'schnitzel'}
                                                {assign var=extras value='|'|explode:$delivery.extra}
                                                {foreach item=extra from=$extras}
                                                    {assign var=extraOutput value=$extraOutput|cat:"`$extra|ucfirst`, "}
                                                {/foreach}
                                                {$extraOutput|substr:0:-2}
                                                {assign var=extraOutput value=''}
                                            {elseif $category == 'kebap'}
                                                {assign var=extras value='|'|explode:$delivery.extra}
                                                {foreach item=extra from=$extras}
                                                    {assign var=extraOutput value=$extraOutput|cat:"Ohne `$extra|ucfirst`, "}
                                                {/foreach}
                                                {$extraOutput|substr:0:-2}
                                                {assign var=extraOutput value=''}
                                            {elseif $delivery.extra == 'false'}
                                                -
                                            {else}
                                                {$delivery.extra}
                                            {/if}
                                        </td>
                                        <td>€ {$delivery.price|number_format:2:",":"."}</td>
                                        <td>
                                            {if $delivery.userid == $sessionUser AND $ownerID != $sessionUser AND $deliverys.0.locked != 1}
                                                <a href="storno.php?id={$delivery.id}">Storno</a>
                                            {/if}
                                        </td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">                
                <div class="col-md-6" style="margin-top: 15px;">
                    <div style="border: 3px solid darkred; border-radius: 15px; padding-left: 15px; padding-right: 15px;">
                        <div class="row">
                            <div class="col-xs-12" style="text-align: center;">
                                <span style="font-size: 18px;">Zusammenfassung</span>
                            </div>
                        </div>
                        {assign var=totalItemsCount value=0}
                        {foreach item=item from=$totalItems}
                            <div class="row">
                                <div class="col-xs-12">
                                    {$item.count}x {$item.item}
                                    {assign var=totalItemsCount value=$totalItemsCount+$item.count}
                                </div>
                            </div>
                        {/foreach}
                            <div class="row" style="border-top: 3px double black">
                                <div class="col-sm-6">
                                    Summe aller Bestellungen: {$totalItemsCount}
                                </div>
                                <div class="col-sm-6">
                                    Punktewert: {$points}
                                </div>
                            </div>
                    </div>
                </div>
                <div class="col-md-6" style="margin-top: 15px;">
                    <table class="table table-striped">
                        {foreach item=row from=$total}
                            <tr> 
                                <td>
                                    {$row.fullname}
                                </td>
                                <td>
                                    € {$row.total|number_format:2:",":"."}
                                </td>
                            </tr>
                        {/foreach}
                        <tr style="border-top: 3px double black;">
                            <td>
                                Summe:
                            </td>
                            <td>
                                € {$totalSum|number_format:2:",":"."}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            {if $ownerID == $sessionUser}
                {if $deliverys.0.status != 1}
                    <div class="row">
                        <div class="col-xs-12">
                            {if $deliverys.0.status != 1}
                                {if $deliverys.0.locked == 1}
                                    <a href="overview.php?dn={$dn}&do=unlock" class="btn btn-primary">Bestellung entsperren</a>
                                {else}
                                    <a href="overview.php?dn={$dn}&do=lock" class="btn btn-primary">Bestellung sperren</a>
                                {/if}
                            {/if}
                        </div>
                    </div>                
                    <br>
                    <form action="overview.php?dn={$dn}&do=close" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                    {for $i=0 to 9}
                                        <div class="row">
                                            {for $c=0 to $to}                    
                                                {assign var="add" value=$c|cat:"0"}
                                                {assign var="key" value=$i+$add}
                                                {if $key|array_key_exists:$helperArray}
                                                    <div class="col-md-{$col} col-xs-12">
                                                        <div class="checkbox" style="margin-top: 3px; margin-bottom: 3px;">
                                                            <label>
                                                                <input type="checkbox" name="helper[]" value="{$helperArray.$key.id}" {if $helperArray.$key.id == $ownerID}disabled{/if}>                            
                                                                {$helperArray.$key.firstname} {$helperArray.$key.lastname}
                                                            </label>
                                                        </div>
                                                    </div>
                                                {else}
                                                    <div class="col-md-{$col} col-xs-12"></div>
                                                {/if}
                                            {/for}
                                        </div>
                                    {/for}
                            </div>
                        </div>                    
                        <br>
                        <div class="row">
                            <div class="col-xs-12">
                                <input type="hidden" name="category" value="{$category}">
                                <input type="submit" class="btn btn-danger" value="Bestellung abschließen">
                            </div>
                        </div>
                    </form>
                {/if}
            {/if}
        </div>
        {if $category == 'schnitzel' OR $category == 'kebap'}
            <script type="text/javascript">
                $(document).ready(function () {
                    $('td:nth-child(3),th:nth-child(3)').hide();
                })
            </script>
        {/if}
    </body>
    {/nocache}
</html>
