<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
use Bitrix\Main\UI\Extension;
Extension::load('ui.bootstrap4');
?>
<form id = "bx-certificateFormActivate" style = "max-width:800px;" method = "POST">
    <? if (isset($arResult['NOTICE'])): ?>
        <div class="alert alert-warning" role="alert">
            <?=$arResult['NOTICE']?>
        </div>
    <?endif;?>
    <div class="form-group">
        <label for="InputConfirmCode">Confirm code</label>
        <input type="text" class="form-control" name="InputConfirmCode" placeholder="Confirm code">
    </div>
    <br>
    <button type="submit" class="btn btn-primary">Получить скидку</button>
</form>
</div>