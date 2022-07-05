<?php
namespace Vitas\Highvote;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\TextField;

Loc::loadMessages(__FILE__);

/**
 * Class HighvoteTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> UF_VITAS_IP text optional
 * <li> UF_ELEMENT_ID int optional
 * </ul>
 *
 * @package Bitrix\Highvote
 **/

class HighvoteTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'vitas_highvote';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('HIGHVOTE_ENTITY_ID_FIELD')
                ]
            ),
            new TextField(
                'IP',
                [
                    'column_name' => 'UF_VITAS_IP',
                    'title' => Loc::getMessage('HIGHVOTE_ENTITY_UF_VITAS_IP_FIELD')
                ]
            ),
            new IntegerField(
                'ELEMENT_ID',
                [
                    'column_name' => 'UF_VITAS_ELEMENT_ID',
                    'title' => Loc::getMessage('HIGHVOTE_ENTITY_UF_VITAS_ELEMENT_ID_FIELD')
                ]
            ),
        ];
    }
}