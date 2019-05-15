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
$_REQUEST['sortby'] = "new";
?>

<?$APPLICATION->IncludeComponent(
    "itsfera:blogforum.list",
    "themes",
    Array(
        "AJAX_MODE" => "N",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_SHADOW" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "CACHE_TIME" => "300",
        "CACHE_TYPE" => "A",
        "IBLOCK_ID" => "1",
        "IBLOCK_TYPE" => "-",
        "ONPAGE" => "10",
        "SHOW_BLOG" => "Y",
        "SHOW_FORUM" => "Y",
        "FORUM_IDS" => $arResult["PROPERTIES"]["T_FORUMS"]["VALUE"],
        "BLOG_IDS" => $arResult["PROPERTIES"]["T_BLOGS"]["VALUE"]
    )
);?>
