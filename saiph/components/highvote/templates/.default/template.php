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
<div class="highvote">
    <form class = "highvote-form" method="post" action="<?=$arResult['VOTE_URL']?>">
        <span>Рейтинг: <?=$arResult['VOTE_COUNT']?></span>
        <a href = 'javascript:void(0)' class = "vote-button  <?=($arResult['VOTE_STATE'] ? "vote-button--disable" : "vote-button--up js-vote--up")?>">Проголосовать+</a>
        <a href = 'javascript:void(0)' class = "vote-button  <?=($arResult['VOTE_STATE'] ? "vote-button--down js-vote--down" : "vote-button--disable")?>">Проголосовать-</a>
    </form>
</div>

