<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

use Bitrix\Main\UI\Extension;
use Bitrix\Main\Type\DateTime;

Extension::load('ui.bootstrap4');
?>
    <div class="container">
        <div class="row">
            <? if (isset($arResult['NOTICE'])): ?>
                <div class="alert alert-warning" role="alert">
                    <?=$arResult['NOTICE']?>
                </div>
            <?endif;?>
        </div>
    </div>

<?if (isset($arResult['CERTIFICATE'])):
    $today = ( new DateTime() )->getTimestamp();
    $fromDiff = $today - ( new DateTime($arResult['CERTIFICATE']['ACTIVE_FROM']) )->getTimestamp();
    $toDiff = $today - ( new DateTime($arResult['CERTIFICATE']['ACTIVE_TO']) )->getTimestamp();

    if ($toDiff >= 0)
        $arResult['CERTIFICATE']['DOP_INFO'] = 'Время действия сертфикиата истекло';
    else if ($fromDiff < 0)
        $arResult['CERTIFICATE']['DOP_INFO'] = 'Время действия сертификата не началось';
    else if ($arResult['CERTIFICATE']['CERT_ACTIVE'] == '1')
        $arResult['CERTIFICATE']['DOP_INFO'] = 'Cертификат успешно активирован';
    else if ($arResult['CERTIFICATE']['ACTIVE'] == 'Y')
        $arResult['CERTIFICATE']['DOP_INFO'] = 'Сертифкиат заблокирован менеджером';

?>

<div class="container">
    <div class="row">
        <div class="col-md-12">Номер сертификата <?=$arResult['CERTIFICATE']['CODE']?></div>
        <div class="col-md-12">Текущий статус сертфикиата
            <?=($arResult['CERTIFICATE']['CERT_ACTIVE_VALUE'] == 1 ? 'Активирован' : 'Не активирован');?></div>
        <div class="col-md-12">Дополнительная информация <?=$arResult['CERTIFICATE']['DOP_INFO']?></div>
    </div>
</div>

<?php endif; ?>