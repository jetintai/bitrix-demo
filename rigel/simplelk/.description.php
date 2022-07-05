<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("HG_TITLE"),
	"DESCRIPTION" => GetMessage("HG_NAME"),
	//"ICON" => "/images/photo_detail.gif",
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "content",
		"CHILD" => array(
			"ID" => "iblock",
			"NAME" => GetMessage("HG_DESC"),
		),
	),
);

?>