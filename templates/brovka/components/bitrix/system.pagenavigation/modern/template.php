<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
GLOBAL $replaceBasePath;
if($replaceBasePath)
    $arResult["sUrlPath"] = $replaceBasePath;
if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}
?>

<div class="pagination">
<?

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>

<?
if($arResult["bDescPageNumbering"] === true):

    $bFirst = true;
    if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
        if($arResult["bSavePage"]):
            ?>
            <a class="next-tab" href="<?=$arResult["sUrlPath"]?>page<?=($arResult["NavPageNomer"]+1)?>/"><?=GetMessage("nav_prev")?></a>
            <?
        else:
            if ($arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1) ):
                ?>
                <a class="next-tab" href="<?=$arResult["sUrlPath"]?>"><?=GetMessage("nav_prev")?></a>
                <?
            else:
                ?>
                <a class="next-tab" href="<?=$arResult["sUrlPath"]?>page<?=($arResult["NavPageNomer"]+1)?>/"><?=GetMessage("nav_prev")?></a>
                <?
            endif;
        endif;

        if ($arResult["nStartPage"] < $arResult["NavPageCount"]):
            $bFirst = false;
            if($arResult["bSavePage"]):
                ?>
                <a class="page-tab" href="<?=$arResult["sUrlPath"]?>page<?=$arResult["NavPageCount"]?>/">1</a>
                <?
            else:
                ?>
                <a class="page-tab" href="<?=$arResult["sUrlPath"]?>">1</a>
                <?
            endif;
            if ($arResult["nStartPage"] < ($arResult["NavPageCount"] - 1)):
                ?>
                <a class="page-tab" href="<?=$arResult["sUrlPath"]?>page<?=intVal($arResult["nStartPage"] + ($arResult["NavPageCount"] - $arResult["nStartPage"]) / 2)?>/">...</a>
                <?
            endif;
        endif;
    endif;
    do
    {
        $NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;

        if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
            ?>
            <span class="page-tab"><?=$NavRecordGroupPrint?></span>
            <?
        elseif($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):
            ?>
            <a href="<?=$arResult["sUrlPath"]?>" class="page-tab"><?=$NavRecordGroupPrint?></a>
            <?
        else:
            ?>
            <a href="<?=$arResult["sUrlPath"]?>page<?=$arResult["nStartPage"]?>/" class="page-tab"><?=$NavRecordGroupPrint?></a>
            <?
        endif;

        $arResult["nStartPage"]--;
        $bFirst = false;
    } while($arResult["nStartPage"] >= $arResult["nEndPage"]);

    if ($arResult["NavPageNomer"] > 1):
        if ($arResult["nEndPage"] > 1):
            if ($arResult["nEndPage"] > 2):
                ?>
                <a class="page-tab" href="<?=$arResult["sUrlPath"]?>page<?=round($arResult["nEndPage"] / 2)?>/">...</a>
                <?
            endif;
            ?>
            <a class="page-tab" href="<?=$arResult["sUrlPath"]?>page1/"><?=$arResult["NavPageCount"]?></a>
            <?
        endif;

        ?>
        <a class="next-tab"href="<?=$arResult["sUrlPath"]?>page<?=($arResult["NavPageNomer"]-1)?>/"><?=GetMessage("nav_next")?></a>
        <?
    endif;

else:
	$bFirst = true;

	if ($arResult["NavPageNomer"] > 1):
		if($arResult["bSavePage"]):
?>
			<a class="next-tab" href="<?=$arResult["sUrlPath"]?>page<?=($arResult["NavPageNomer"]-1)?>/"><?=GetMessage("nav_prev")?></a>
<?
		else:
			if ($arResult["NavPageNomer"] > 2):
?>
			<a class="next-tab" href="<?=$arResult["sUrlPath"]?>page<?=($arResult["NavPageNomer"]-1)?>/"><?=GetMessage("nav_prev")?></a>
<?
			else:
?>
			<a class="next-tab" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=GetMessage("nav_prev")?></a>
<?
			endif;

		endif;

		if ($arResult["nStartPage"] > 1):
			$bFirst = false;
			if($arResult["bSavePage"]):
?>
			<a class="page-tab" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1">1</a>
<?
			else:
?>
			<a class="page-tab" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a>
<?
			endif;
			if ($arResult["nStartPage"] > 2):
/*?>
			<span class="modern-page-dots">...</span>
<?*/
?>
			<a class="page-tab" href="<?=$arResult["sUrlPath"]?>page<?=round($arResult["nStartPage"] / 2)?>/">...</a>
<?
			endif;
		endif;
	endif;

	do
	{
		if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
?>
            <a class="page-tab active-page" href="javascript:void(0)"><?=$arResult["nStartPage"]?></a>

<?
		elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):
?>
		<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="page-tab <?=($bFirst ? "modern-page-first" : "")?>"><?=$arResult["nStartPage"]?></a>
<?
		else:
?>
		<a href="<?=$arResult["sUrlPath"]?>page<?=$arResult["nStartPage"]?>/"<?
			?> class="page-tab <?=($bFirst ? "modern-page-first" : "")?>"><?=$arResult["nStartPage"]?></a>
<?
		endif;
		$arResult["nStartPage"]++;
		$bFirst = false;
	} while($arResult["nStartPage"] <= $arResult["nEndPage"]);

	if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
		if ($arResult["nEndPage"] < $arResult["NavPageCount"]):
			if ($arResult["nEndPage"] < ($arResult["NavPageCount"] - 1)):
/*?>
		<span class="modern-page-dots">...</span>
<?*/
?>
		<a class="page-tab" href="<?=$arResult["sUrlPath"]?>page<?=round($arResult["nEndPage"] + ($arResult["NavPageCount"] - $arResult["nEndPage"]) / 2)?>/">...</a>
<?
			endif;
?>
		<a class="page-tab" href="<?=$arResult["sUrlPath"]?>page<?=$arResult["NavPageCount"]?>/"><?=$arResult["NavPageCount"]?></a>
<?
		endif;
?>
		<a class="next-tab" href="<?=$arResult["sUrlPath"]?>page<?=($arResult["NavPageNomer"]+1)?>/"><?=GetMessage("nav_next")?></a>
<?
	endif;
endif;
/*
if ($arResult["bShowAll"]):
	if ($arResult["NavShowAll"]):
?>
		<a class="modern-page-pagen" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=0"><?=GetMessage("nav_paged")?></a>
<?
	else:
?>
		<a class="modern-page-all" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=1"><?=GetMessage("nav_all")?></a>
<?
	endif;
endif
*/
?>
</div>