<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
use Bitrix\Main\UI\Extension;
Extension::load('ui.bootstrap4');
?>
<form id = "bx-certificateForm" style = "max-width:800px;" method = "POST">
    <? if (isset($arResult['NOTICE'])): ?>
        <div class="alert alert-warning" role="alert">
            <?=$arResult['NOTICE']?>
        </div>
    <?endif;?>
        <div class="form-group">
            <label for="InputCertificateCode">Certificate code</label>
            <input type="text" class="form-control" name="InputCertificateCode" placeholder="Certificate code">
        </div>
        <div class="form-group">
            <label for="InputEmail">Email address</label>
            <input type="text" class="form-control" name="InputEmail" aria-describedby="emailHelp" placeholder="Enter email">
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
            <input name="captcha_code" value="<?=$arResult['CAPTCHA_CODE'];?>" type="hidden">
            <input class="form-control" id="captcha_word" name="captcha_word" type="text">
            <br>
        </div>
        <div class = "form-group">
            <img src="/bitrix/tools/captcha.php?captcha_code=<?=$arResult['CAPTCHA_CODE'];?>">
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>