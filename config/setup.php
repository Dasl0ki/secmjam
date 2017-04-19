<?php
define('SMARTY_DIR', 'C:/xampp/htdocs/secmjam/smarty/');
require(SMARTY_DIR . 'Smarty.class.php');

class Smarty_mjam extends Smarty {

    function __construct()
    {
        parent::__construct();

        $this->setTemplateDir('C:/xampp/htdocs/secmjam/templates');
        $this->setCompileDir('C:/xampp/htdocs/secmjam/templates_c/');
        $this->setConfigDir('C:/xampp/htdocs/secmjam/configs/');
        $this->setCacheDir('C:/xampp/htdocs/secmjam/cache/');

        $this->caching = Smarty::CACHING_LIFETIME_CURRENT;
        $this->assign('app_name', 'sec-mjam');
    }
}
?>