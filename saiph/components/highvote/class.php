<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
\Bitrix\Main\Loader::includeModule('vitas.highvote');

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use \Vitas\Highvote\CandidateNewsTable;
use \Vitas\Highvote\HighvoteTable;



Loc::loadMessages(__FILE__);

class HighVoteComponent extends CBitrixComponent {

    private $vote_state = false;
    private $user_ip, $error;

    public function executeComponent()
    {
        $this->user_ip = \Bitrix\Main\Service\GeoIp\Manager::getRealIp();

        //FIXME: Replace $_REQUEST
        if (isset($_REQUEST['VOTE_TOGGIE'])
            && in_array($_REQUEST['VOTE_TOGGIE'], ['Y', 'N'])) {
            $this->toggieVoteState($_REQUEST['VOTE_TOGGIE']);
        }

        if (isset($this->user_ip)) {
            $this->vote_state = $this->getVoteState();
            $this->arResult['VOTE_STATE'] = $this->vote_state;
            $this->arResult['VOTE_COUNT'] = $this->getVotesCount();
        } else $this->error = 'VOTE_DISABLED';

        $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
        $uri = new \Bitrix\Main\Web\Uri($request->getRequestUri());
        $uri->addParams(array("VOTE_TOGGIE" => 'Y'));
        $this->arResult['VOTE_URL'] = $uri->getUri();

        if ($this->error == 'VOTE_DISABLED') {
            $this->arResult['ERROR']['TYPE'] = 'VOTE_DISABLED';
            $this->arResult['ERROR']['MSG'] = 'Голсование временно недоступно';
        }

        $this->includeComponentTemplate();
    }

    private function toggieVoteState() {
        $highvote_table = new HighvoteTable();
        $state = $this->vote_state = $this->getVoteState();

        if ($state) {
            //FIXME: Duplicate code
            $db_res = $highvote_table::getList([
                'select' => ['ID'],
                'filter' => ['ELEMENT_ID' => $_REQUEST['ID'], 'IP' => $this->user_ip],
                'limit' => 1,
            ]);
            if ($res = $db_res->fetch())
                $id = $res['ID'];

            $res = $highvote_table->delete($id);
            if (!$res->isSuccess()) $this->error = 'VOTE_DISABLED';
        } else if (isset($_REQUEST['ID']) && intval($_REQUEST['ID']) > 0) {
            $res = $highvote_table->add([
                'IP' => $this->user_ip,
                'ELEMENT_ID' => $_REQUEST['ID'],
            ]);

            if (!$res->isSuccess()) $this->error = 'VOTE_DISABLED';
        }
    }

    private function getVoteState() {

        if (empty($this->user_ip)) return false;

        $highvote_table = new HighvoteTable();
        $db_res = $highvote_table::getList([
            'select' => ['*'],
            'filter' => ['ELEMENT_ID' => $_REQUEST['ID'], 'IP' => $this->user_ip],
            'limit' => 1,
            'count_total' => true,
        ]);
        if ($db_res->getCount() == 1)
            return true;

        return false;

    }

    private function getVotesCount() {
        $highvote_table = new HighvoteTable();
        $dbRes = $highvote_table::getList([
            'select' => ['ID'],
            'filter' => ['ELEMENT_ID' => $_REQUEST['ID']],
            'count_total' => true,
        ]);

        return $dbRes->getCount();
    }
}