<html>
    <head>
        <title>SEC Mjam</title>
        <link rel="stylesheet" href="config/style.css">
    </head>
    {nocache}
    <body>
        <table id="stat_frame">
            <tr>
                <td style="width: 50%">
                    <table class="stat_table" style="width: 100%;">
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
                </td>
                <td style="width: 50%">
                    <table class="stat_table" style="width: 100%; max-width: inherit;">
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
                </td>
            </tr>
            <tr>
                <td>
                    <table class="stat_table" style="width: 100%; max-width: inherit;">
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
                </td>
                <td>
                    <table class="stat_table" style="width: 100%; max-width: inherit;">
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
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="stat_table" style="width: 100%; max-width: inherit;">
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
                </td>
            </tr>
        </table>
        <br>
        <a href="main.php">Zurück zur Hauptseite</a>
    </body>
    {/nocache}
</html>