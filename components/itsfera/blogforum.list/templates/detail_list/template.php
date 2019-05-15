<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<?
//foreach ($arResult['ITEMS'] as $i => $post){
while ($post = $arResult["RESULT"]->GetNext())
{
    switch ($post['TYPE']) {
        case 'forum':
            $APPLICATION->IncludeComponent(
                "bitrix:forum.topic.read",
                "topic",
                array(
                    "AJAX_TYPE" => "Y",
                    "ATTACH_MODE" => array(
                        0 => "NAME",
                    ),
                    "ATTACH_SIZE" => "90",
                    "CACHE_TIME" => "0",
                    "CACHE_TYPE" => "A",
                    "DATE_FORMAT" => "d.m.Y",
                    "DATE_TIME_FORMAT" => "d.m.Y H:i:s",
                    "FID" => $post["FORUM_ID"],
                    "IMAGE_SIZE" => "750",
                    "MESSAGES_PER_PAGE" => "1",
                    "MID" => $post["MID"],
                    "PAGE_NAVIGATION_SHOW_ALL" => "N",
                    "PAGE_NAVIGATION_TEMPLATE" => "",
                    "PAGE_NAVIGATION_WINDOW" => "11",
                    "RATING_ID" => "3",
                    "RATING_TYPE" => "standart_text",
                    "SEND_MAIL" => "E",
                    "SEO_USER" => "Y",
                    "SET_NAVIGATION" => "Y",
                    "SET_PAGE_PROPERTY" => "N",
                    "SET_TITLE" => "N",
                    "SHOW_FIRST_POST" => "N",
                    "SHOW_NAME_LINK" => "Y",
                    "SHOW_RATING" => "Y",
                    "SHOW_RSS" => "Y",
                    "SHOW_VOTE" => "N",
                    "TID" => $post["ID"],
                    "URL_TEMPLATES_INDEX" => "index.php",
                    "URL_TEMPLATES_LIST" => "forum#FID#/",
                    "URL_TEMPLATES_MESSAGE" => "/forum/forum#FID#/#TITLE_SEO#",
                    "URL_TEMPLATES_MESSAGE_MOVE" => "message_move.php?FID=#FID#&TID=#TID#&MID_ARRAY=#MID_ARRAY#",
                    "URL_TEMPLATES_MESSAGE_SEND" => "message_send.php?UID=#UID#",
                    "URL_TEMPLATES_PM_EDIT" => "pm_edit.php",
                    "URL_TEMPLATES_PROFILE_VIEW" => "profile_view.php?UID=#UID#",
                    "URL_TEMPLATES_READ" => "read.php?FID=#FID#&TID=#TID#",
                    "URL_TEMPLATES_RSS" => "rss.php?TYPE=#TYPE#&MODE=#MODE#&IID=#IID#",
                    "URL_TEMPLATES_SUBSCR_LIST" => "subscr_list.php?FID=#FID#",
                    "URL_TEMPLATES_TOPIC_MOVE" => "topic_move.php?FID=#FID#&TID=#TID#",
                    "URL_TEMPLATES_TOPIC_NEW" => "topic_new.php?FID=#FID#",
                    "URL_TEMPLATES_USER_POST" => "user_post.php?UID=#UID#&mode=#mode#",
                    "VOTE_TEMPLATE" => "light",
                    "WORD_LENGTH" => "50",
                    "COMPONENT_TEMPLATE" => "topic"
                ),
                false
            );
            break;
        case 'blog':
            $APPLICATION->IncludeComponent(
                "bitrix:blog.post",
                "post",
                array(
                    "BLOG_URL" => $post["URL"],
                    "BLOG_VAR" => "",
                    "CACHE_TIME" => "86400",
                    "CACHE_TYPE" => "A",
                    "DATE_TIME_FORMAT" => "d.m.Y H:i:s",
                    "ID" => $post["ID"],
                    "IMAGE_MAX_HEIGHT" => "800",
                    "IMAGE_MAX_WIDTH" => "750",
                    "PAGE_VAR" => "",
                    "PATH_TO_BLOG" => "",
                    "PATH_TO_BLOG_CATEGORY" => "",
                    "PATH_TO_POST_EDIT" => "",
                    "PATH_TO_SMILE" => "",
                    "PATH_TO_USER" => "",
                    "POST_PROPERTY" => array(
                    ),
                    "POST_VAR" => "id",
                    "RATING_TYPE" => "",
                    "SEO_USE" => "Y",
                    "SEO_USER" => "N",
                    "SET_NAV_CHAIN" => "N",
                    "SET_TITLE" => "N",
                    "SHOW_RATING" => "",
                    "USER_VAR" => "",
                    "COMPONENT_TEMPLATE" => "post"
                ),
                false
            );
            break;
    }
}

ob_start();
// буферизация пагинатора
$APPLICATION->IncludeComponent('bitrix:system.pagenavigation', 'modern', array(
    'NAV_RESULT' => $arResult["RESULT"],
    'BASE_LINK' => "/blogs/"
));
$arResult["NAV_STRING"] = ob_get_contents();
ob_end_clean();

echo $arResult["NAV_STRING"];

unset($arResult["RESULT"]);
unset($arResult["NAV_STRING"]);

$APPLICATION->IncludeComponent(
    "bitrix:main.pagenavigation",
    "modern",
    Array()
);

$arResult["NavQueryString"] = str_replace('&amp;','&',$arResult["NavQueryString"]);
$do = preg_match('/.*bxajaxid=(\S+).*/',$arResult["NavQueryString"],$bxajaxid);
?>
<script>
    var postList = <?=CUtil::PhpToJSObject($arResult)?>;
</script>

