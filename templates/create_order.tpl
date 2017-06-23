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
            <form action="create_order.php?page=menue" method="post">
                <div class="row">
                    <div class="col-xs-12">
                        <select name="food" onchange="this.form.submit()">
                            <option></option>
                            <option value="pizza">Pizza (Pizzakeller)</option>
                            <option value="noodles">Noodles</option>
                            <option value="kebap">Kepab</option>
                            <option value="schnitzel">Schnitzel</option>
                            {if $userid == 1}<option value="test">Test</option>{/if}
                        </select>
                    </div>
                </div>
            </form>
            {if $page == "menue"}
                <form action="order.php" method="post">
                    <div class="row">
                        <div class="col-xs-12">
                            <input type="checkbox" name="check" value="1">
                            Bestellung möglich bis: <input name="autolock" type="time" step="900" value="00:00">
                        </div>
                        <div class="col-xs-12">
                            <input type="checkbox" name="mail_check" value="1" checked>
                            Infomail aussenden?
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-xs-12'>
                            Dein Guthaben € {$userBalance|number_format:2:',':'.'}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Sub Cat</th>
                                        <th>Item</th>
                                        <th>Size</th>
                                        <th>Extras</th>
                                        <th>Prize</th>
                                        <th>Amount</th>
                                        <th>Check</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach item=item from=$menue}
                                        <tr>
                                            <td>{$item.sub_category}</td>
                                            <td>{$item.item}</td>
                                            <td>{$item.size}</td>
                                            <td>
                                            {if $category == 'noodles'}                                                
                                                <select size="1" name="sauce[]">
                                                    <option value="false"></option>
                                                    <option value="{$item.id}*Ohne">Ohne</option>
                                                    <option value="{$item.id}*Soja">Soja</option>
                                                    <option value="{$item.id}*Süß-Sauer">Süß-Sauer</option>
                                                    <option value="{$item.id}*Teriyaki">Teriyaki</option>
                                                    <option value="{$item.id}*Scharf">Scharf</option>
                                                </select>                                            
                                            {elseif $category == 'schnitzel'}
                                                <input type="checkbox" name="sauce[]">Ketchup<br>
                                                <input type="checkbox" name="sauce[]">Mayo<br>
                                                <input type="checkbox" name="sauce[]">Senf<br>
                                                <input type="checkbox" name="sauce[]">Salat<br>
                                            {elseif $category == 'kebap'}
                                                <input type="checkbox" name="sauce[]">Ohne Salat<br>
                                                <input type="checkbox" name="sauce[]">Ohne Zwiebel<br>
                                                <input type="checkbox" name="sauce[]">Ohne Tomate<br>
                                                <input type="checkbox" name="sauce[]">Ohne Sauce<br>
                                                <input type="checkbox" name="sauce[]">Ohne Scharf<br>
                                                <input type="checkbox" name="sauce[]">Ohne Rotkraut<br>
                                            {else}
                                                <input type="hidden" value="-" name="sauce[]"> -
                                            {/if}
                                            </td>
                                            <td>€ {$item.prize|number_format:2:',':'.'}</td>
                                            <td><input type="number" name="{$item.id}_amount" min="1" max="5" value="1"></td>
                                            <td><input type="checkbox" value="{$item.id}" name="foodid[]"></td>
                                        </tr>
                                    {/foreach}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" value="{$owner}" name="userid">
                    <input type="hidden" value="{$dn}" name="dn">
                    <input type="hidden" value="{$owner}" name="owner">
                    <input type="hidden" value="{$category}" name="cat">
                    <input type="hidden" value="1" name="new">
                    <input class="btn btn-primary" type="submit" value="Bestellen">
                </form>
            {/if}
        </div>
        {/nocache}
    </body>
</html>