<?php
if ( ! defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Highloadblock\HighloadBlockTable as HL;

Loc::loadMessages(__FILE__);

// Параметры
$arComponentParameters = [
    'GROUPS'     => [
        'DETAIL_VIEW' => [
            'NAME' => Loc::getMessage('SIMPLELK_DETAIL_GROUP_NAME'),
        ],
        'LIST_VIEW' => [
            'NAME' => Loc::getMessage('SIMPLELK_LIST_GROUP_NAME'),
        ],
    ],
    'PARAMETERS' => [
        // ЧПУ
        'SEF_MODE'          => [
            'list' => [
                'NAME'      => Loc::getMessage('SIMPLELK_CHP_VIEW_LIST'),
                'DEFAULT'   => '',
                'VARIABLES' => ['USER_ID'],
            ],
            'detail' => [
                'NAME'      => Loc::getMessage('SIMPLELK_CHP_VIEW_DETAIL'),
                'DEFAULT'   => '#USER_ID#/',
                'VARIABLES' => ['USER_ID', 'ID'],
            ],
        ],
        // Список
        'LIST_ROWS_PER_PAGE'     => [
            'PARENT'  => 'LIST_VIEW',
            'NAME'    => Loc::getMessage('SIMPLELK_COMPONENT_LIST_PER_PAGE_PARAM'),
            'TYPE'    => 'TEXT',
            'DEFAULT' => '10',
        ],
        'LIST_SORT_FIELD'        => [
            'PARENT'  => 'LIST_VIEW',
            'NAME'    => Loc::getMessage('SIMPLELK_COMPONENT_LIST_SORT_FIELD_PARAM'),
            'TYPE'    => 'TEXT',
            'DEFAULT' => 'ID',
        ],
        'LIST_SORT_ORDER'        => [
            'PARENT'  => 'LIST_VIEW',
            'NAME'    => Loc::getMessage('SIMPLELK_COMPONENT_LIST_SORT_ORDER_PARAM'),
            'TYPE'    => 'LIST',
            'DEFAULT' => 'DESC',
            'VALUES'  => [
                'DESC' => Loc::getMessage('SIMPLELK_COMPONENT_SORT_ORDER_PARAM_DESC'),
                'ASC'  => Loc::getMessage('SIMPLELK_COMPONENT_SORT_ORDER_PARAM_ASC'),
            ],
        ],
        'DETAIL_SORT_FIELD'        => [
            'PARENT'  => 'DETAIL_VIEW',
            'NAME'    => Loc::getMessage('SIMPLELK_COMPONENT_LIST_SORT_FIELD_PARAM'),
            'TYPE'    => 'TEXT',
            'DEFAULT' => 'ID',
        ],
        'DETAIL_SORT_ORDER'        => [
            'PARENT'  => 'DETAIL_VIEW',
            'NAME'    => Loc::getMessage('SIMPLELK_COMPONENT_LIST_SORT_ORDER_PARAM'),
            'TYPE'    => 'LIST',
            'DEFAULT' => 'DESC',
            'VALUES'  => [
                'DESC' => Loc::getMessage('SIMPLELK_COMPONENT_SORT_ORDER_PARAM_DESC'),
                'ASC'  => Loc::getMessage('SIMPLELK_COMPONENT_SORT_ORDER_PARAM_ASC'),
            ],
        ],
    ],
];