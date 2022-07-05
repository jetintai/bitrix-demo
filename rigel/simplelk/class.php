<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Vitas\Highvote\CandidateNewsTable;
use Bitrix\Iblock\Component\Tools;

\Bitrix\Main\Loader::includeModule('sale');

Loc::loadMessages(__FILE__);

class SimpleLk extends CBitrixComponent
{

    protected $arDefaultVariableAliases404 = [];
    protected $arDefaultVariableAliases = [];
    protected $arDefaultUrlTemplates404 = [
        'list' => '',
        'detail' => '#USER_ID#/',
    ];

    protected $arComponentVariables = [
        'USER_ID',
    ];

    protected $arUrlTemplates = [];
    protected $componentPage = '';
    protected $arVariables = [];

    public function executeComponent()
    {
        Loader::includeModule('iblock');
        global $USER;

        global $APPLICATION;

        $arComponentVariables = $this->arComponentVariables;
        $arVariables = $this->arVariables;

        if ($this->arParams['SEF_MODE'] == 'Y') {
            $arDefaultUrlTemplates404 = $this->arDefaultUrlTemplates404;
            $arDefaultVariableAliases404 = $this->arDefaultVariableAliases404;

            $arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates(
                $arDefaultUrlTemplates404,
                $this->arParams['SEF_URL_TEMPLATES']
            );

            $arVariableAliases = CComponentEngine::MakeComponentVariableAliases(
                $arDefaultVariableAliases404,
                $this->arParams['VARIABLE_ALIASES']
            );

            $componentPage = CComponentEngine::ParseComponentPath(
                $this->arParams['SEF_FOLDER'],
                $arUrlTemplates,
                $arVariables
            );

            if (!$componentPage) {
                $componentPage = 'list';
            }

            CComponentEngine::InitComponentVariables(
                $componentPage,
                $arComponentVariables,
                $arVariableAliases,
                $arVariables
            );

            $this->arResult['FOLDER'] = $this->arParams['SEF_FOLDER'];
            $this->arResult['URL_TEMPLATES'] = $arUrlTemplates;
        } else {
            $arDefaultVariableAliases = $this->arDefaultVariableAliases;

            $arVariableAliases = CComponentEngine::MakeComponentVariableAliases(
                $arDefaultVariableAliases,
                $this->arParams['VARIABLE_ALIASES']
            );

            CComponentEngine::InitComponentVariables(
                false,
                $arComponentVariables,
                $arVariableAliases,
                $arVariables
            );

            if (isset($arVariables['USER_ID']) && intval($arVariables['USER_ID']) > 0) {
                $componentPage = 'detail';
            } else {
                $componentPage = 'list';
            }

            $sGetCurPage = htmlspecialchars($APPLICATION->GetCurPage());

            $this->arResult['FOLDER'] = '';
            $this->arResult['URL_TEMPLATES'] = [
                'list' => $sGetCurPage,
                'detail' => $sGetCurPage . '?' . $arVariableAliases['USER_ID'] . '=#USER_ID#',
            ];
        }

        $this->arResult['VARIABLES'] = $arVariables;
        $this->arVariables = $arVariables;
        $this->arResult['ALIASES'] = $arVariableAliases;
        $this->arResult['CURRENT_TEMPLATE'] = $componentPage;

        $cache_id = serialize(array($arParams, ($arParams['CACHE_GROUPS'] === 'N' ? false : $USER->GetGroups())));
        $obCache = new CPHPCache;
        if ($obCache->InitCache($arParams['CACHE_TIME'], $cache_id, '/')) {
            $vars = $obCache->GetVars();
            $arResult = $vars['arResult'];
        } elseif ($obCache->StartDataCache()) {
            if ($componentPage == 'list')
                $this->arResult['USERS'] = $this->getUsers();
            else
                $this->arResult['ORDERS'] = $this->getOrdersByUser();
            $obCache->EndDataCache(array(
                'arResult' => $arResult,
            ));
        }

        $this->IncludeComponentTemplate($componentPage);
    }

    protected function getOrdersByUser()
    {
        $order = array();
        if (!empty($this->arParams['DETAIL_SORT_FIELD']))
            $order[$this->arParams['DETAIL_SORT_FIELD']] = $this->arParams['DETAIL_SORT_ORDER'];

        return $db_res = \Bitrix\Sale\Order::getList(array(
            'select' => array('*', 'STATUS_NAME' => 'STATUS_LANG.NAME'),
            'order' => $order,
            'limit' => 50,
            'filter' => array('USER_ID' => $this->arVariables['USER_ID']),
            'runtime' => [
                new \Bitrix\Main\Entity\ReferenceField(
                    'STATUS_LANG',
                    '\Bitrix\Sale\Internals\StatusLangTable',
                    ["=this.STATUS_ID" => "ref.STATUS_ID"],
                    ["join_type" => "inner"]
                ),
            ],
            'group' => array('ID')
        ))->fetchAll();
    }

    protected function getUsers()
    {

        $nav = new \Bitrix\Main\UI\PageNavigation("nav-users");
        $nav->allowAllRecords(true)
            ->setPageSize($this->arParams['LIST_ROWS_PER_PAGE'])
            ->initFromUri();

        $order = array();
        if (!empty($this->arParams['LIST_SORT_FIELD']))
            $order[$this->arParams['LIST_SORT_FIELD']] = $this->arParams['LIST_SORT_ORDER'];

        $db_res = \Bitrix\Main\UserTable::getList(array(
            'select' => array('*', 'ORDERS_COUNT'),
            'order' => $order,
            'group' => array(
                'ID',
            ),
            'offset' => $nav->getOffset(),
            'limit' => $nav->getLimit(),
            'count_total' => true,
            //'limit' => 3
            'runtime' => [
                'ORDERS_REFERENCE' => [
                    'data_type' => 'Bitrix\Sale\Order',
                    'reference' => [
                        '=this.ID' => 'ref.USER_ID',
                    ],
                    'join_type' => 'left',
                ],
                'ORDERS_COUNT' => [
                    'data_type' => Bitrix\Main\ORM\Fields\IntegerField::class,
                    'expression' => [
                        'COUNT(%s)',
                        'ORDERS_REFERENCE.ID'
                    ],
                ]

            ],
        ));

        while ($user = $db_res->fetch()) {
            if (!empty($user['PERSONAL_PHOTO'])) {
                $rs_image = \CFile::GetById($user['PERSONAL_PHOTO']);
                $ar_image = $rs_image->Fetch();
                $user['PERSONAL_PHOTO'] = \CFile::ResizeImageGet(
                    $ar_image,
                    array('width' => 60, 'height' => 60),
                    BX_RESIZE_IMAGE_PROPORTIONAL,
                    true
                );
            }
            $user['ORDERS_PAGE_URL'] = $this->arResult["URL_TEMPLATES"]["list"] . $user['ID'] . '/';
            if ($this->arParams['SEF_MODE'] == 'N')
                $user['ORDERS_PAGE_URL'] = $this->arResult["URL_TEMPLATES"]["list"] . '?USER_ID=' . $user['ID'];
            $users[] = $user;
        }

        $nav->setRecordCount($db_res->getCount());
        $this->arResult['NAV_OBJECT'] = $nav;

        return $users;
    }
}