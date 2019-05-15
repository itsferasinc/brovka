<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

$arTypesEx = CIBlockParameters::GetIBlockTypes(array("-"=>" "));

$arIBlocks=array();
$db_iblock = CIBlock::GetList(array("SORT"=>"ASC"), array("SITE_ID"=>$_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"]!="-"?$arCurrentValues["IBLOCK_TYPE"]:"")));
while($arRes = $db_iblock->Fetch())
	$arIBlocks[$arRes["ID"]] = $arRes["NAME"];


$arComponentParameters = array(
	"GROUPS" => array(

	),
	"PARAMETERS" => array(

		"IBLOCK_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => "Тип инфоблока",
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx,
			"REFRESH" => "Y",
		),

		"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => "Инфоблок",
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"DEFAULT" => '={$_REQUEST["ID"]}',
		),
        "ONPAGE" => array(
            "PARENT" => "BASE",
            "NAME" => "Постов на странице",
            "TYPE" => "TEXT",
            "DEFAULT" => '10',
        ),
        "SHOW_BLOG" => array(
            "PARENT" => "BASE",
            "NAME" => "Выводить блоги",
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),
        "SHOW_FORUM" => array(
            "PARENT" => "BASE",
            "NAME" => "Выводить форумы",
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),
		"CACHE_TIME"  =>  array("DEFAULT"=>300),

		/*"NDS" => array(
			"PARENT" => "BASE",
			"NAME" => "Ставка НДС",
			"TYPE" => "STRING",
			"DEFAULT" => "18",
		),*/
	
	),
);