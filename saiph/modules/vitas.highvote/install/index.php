<?php

use Bitrix\Main\Loader;

class vitas_highvote extends CModule
{
    public $MODULE_ID = "vitas.highvote";
    public $MODULE_NAME = "High vote";
	public $MODULE_VERSION = '1.0.0';
  	public $MODULE_VERSION_DATE = '09062022';

    function __construct() {
        $arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path."/version.php");

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this->MODULE_NAME = "HighVote – модуль голосования с highloadblock";
        $this->MODULE_DESCRIPTION = "После установки вы сможете голосовать";
    }

    public function DoInstall()
    {
        $file = __DIR__.'/do-install.php';
        if (file_exists($file)) {
            include $file;
        }
        RegisterModule($this->MODULE_ID);
    }

    public function DoUninstall()
    {
        $file = __DIR__.'/do-uninstall.php';
        if (file_exists($file)) {
            include $file;
        }
        UnRegisterModule($this->MODULE_ID);
    }
}
