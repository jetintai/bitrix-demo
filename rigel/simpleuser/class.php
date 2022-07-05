<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\Contract\Controllerable;


Loc::loadMessages(__FILE__);

class SimpleUser extends CBitrixComponent implements Controllerable
{

    private $vote_state = false;
    private $user_ip, $error;

    private $map = array(
        "FILTER_NAME" => 'FULL_NAME',
        "FILTER_BIRTHDAY" => "BIRTHDAY",
        "FILTER_LOGIN" => "LOGIN",
        "FILTER_EMAIL" => "EMAIL",
    );

    public function executeComponent()
    {

        $this->arResult['USERS'] = $this->getUsers();
        $this->includeComponentTemplate();
    }

    public function isFiltering($option): string
    {
        if (!in_array($this->map[$option], $this->arParams['FILTER_COLUMN']))
            return 'disabled';

        return '';
    }

    protected function getUsers($filter = array())
    {
        $db_res = \Bitrix\Main\UserTable::getList(array(
            'select' => array(
                'ID',
                'LOGIN',
                'EMAIL',
                'NAME',
                'LAST_NAME',
                'PERSONAL_BIRTHDAY',
                'BIRTHDAY',
                'FULL_NAME',
            ),
            'count_total' => true,
            'filter' => $filter,
            'runtime' => [
                new \Bitrix\Main\Entity\ExpressionField(
                    'FULL_NAME',
                    "CONCAT(NAME, ' ', LAST_NAME)"
                ),
                new \Bitrix\Main\Entity\ExpressionField(
                    'BIRTHDAY',
                    "DATE_FORMAT(PERSONAL_BIRTHDAY, '%%d %%M %%Y')"
                ),
            ]
            //'limit' => 3
        ));

        while ($res = $db_res->fetch()) {
            $res['PERSONAL_BIRTHDAY'] = $res['PERSONAL_BIRTHDAY']->format('d F Y');
            $users[] = $res;
        }

        return $users;
    }

    public function configureActions(): array
    {
        return [
            'filter' => [
                'prefilters' => []
            ]
        ];
    }


    public function filterAction($post)
    {
        try {
            $filter = array();
            foreach ($this->map as $key => $option) {
                if (!empty($post[$key])) {
                    $filter["%=${option}"] = "%${post[ $key ]}%";
                }

            }
            return $this->getUsers($filter);
        } catch (\Exception $e) {
            return 'Something is wrong, try again later.';
        }
    }

}