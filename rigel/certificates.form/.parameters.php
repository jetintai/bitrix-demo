<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
$arComponentParameters = array(
    "GROUPS" => array(
        "AJAX_SETTINGS" => array(
            "NAME" => 'Опции Ajax',
            'SORT' => 100,
        ),
    ),
    "PARAMETERS" => array(
        "AJAX_MODE" => array(
            "NAME" => 'режим Ajax',
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
            "ADDITIONAL_VALUES" => "Y",
            "PARENT" => "AJAX_SETTINGS",
        ),
    ),
);
