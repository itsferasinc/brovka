<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
* @var array $arParams
* @var array $arResult
* @var string $strErrorMessage
* @param CBitrixComponent $component
* @param CBitrixComponentTemplate $this
* @global CMain $APPLICATION
*/

    if($arParams["~MESSAGE"]['NEW_TOPIC'] == "Y"){
        $imgTypes = array("image/jpeg","image/jpg","image/png");
        $txt = getTextBetweenTags($arParams["~MESSAGE"]['POST_MESSAGE'], "IMG");
        $APPLICATION->AddHeadString('<meta property="og:title" content="' . $arParams['arResult']['TOPIC']['TITLE'] . '" />');
        $APPLICATION->AddHeadString('<meta property="og:type" content="website" />');
        $APPLICATION->AddHeadString('<meta property="og:description" content="' . strip_tags(preg_replace("/\r|\n/", "", $arParams["~MESSAGE"]['POST_MESSAGE_TEXT'])) . '" />');
        foreach ($txt as $img) {
            $skipDefaultLogo = true;
            $APPLICATION->AddHeadString('<meta property="og:image" content="' . $img . '" />');
        }
        foreach ($arParams["MESSAGE"]["FILES"] as $img)
            if(in_array($img["CONTENT_TYPE"],$imgTypes)) {
                $skipDefaultLogo = true;
                $APPLICATION->AddHeadString('<meta property="og:image" content="http://brovka.net' . $img["SRC"] . '" />');
            }
        if (count($txt) < 1 && !$skipDefaultLogo)
            $APPLICATION->AddHeadString('<meta property="og:image" content="http://brovka.net/local/templates/brovka/images/logo_op.png" />');
        $APPLICATION->AddHeadString('<meta property="og:url" content="' . $_SERVER['SCRIPT_URI'] . '" />'); //$arParams["~MESSAGE"]['URL']['MESSAGE']
    }


