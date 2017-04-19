<html>
    <head>
        <title>SEC Mjam</title>
        <link rel="stylesheet" href="config/style.css">
    </head>
    <body>
        {nocache}
        <form action="bank.php" method="post">
            <select name="user" onchange="this.form.submit()">
                <option></option>
                {foreach item=user from=$userlist}
                    <option value="{$user.id}">{$user.lastname} {$user.firstname}</option>
                {/foreach}
            </select>
        </form>
            {if isset($balance)}
                Balance: € {$balance|number_format:2:",":"."}
                <form action="bank.php?do=add" method="post">
                    <input type="number" min="1" step="any" name="amount" style="width: 50px;">
                    <input type="hidden" value="{$add_to_user}" name="add_to_user">
                    <input type="submit" value="Einzahlen">                    
                </form>
            {/if}
        <a href="main.php">Zurück zur Hauptseite</a>
        {/nocache}
    </body>
</html>