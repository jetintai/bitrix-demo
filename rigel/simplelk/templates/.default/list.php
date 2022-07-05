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
    <caption>Список пользователей по заказам</caption>
    <thead>
    <tr>
        <th scope="col">ИД</th>
        <th scope="col">Актвиность</th>
        <th scope="col">Логин</th>
        <th scope="col">Фото</th>
        <th scope="col">Количество заказов</th>
    </tr>
    </thead>
    <tbody>
    <?foreach ($arResult['USERS'] as $user):?>
        <tr onclick="document.location = '<?=$user['ORDERS_PAGE_URL']?>'">
            <td><?=$user['ID']?></td>
            <td><?=($user['ACTIVE'] == 'Y' ? 'Активен' : 'Заблокирован')?></td>
            <td><?=$user['LOGIN']?></td>
            <td><img src = "<?=$user['PERSONAL_PHOTO']['src']?>"</td>
            <td><?=$user['ORDERS_COUNT']?></td>
        </tr>
    <?endforeach;?>
    </tbody>
</table>

<?
$APPLICATION->IncludeComponent(
    "bitrix:main.pagenavigation",
    "",
    array(
        "NAV_OBJECT" => $arResult['NAV_OBJECT'],
        "SEF_MODE" => "Y",
        "SHOW_COUNT" => "N",
    ),
    false
);
?>
