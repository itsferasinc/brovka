<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<?
//foreach ($arResult['ITEMS'] as $i => $post)
while ($post = $arResult["RESULT"]->GetNext())
{
    switch ($post['TYPE']) {
        case 'forum':
            break;
        case 'blog':
            $APPLICATION->IncludeComponent(
                "itsfera:blog.post.drafts",
                "post",
                Array(
                    "BLOG_URL" => $post['URL'],
                    "BLOG_VAR" => '',
                    "CACHE_TIME" => "86400",
                    "CACHE_TYPE" => "A",
                    "DATE_TIME_FORMAT" => "d.m.Y H:i:s",
                    "ID" => $post['ID'],
                    "IMAGE_MAX_HEIGHT" => "600",
                    "IMAGE_MAX_WIDTH" => "600",
                    "PAGE_VAR" => "",
                    "PATH_TO_BLOG" => "",
                    "PATH_TO_BLOG_CATEGORY" => "",
                    "PATH_TO_POST_EDIT" => "",
                    "PATH_TO_SMILE" => "",
                    "PATH_TO_USER" => "",
                    "POST_PROPERTY" => array(),
                    "POST_VAR" => 'id',
                    "RATING_TYPE" => "",
                    "SEO_USE" => "Y",
                    "SEO_USER" => "N",
                    "SET_NAV_CHAIN" => "N",
                    "SET_TITLE" => "N",
                    "SHOW_RATING" => "",
                    "USER_VAR" => ""
                )
            );
            break;
    }
}

echo $arResult["NAV_STRING"];

unset($arResult["RESULT"]);
unset($arResult["NAV_STRING"]);

$arResult["NavQueryString"] = str_replace('&amp;','&',$arResult["NavQueryString"]);
$do = preg_match('/.*bxajaxid=(\S+).*/',$arResult["NavQueryString"],$bxajaxid);
?>
<script>
    var postList = <?=CUtil::PhpToJSObject($arResult)?>;
</script>

