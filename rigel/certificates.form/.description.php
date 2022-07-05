<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
$arComponentDescription = array(
    "NAME" => 'Certificate component',
    "DESCRIPTION" => 'Certicate component',
    "SORT" => 20,
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "service",
        "CHILD" => array(
            "ID" => "certificates.form",
            "NAME" => 'form',
        ),
    ),
);


?>