<?php
define('SMARTY_DIR', '/var/www/vhosts/loki-net.at/smarty/');
require(SMARTY_DIR . 'Smarty.class.php');

class Smarty_mjam extends Smarty {

    function __construct()
    {
        parent::__construct();

        $this->setTemplateDir('/var/www/vhosts/loki-net.at/sec-mjam.loki-net.at/templates');
        $this->setCompileDir('/var/www/vhosts/loki-net.at/sec-mjam.loki-net.at/templates_c');
        $this->setConfigDir('/var/www/vhosts/loki-net.at/sec-mjam.loki-net.at/configs');
        $this->setCacheDir('/var/www/vhosts/loki-net.at/sec-mjam.loki-net.at/cache');

        $this->caching = Smarty::CACHING_LIFETIME_CURRENT;
        $this->assign('app_name', 'sec-mjam');
    }
}
?>