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
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");

$guideIbId = getIBlockIdByCode("guide");
$arSelect = Array("ID", "NAME");
$arFilter = Array("IBLOCK_ID"=>$guideIbId, "ACTIVE"=>"Y");
$resEl = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($obEl = $resEl->GetNextElement())
{
    $arFields = $obEl->GetFields();
    $res = CIBlockElement::GetProperty($guideIbId, $arFields['ID'], "sort", "asc", array("CODE" => "FISHING_TYPE"));
    while ($ob = $res->GetNext())
        if((int)$ob['VALUE']>0)
            $FISHING_TYPE[] = $ob['VALUE'];
    $res = CIBlockElement::GetProperty($guideIbId, $arFields['ID'], "sort", "asc", array("CODE" => "LOCATION_TYPE"));
    while ($ob = $res->GetNext())
        if((int)$ob['VALUE']>0)
            $LOCATION_TYPE[] = $ob['VALUE'];
    $res = CIBlockElement::GetProperty($guideIbId, $arFields['ID'], "sort", "asc", array("CODE" => "FISH_TYPE"));
    while ($ob = $res->GetNext())
        if((int)$ob['VALUE']>0)
            $FISH_TYPE[] = $ob['VALUE'];
    $res = CIBlockElement::GetProperty($guideIbId, $arFields['ID'], "sort", "asc", array("CODE" => "LOCATION"));
    while ($ob = $res->GetNext())
        if((int)$ob['VALUE']>0)
            $LOCATION[] = $ob['VALUE'];
}
$guideIbId = getIBlockIdByCode("guide");
$arSelect = Array("ID", "NAME");
$arFilter = Array("IBLOCK_ID"=>getIBlockIdByCode("thems"), "ACTIVE"=>"Y");
$resEl = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($obEl = $resEl->GetNextElement())
{
    $arFields = $obEl->GetFields();
    if(in_array($arFields['ID'],$FISHING_TYPE))
        $DICS[30][] = array("ID"=>$arFields['ID'],"NAME"=>$arFields['NAME']);
    if(in_array($arFields['ID'],$LOCATION_TYPE))
        $DICS[32][] = array("ID"=>$arFields['ID'],"NAME"=>$arFields['NAME']);
    if(in_array($arFields['ID'],$FISH_TYPE))
        $DICS[33][] = array("ID"=>$arFields['ID'],"NAME"=>$arFields['NAME']);
}
$db_vars = CSaleLocation::GetList(
    array(
        "SORT" => "ASC",
        "COUNTRY_NAME_LANG" => "ASC",
        "CITY_NAME_LANG" => "ASC"
    ),
    array("LID" => LANGUAGE_ID, "ID" => $LOCATION),
    false,
    false,
    array()
);
while ($vars = $db_vars->Fetch()){
    $DICS[31][] = array("ID"=>$vars['ID'],"NAME"=>$vars['REGION_NAME']);
}
$filterNames = array(
    30 => 'FISHING_TYPE',
    31 => 'LOCATION',
    32 => 'LOCATION_TYPE',
    33 => 'FISH_TYPE',
);

$location = ITSGlobal::getLocationByFacet(3);


$arLocs = CSaleLocation::GetByID(21, LANGUAGE_ID);
echo'<pre>';print_r($arLocs);echo"</pre>";
$arLocs = CSaleLocation::GetByID(54, LANGUAGE_ID);
echo'<pre>';print_r($arLocs);echo"</pre>";
$arLocs = CSaleLocation::GetByID(66, LANGUAGE_ID);
echo'<pre>';print_r($arLocs);echo"</pre>";
?>
<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get">
	<?foreach($arResult["ITEMS"] as $arItem):
		if(array_key_exists("HIDDEN", $arItem)):
			echo $arItem["INPUT"];
		endif;
	endforeach;?>
	<table class="data-table" cellspacing="0" cellpadding="2">
	<thead>
		<tr>
			<td colspan="2" align="center"><?=GetMessage("IBLOCK_FILTER_TITLE")?></td>
		</tr>
	</thead>
	<tbody>
		<?foreach($arResult["ITEMS"] as $key=>$arItem):?>
			<?if(!array_key_exists("HIDDEN", $arItem)):
                $id = (int)str_replace("PROPERTY_","",$key);?>
				<tr>
					<td valign="top"><?=$arItem["NAME"]?>:</td>
					<td valign="top">
                        <select name="arrFilter_pf[<?=$filterNames[$id]?>][]" class="select2guides" multiple>
                        <?foreach ($DICS[$id] as $val){?>
                            <option value="<?=$val['ID']?>"><?=$val['NAME']?></option>
                        <?}?>
                        </select>
                    </td>
				</tr>
			<?endif?>
		<?endforeach;?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="2">
				<input type="submit" name="set_filter" value="<?=GetMessage("IBLOCK_SET_FILTER")?>" /><input type="hidden" name="set_filter" value="Y" />&nbsp;&nbsp;<input type="submit" name="del_filter" value="<?=GetMessage("IBLOCK_DEL_FILTER")?>" /></td>
		</tr>
	</tfoot>
	</table>
</form>
