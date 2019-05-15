<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="content-block-links">

    <?foreach($arResult["ITEMS"] as $arItem):?>
	 <a class="link-way" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
    <?endforeach;?>

</div>
