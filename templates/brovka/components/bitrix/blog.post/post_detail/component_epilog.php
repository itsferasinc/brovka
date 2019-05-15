<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @var array $arParams
 * @var array $arResult
 * @var string $strErrorMessage
 * @param CBitrixComponent $component
 * @param CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 */

$txt = getTextBetweenTags($arResult['Post']['DETAIL_TEXT'], "IMG");
$txt2 = getTextBetweenNormalTags($arResult['Post']['DETAIL_TEXT'], "IMG");


if($USER->isadmin()){


}

$APPLICATION->AddHeadString('<meta property="og:title" content="' . $arResult['Post']['TITLE'] . '" />');
$APPLICATION->AddHeadString('<meta property="og:type" content="website" />');
$APPLICATION->AddHeadString('<meta property="og:description" content="' . strip_tags(preg_replace( "/\r|\n/", "", $arResult['Post']['textFormated'] )) . '" />');
foreach ($txt as $img) {
    $skipDefaultLogo = true;
    $APPLICATION->AddHeadString('<meta property="og:image" content="' . $img . '" />');
}
foreach ($txt2 as $imgId) {
    $res = CBlogImage::GetByID($imgId);
    $skipDefaultLogo = true;
    $APPLICATION->AddHeadString('<meta property="og:image" content="http://brovka.net' . cfile::getpath($res['FILE_ID']) . '" />');
}
if(count($txt) < 1 && !$skipDefaultLogo){
    $APPLICATION->AddHeadString('<meta property="og:image" content="http://brovka.net/local/templates/brovka/images/logo_op.png" />');
}
$APPLICATION->AddHeadString('<meta property="og:url" content="'.$_SERVER['SCRIPT_URI'].'" />');