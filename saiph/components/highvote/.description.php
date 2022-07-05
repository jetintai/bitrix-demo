<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("HV_NAME"),
	"DESCRIPTION" => GetMessage("HV_DESC"),
	//"ICON" => "/images/photo_detail.gif",
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "content",
		"CHILD" => array(
			"ID" => "iblock",
			"NAME" => GetMessage("HV_SUBNAME"),
		),
	),
);

?>