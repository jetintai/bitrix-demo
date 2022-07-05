<?php
if ( ! defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
\Bitrix\Main\Loader::includeModule('vitas.highvote');

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Vitas\Highvote\CandidateNewsTable;
use Bitrix\Iblock\Component\Tools;


Loc::loadMessages(__FILE__);

class HighNewsComponent extends CBitrixComponent {

    public function executeComponent() {
        Loader::includeModule('iblock');
        global $USER;

        $componentPage = $this->noSefMode();

        $cache_id = serialize(array($arParams, ($arParams['CACHE_GROUPS']==='N'? false: $USER->GetGroups())));
        $obCache = new CPHPCache;
        if ($obCache->InitCache($arParams['CACHE_TIME'], $cache_id, '/'))
        {
            $vars = $obCache->GetVars();
            $arResult = $vars['arResult'];
        }
        elseif ($obCache->StartDataCache())
        {
            $this->arResult = array_merge($this->arResult, $this->prepareComponentData($componentPage));
            $obCache->EndDataCache(array(
                'arResult' => $arResult,
            ));
        }

        $this->IncludeComponentTemplate($componentPage);
    }

    protected function noSefMode()
    {
        $componentPage = "";
        $arVariables = [];
        $arDefaultVariableAliases = [];

        $arVariableAliases = CComponentEngine::makeComponentVariableAliases($arDefaultVariableAliases, $this->arParams["VARIABLE_ALIASES"]);
        CComponentEngine::initComponentVariables(false, $this->arComponentVariables, $arVariableAliases, $arVariables);

        if (isset($arVariables["ID"]) && intval($arVariables["ID"]) > 0)
        {
            $componentPage = "detail";
        } else {
            $componentPage = "list";
        }

        $this->arVariables = $arVariables;
        $this->arResult = [
            "VARIABLES" => $arVariables,
            "ALIASES" => $arVariableAliases
        ];

        return $componentPage;
    }

    protected function prepareComponentData($page) {
        if ($page == 'list') {
            $candidate_news = new CandidateNewsTable();
            $dbRes = $candidate_news::getList([
                'select' => ['*'],
                'filter' => [],
                'limit' => 10,
                'count_total' => true,
            ]);
            return array('ITEMS' => $dbRes->fetchAll());
        } else if ($page == 'detail') {
            $candidate_news = new CandidateNewsTable();
            /*$db_res = $candidate_news::getById($this->arVariables['ID']);*/

            $dbRes = $candidate_news::getList([
                'select' => ['*'],
                'filter' => ['ID' => $this->arVariables['ID']],
                'limit' => 1,
                'count_total' => true,
            ]);

            return $dbRes->fetch();
        }

        return [];
    }
}