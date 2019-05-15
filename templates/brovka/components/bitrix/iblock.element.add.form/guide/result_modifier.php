<?php


CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
$arSelect = Array("ID", "NAME", "IBLOCK_SECTION_ID");
$arFilter = Array("IBLOCK_ID"=>getIBlockIdByCode("thems"),"ACTIVE"=>"Y","INCLUDE_SUCSECTIONS"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement())
{
    $arFields = $ob->GetFields();

    switch($arFields['IBLOCK_SECTION_ID']) {
        case "8":
            $arResult['DICS']["FISHING_TYPE"][] = array("ID" => $arFields['ID'], "NAME" => $arFields['NAME']);
            break;
        case "9":
            $arResult['DICS']["FISH_TYPE"][] = array("ID" => $arFields['ID'], "NAME" => $arFields['NAME']);
            break;
        case "10":
            $arResult['DICS']["LOCATION_TYPE"][] = array("ID" => $arFields['ID'], "NAME" => $arFields['NAME']);
            break;
    }
}