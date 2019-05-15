<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;



$arComponentParameters = array(
	"GROUPS" => array(

	),
	"PARAMETERS" => array(

		"ID" => array(
			"PARENT" => "BASE",
			"NAME" => "ID юзера",
			"TYPE" => "STRING",
		),

		"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => "Инфоблок",
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"DEFAULT" => '={$_REQUEST["ID"]}',
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