<!-- Concept, design and code by Alexander Dominikus (alexander.dominikus@gmail.com) -->
<!-- Menue for Desktop Start-->
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs hidden-xs hidden-sm" style="margin-bottom: 20px;">
            <li {if $current_site == 'main.php'}class="active"{/if}><a href="main.php">Home</a></li>
            <li {if $current_site == 'overview.php' OR $current_site == 'create_order.php'}class="active"{/if}><a href="overview.php">Übersicht</a></li>
            <li {if $current_site == 'user_settings.php'}class="active"{/if}><a href="user_settings.php">Einstellungen</a></li>
            <li {if $current_site == 'highscore.php'}class="active"{/if}><a href="highscore.php">Highscore</a></li>
            <li {if $current_site == 'stats.php'}class="active"{/if}><a href="stats.php">Statistik</a></li>
            <li class="nav navbar-right"><a href="logout.php">Logout</a></li> 
        </ul>
        <!-- Menue for Desktop End-->
        <!-- Menue for Phone Start-->
        <nav class="navbar navbar-default hidden-md hidden-lg">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed pull-left" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false" style="margin-left: 15px;">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">                                        
                        <li {if $current_site == 'main.php'}class="active"{/if}><a href="main.php">Home</a></li>
                        <li {if $current_site == 'customers.php' or $current_site == 'customer_detail.php'}class="active"{/if}><a href="customers.php">Customers</a></li>
                        <li {if $current_site == 'customer_map.php'}class="active"{/if}><a href="customer_map.php">Customer Map</a></li>
                        <li {if $current_site == 'calendar.php'}class="active"{/if}><a href="calendar.php">Kalender</a></li>
                        <li {if $current_site == 'user_config.php'}class="active"{/if}><a href="user_config.php">Einstellungen</a></li>
                        <li role="separator" class="divider"></li>
                        <li class="nav navbar-right"><a href="logout.php">Logout</a></li>
                    </ul>            
                </div>
            </div>
        </nav>
    </div>
</div>
<!-- Menue for Phone End-->