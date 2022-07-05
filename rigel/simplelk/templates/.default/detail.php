<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<table class = "user-table">
    <thead>
    <tr>
        <th scope="col">Номер заказа</th>
        <th scope="col">Дата Оформления</th>
        <th scope="col">Отменен</th>
        <th scope="col">Статус</th>
    </tr>
    </thead>
    <tbody>
    <?foreach ($arResult['ORDERS'] as $order):?>
        <tr>
            <td><?=$order['ID']?></td>
            <td><?=$order['DATE_INSERT']->format('Y-M-d H:m:s');?></td>
            <td><?=($order['CANCELED'] == 'Y' ? 'Да' : 'Нет')?></td>
            <td><?=$order['STATUS_NAME']?></td>
        </tr>
    <?endforeach;?>
    </tbody>
</table>

<a href = '<?=$arResult["URL_TEMPLATES"]["list"];?>'><-- Назад</a>
