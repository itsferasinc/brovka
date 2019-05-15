<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//$bg = cfile::getpath($arResult['BANNER_PROPERTIES']);

if($arResult["BANNER"]) {
    $frame = $this->createFrame()->begin("");
    ?>
    <div class="background-wrapper">
        <?= $arResult["BANNER"] ?>
    </div><?
    $frame->end();
}