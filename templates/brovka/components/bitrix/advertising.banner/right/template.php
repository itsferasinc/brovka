<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["BANNER"]) {
    $frame = $this->createFrame()->begin("");
    ?>
    <div class="advertisement">
    <?= $arResult["BANNER"] ?>
    </div><?
    $frame->end();
}