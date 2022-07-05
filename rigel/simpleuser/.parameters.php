<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

$arComponentParameters = array(
	"GROUPS" => array(
        'SETTINGS_VIEW' => [
            'NAME' => Loc::getMessage('COMP_SETTINGS_VIEW_NAME'),
        ],
	),
	"PARAMETERS" => array(
        "FILTER_COLUMN" => Array(
            "NAME" => GetMessage("COMP_FILTER_COLUMN"),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => array(
                "FULL_NAME" => 'ФИО',
                "BIRTHDAY" => 'День рождение',
                "LOGIN" => 'Логин',
                "EMAIL" => 'EMAIL',
            ),
            "DEFAULT" =>"Y",
            "PARENT" => "SETTINGS_VIEW",
        ),
	),
);

