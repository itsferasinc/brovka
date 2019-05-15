<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Список форум тем + блог постов",
	"DESCRIPTION" => "",
	"SORT" => 100,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "itsfera",
		"SORT" => 200,
		"NAME" => "Компоненты IT-Сфера",
		"CHILD" => array(
			"ID" => "its_custom",
			"NAME" => "Настраиваемые",
			"SORT" => 10,
		)
	),
);

?>