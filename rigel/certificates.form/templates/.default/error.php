<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
use Bitrix\Main\UI\Extension;
Extension::load('ui.bootstrap4');
?>

<div class="alert alert-danger" role="alert">
    Произошла системная ошибка
</div>
<div class="col-md-12">
    <?=$arResult['Exception'];?>
</div>
