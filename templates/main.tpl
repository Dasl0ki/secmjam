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
        <script>
            function getVote(int) {
                xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById("poll").innerHTML = xmlhttp.responseText;
                    }
                };
                xmlhttp.open("GET", "poll_vote.php?vote=" + int, true);
                xmlhttp.send();
            }
        </script>
        <style>
            html {
                position: relative;
                min-height: 100%;
            }
            body {
                /* Margin bottom by footer height */
                margin-bottom: 40px;
            }
            .footer {
                position: absolute;
                bottom: 0;
                width: 100%;
                /* Set the fixed height of the footer here */
                height: 40px;
                background-color: #f5f5f5;
                text-align: center;
            }
        </style>
    </head>
    <body style="margin-top: 5px;">
        {nocache}
        <div class="container">
            {include 'nav.tpl'}
            <div class="row">
                <div class="col-md-6">
                    <div class="well well-sm">
                        <span style="font-size: 18px;">Übersicht</span>
                        <p style="padding-left:25px; padding-right: 25px;">
                            {*Guthaben: € 0,00*}
                            Punktestand: {$user.points}
                        </p>
                    </div>
                </div>
                {nocache}
                <div class="col-md-6">
                    <div class="well well-sm">
                        <span style="font-size: 18px;">Todays Lunch?</span>
                        <div id="poll" style="padding-left: 25px; padding-right: 25px;">
                            {if $voteStatus == '1'}
                                {include 'voteBlock.tpl'}
                            {else}
                            <form>
                                <div class="radio">
                                    <label for="noodles">                                
                                        <input type="radio" value="1" onclick="getVote(this.value)">
                                        Noodles
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="pizza">                                
                                        <input type="radio" value="2" onclick="getVote(this.value)">
                                        Pizza
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="kebap">                                
                                        <input type="radio" value="3" onclick="getVote(this.value)">
                                        Kebap
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="schnitzel">                                
                                        <input type="radio" value="4" onclick="getVote(this.value)">
                                        Schnitzel
                                    </label>
                                </div>
                            </form>
                            {/if}
                        </div>
                    </div>
                </div>
                {/nocache}
            </div>
            <div class="row">
                <div class="col-md-12" style="text-align: center;">
                    <iframe style="width: 100%; height: 450px;" src="changelog.php"></iframe>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <p class="text-muted">© 2015-2017 <a href="mailto: alexander.dominikus@gmail.com">Alexander Dominikus</a>. Powered by <a href="http://getbootstrap.com" target="_blank">bootstrap</a> and <a href="http://glyphicons.com/" target="_blank">Glyphicons</a></p>
            </div>
        </footer>
        {/nocache}
    </body>
</html>