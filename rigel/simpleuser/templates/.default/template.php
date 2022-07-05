<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
<form class = "js-user-form" method = "post" action="javascript:void(0);">
<table class="table">
    <thead>
    <tr>
        <th>ФИО</th>
        <th>Дата рождения</th>
        <th>Логин</th>
        <th>email</th>
    </tr>
    <tr>

        <td><input type="text" <?=$component->isFiltering('FILTER_NAME')?> name="FILTER_NAME"></td>
        <td><input type="text" <?=$component->isFiltering('FILTER_BIRTHDAY')?> name="FILTER_BIRTHDAY"></td>
        <td><input type="text" <?=$component->isFiltering('FILTER_LOGIN')?> name="FILTER_LOGIN"></td>
        <td><input type="text" <?=$component->isFiltering('FILTER_EMAIL')?> name="FILTER_EMAIL"></td>
    </tr>
    </thead>
    <tbody class = "js-user-form--content">
    <? foreach ($arResult['USERS'] as $user): ?>
        <tr>
            <td><? echo $user['FULL_NAME']?></td>
            <td><?= $user['BIRTHDAY'] ?></td>
            <td><?= $user['LOGIN'] ?></td>
            <td><?= $user['EMAIL'] ?></td>
        </tr>
    <? endforeach; ?>
    </tbody>
</table>
<a class = "button js-user-form--filter">Отправить</a>
</form>