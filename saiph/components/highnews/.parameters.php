<?php

use Bitrix\Main\Loader;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arCurrentValues */
Loader::includeModule('iblock');

$arComponentParameters = [
    "PARAMETERS" => [
        "VARIABLE_ALIASES" => [
            "ID" => [
                "NAME" => 'Символьный код элемента',
            ],
        ],
        "SEF_MODE" => [
            "element" => [
                "NAME" => 'Детальная страница',
                "DEFAULT" => "#ID#/",
                "VARIABLES" => [
                    "ELEMENT_ID",
                    "ELEMENT_CODE",
                ]
            ]
        ],
        "CACHE_TIME" => 360000,
    ]
];

CIBlockParameters::Add404Settings($arComponentParameters, []);