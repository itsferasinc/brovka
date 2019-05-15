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
<div class="news-list">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?
    ob_start();
    // буферизация пагинатора
    $APPLICATION->IncludeComponent('bitrix:system.pagenavigation', 'modern', array(
        'NAV_RESULT' => $arResult["NAV_RESULT"],
        'BASE_LINK' => "/guides/"
    ));
    $arResult["NAV_STRING"] = ob_get_contents();
    ob_end_clean();
    echo $arResult["NAV_STRING"];
    ?>
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    //echo'<pre>';print_r($arItem['DISPLAY_PROPERTIES']);echo"</pre>";
	//echo'<pre>';print_r($arItem['PROPERTIES']['FISHING_TYPE']['VALUE']);echo"</pre>";
    //echo'<pre>';print_r($arItem['PROPERTIES']['FISH_TYPE']['VALUE']);echo"</pre>";
	?>

	<div class="guid-item row" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<div class="col-xs-3">
			<a href="<?=$arItem['DETAIL_PAGE_URL'];?>">
				<img alt="<?=$arItem['PREVIEW_PICTURE']['ALT'];?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE'];?>" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"];?>" class="guid-avatar">
			</a>	
		</div>
		<div class="col-xs-9">
			<div class="guid-description">
				<div class="guid-item-description">
					<h2><a class="name-link" href="<?=$arItem['DETAIL_PAGE_URL'];?>"><?=$arItem["NAME"];?></a></h2>
					<?/*<p class="h4">Авторитет на сайте: 324234</p>*/?>
					<p><?=$arItem["PREVIEW_TEXT"];?></p>
				</div>
				<div class="content-block-links fishing-way flaticon-anchor">
					<?foreach($arItem['DISPLAY_PROPERTIES']['FISHING_TYPE']['LINK_ELEMENT_VALUE'] as $fishingType):?>
					<a class="link-way" href="/guides/?arrFilter_30=<?=abs(crc32($fishingType['ID']))?>&set_filter=Найти<?//=$fishingType['DETAIL_PAGE_URL'];?>"><?=$fishingType['NAME'];?></a>
					<?endforeach;?>
				</div>
				<div class="content-block-links fish-type flaticon-fish">
					<?foreach($arItem['DISPLAY_PROPERTIES']['FISH_TYPE']['LINK_ELEMENT_VALUE'] as $fishingType):?>
					<a class="link-way" href="/guides/?arrFilter_33=<?=abs(crc32($fishingType['ID']))?>&set_filter=Найти<?//=$fishingType['DETAIL_PAGE_URL'];?>"><?=$fishingType['NAME'];?></a>
					<?endforeach;?>
				</div>
				<div class="guid-more">
					<a class="dashed-link" href="<?=$arItem['DETAIL_PAGE_URL'];?>">Подробнее</a>
				</div>
			</div>
		</div>
	</div>
	<div class="separator"></div>
<?endforeach;?>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
    <?
    ob_start();
    // буферизация пагинатора
    $APPLICATION->IncludeComponent('bitrix:system.pagenavigation', 'modern', array(
        'NAV_RESULT' => $arResult["NAV_RESULT"],
        'BASE_LINK' => "/guides/"
    ));
    $arResult["NAV_STRING"] = ob_get_contents();
    ob_end_clean();
    echo $arResult["NAV_STRING"];
    ?>
<?endif;?>
</div>

	<?/*
	<p class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
			<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img
						class="preview_picture"
						border="0"
						src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
						width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
						height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
						alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
						title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
						style="float:left"
						/></a>
			<?else:?>
				<img
					class="preview_picture"
					border="0"
					src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
					width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
					height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
					alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
					title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
					style="float:left"
					/>
			<?endif;?>
		<?endif?>
		<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
			<span class="news-date-time"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
		<?endif?>
		<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
			<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><b><?echo $arItem["NAME"]?></b></a><br />
			<?else:?>
				<b><?echo $arItem["NAME"]?></b><br />
			<?endif;?>
		<?endif;?>
		<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
			<?echo $arItem["PREVIEW_TEXT"];?>
		<?endif;?>
		<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
			<div style="clear:both"></div>
		<?endif?>
		<?foreach($arItem["FIELDS"] as $code=>$value):?>
			<small>
			<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
			</small><br />
		<?endforeach;?>
		<?foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
			<small>
			<?=$arProperty["NAME"]?>:&nbsp;
			<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
				<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
			<?else:?>
				<?=$arProperty["DISPLAY_VALUE"];?>
			<?endif?>
			</small><br />
		<?endforeach;?>
	</p>
	*/?>