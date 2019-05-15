<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?><?
/**
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param string $componentName
 * @param CBitrixComponent $this
 */

/*************** Default data **************************************/
$arParams["iIndex"] = $iIndex = rand();
$message = ($_SERVER['REQUEST_METHOD'] == "POST" ? $_POST["message_id"] : $_GET["message_id"]);
$message = (is_array($message) ? $message : array($message));

$arUserSettings = array("first_post" => "show");
if ($arParams["SHOW_FIRST_POST"] == "Y" && $GLOBALS["USER"]->IsAuthorized()) {
    require_once($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/classes/" . strToLower($GLOBALS["DB"]->type) . "/favorites.php");
    $arUserSettings = CUserOptions::GetOption("forum", "default_template", "");
    $arUserSettings = (CheckSerializedData($arUserSettings) ? @unserialize($arUserSettings) : array());

    $arUserSettings["first_post"] = ($arUserSettings["first_post"] == "hide" ? "hide" : "show");
}
$bShowedHeader = false;

$arAuthorId = array();
$arPostId = array();
$arTopicId = array();
$arRatingResult = array();
$arRatingVote = array();
if ($arParams["SHOW_RATING"] == 'Y') {
    $tmp = (!empty($arResult["MESSAGE_FIRST"]) ?
        (array($arResult["MESSAGE_FIRST"]["ID"] => $arResult["MESSAGE_FIRST"]) + $arResult["MESSAGE_LIST"]) : $arResult["MESSAGE_LIST"]);
    foreach ($tmp as $res) {
        $arAuthorId[] = $res['AUTHOR_ID'];
        if ($res['NEW_TOPIC'] == "Y")
            $arTopicId[] = $res['TOPIC_ID'];
        else
            $arPostId[] = $res['ID'];
    }
    if (!empty($arAuthorId)) {
        foreach ($arParams["RATING_ID"] as $key => $ratingId) {
            $arParams["RATING_ID"][$key] = intval($ratingId);
            $arRatingResult[$arParams["RATING_ID"][$key]] = CRatings::GetRatingResult($arParams["RATING_ID"][$key], array_unique($arAuthorId));
        }
    }

    if (!empty($arPostId))
        $arRatingVote['FORUM_POST'] = CRatings::GetRatingVoteResult('FORUM_POST', $arPostId);

    if (!empty($arTopicId))
        $arRatingVote['FORUM_TOPIC'] = CRatings::GetRatingVoteResult('FORUM_TOPIC', $arTopicId);
}
/*************** Default data **************************************/
if (!empty($arResult["ERROR_MESSAGE"])):
    ?>
    <div class="forum-note-box forum-note-error">
        <div class="forum-note-box-text"><?= ShowError($arResult["ERROR_MESSAGE"], "forum-note-error"); ?></div>
    </div>
    <?
endif;
if (!empty($arResult["OK_MESSAGE"])):
    ?>
    <div class="forum-note-box forum-note-success">
        <div class="forum-note-box-text"><?= ShowNote($arResult["OK_MESSAGE"], "forum-note-success") ?></div>
    </div>
    <?
endif;
foreach ($arResult["MESSAGE_LIST"] as $res)
    $url = $res['message_link'];
    $urlToAuthor = "/people/user/".$arResult['TOPIC']['USER_START_ID']."/profile/";
    $arResult['AuthorName'] = ITSGlobal::getUserName($arResult['TOPIC']['USER_START_ID']);
    ?>

    <div>
        <div class="block forumblogpost" data-id="<?=$arResult["TOPIC"]["ID"]?>">
            <h2><a href="<?=$url?>"><?=trim($arResult["TOPIC"]["TITLE"]) ?></a></h2>
            <div class="info">
                <i class="flaticon-time"></i>
                <p class="h4"><?=$arResult["TOPIC"]["START_DATE"]?></p>
                <i class="flaticon-man"></i>
                <p class="h4">Автор: <a class="name-link" href="<?=$urlToAuthor?>"><?=$arResult['AuthorName']//$arResult['TOPIC']['USER_START_NAME']?></a></p>
                <i class="flaticon-bubble"></i>
                <p class="h4">Комментариев: <a class="name-link" href="<?=$url?>"><?=$arResult["TOPIC"]["POSTS"]?></a></p>
                <i class="flaticon-eye"></i>
                <p class="h4">Просмотров: <?=$arResult["TOPIC"]["VIEWS"]?></p>
                <i class="flaticon-eye"></i>
                <p class="h4">Рейтинг: <?=(int)$arResult["RATING"]["TOTAL_VALUE"]?></p>

            </div>
            <div class="text-body">
                <?

                if (!empty($arResult["MESSAGE_LIST"])) {
                    $iCount = 0;
                    foreach ($arResult["MESSAGE_LIST"] as $res) {
                        //echo'<pre>';print_r($res);echo"</pre>";
                        echo $res['POST_MESSAGE_TEXT'];
                        /*?><? $GLOBALS["APPLICATION"]->IncludeComponent(
                            "bitrix:forum.message.template", "",
                            Array(
                                "MESSAGE" => $res + array("CHECKED" => (in_array($res["ID"], $message) ? "Y" : "N")),
                                "ATTACH_MODE" => $arParams["ATTACH_MODE"],
                                "ATTACH_SIZE" => $arParams["ATTACH_SIZE"],
                                "COUNT" => count($arResult["MESSAGE_LIST"]),
                                "NUMBER" => $iCount,
                                "SEO_USER" => $arParams["SEO_USER"],
                                "SHOW_RATING" => $arParams["SHOW_RATING"],
                                "RATING_ID" => $arParams["RATING_ID"],
                                "RATING_TYPE" => $arParams["RATING_TYPE"],
                                "arRatingVote" => $arRatingVote,
                                "arRatingResult" => $arRatingResult,
                                "arResult" => $arResult,
                                "arParams" => $arParams
                            ),
                            (($this && $this->__component && $this->__component->__parent) ? $this->__component->__parent : null),
                            array("HIDE_ICONS" => "Y")
                        ); ?><?*/
                    }
                }?>
                </div>

            <?GLOBAL $themes; $themes = array();
            $themes['PROPERTY_T_FORUMS'] = $arResult["TOPIC"]["ID"];?>
            <?$APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "themes",
                Array(
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",
                    "ADD_SECTIONS_CHAIN" => "N",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_ADDITIONAL" => "",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "Y",
                    "CACHE_TIME" => "36000000",
                    "CACHE_TYPE" => "A",
                    "CHECK_DATES" => "Y",
                    "DETAIL_URL" => "",
                    "DISPLAY_BOTTOM_PAGER" => "N",
                    "DISPLAY_DATE" => "N",
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PICTURE" => "N",
                    "DISPLAY_PREVIEW_TEXT" => "N",
                    "DISPLAY_TOP_PAGER" => "N",
                    "FIELD_CODE" => array("", ""),
                    "FILTER_NAME" => "themes",
                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                    "IBLOCK_ID" => "3",
                    "IBLOCK_TYPE" => "base",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                    "INCLUDE_SUBSECTIONS" => "N",
                    "MESSAGE_404" => "",
                    "NEWS_COUNT" => "99",
                    "PAGER_BASE_LINK_ENABLE" => "N",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_TEMPLATE" => ".default",
                    "PAGER_TITLE" => "Новости",
                    "PARENT_SECTION" => "",
                    "PARENT_SECTION_CODE" => "",
                    "PREVIEW_TRUNCATE_LEN" => "",
                    "PROPERTY_CODE" => array("T_FORUMS", ""),
                    "SET_BROWSER_TITLE" => "N",
                    "SET_LAST_MODIFIED" => "N",
                    "SET_META_DESCRIPTION" => "N",
                    "SET_META_KEYWORDS" => "N",
                    "SET_STATUS_404" => "N",
                    "SET_TITLE" => "N",
                    "SHOW_404" => "N",
                    "SORT_BY1" => "ACTIVE_FROM",
                    "SORT_BY2" => "SORT",
                    "SORT_ORDER1" => "DESC",
                    "SORT_ORDER2" => "ASC",
                    "STRICT_SECTION_CHECK" => "N"
                )
            );?>
            <?if(CSite::InGroup( array(1,10))){?>
                <p><a href="/local/ajax/themes.php?id=<?=$arResult["TOPIC"]["ID"]?>&type=forum" class="fancybox">Указать темы</a></p>
                <button class="hide-button" onclick="topic_hide_index(<?=$arResult["TOPIC"]["ID"]?>,'forum',$(this))" <?if(in_array("f".$arResult["TOPIC"]["ID"], $arResult['FAVORITES'])){?>style="display: none"<?}?>>
                    <span>Убрать с главной</span>
                </button>
                <button class="hide-button" onclick="topic_unhide_index(<?=$arResult["TOPIC"]["ID"]?>,'forum',$(this))" <?if(in_array("f".$arResult["TOPIC"]["ID"], $arResult['FAVORITES'])){?>style="display: block"<?}?>>
                    <span>Вернуть на главную</span>
                </button>
            <?}?>

            <div class="separator"></div>
            <div class="content-block-footer">
                <button class="plus-button" onclick="upvote(<?=$arResult["TOPIC"]["ID"]?>,'forum')">
                    <i class="flaticon-plus"></i>
                    <span><?=(int)$arResult['RATING']['TOTAL_POSITIVE_VOTES']?></span>
                </button>
                <button class="minus-button" onclick="downvote(<?=$arResult["TOPIC"]["ID"]?>,'forum')">
                    <i class="flaticon-minus"></i>
                    <span><?=(int)$arResult['RATING']['TOTAL_NEGATIVE_VOTES']?></span>
                </button>
                <button class="favorite-button" onclick="favorites_add(<?=$arResult["TOPIC"]["ID"]?>,'forum',$(this))" <?if(in_array("f".$arResult["TOPIC"]["ID"], $arResult['FAVORITES'])){?>style="display: none"<?}?>>
                    <i class="flaticon-star"></i>
                    <span>Добавить в избранное</span>
                </button>
                <button class="favorite-button favorite-active" onclick="favorites_del(<?=$arResult["TOPIC"]["ID"]?>,'forum',$(this))" <?if(in_array("f".$arResult["TOPIC"]["ID"], $arResult['FAVORITES'])){?>style="display: block"<?}?>>
                    <i class="flaticon-star"></i>
                    <span>Избранное</span>
                </button>
                <div class="share">
                    <span>ПОДЕЛИТЬСЯ:</span>
                    <div class="ya-share2" data-services="vkontakte,twitter,facebook,gplus,odnoklassniki" data-counter data-url="http://brovka.net<?=$url?>"></div>
                </div>
            </div>
            <?/*if($arResult["USER"]["RIGHTS"]["MODERATE"] == "Y" && $iCount <= 1) {?>
                <form class="forum-form" action="<?= POST_FORM_ACTION_URI ?>" method="POST" onsubmit="return Validate(this)"
                      name="MESSAGES_<?= $arParams["iIndex"] ?>" id="MESSAGES_<?= $arParams["iIndex"] ?>">
                    <div>
                        <?= bitrix_sessid_post() ?>
                        <input type="hidden" name="type" value="messages"/>
                        <input type="hidden" name="PAGE_NAME" value="read"/>
                        <input type="hidden" name="FID" value="<?= $arParams["FID"] ?>"/>
                        <input type="hidden" name="TID" value="<?= $arParams["TID"] ?>"/>
                        <input type="hidden" name="ACTION" value=""/>
                        <div class="forum-post-moderate">
                            &nbsp;&nbsp;<span class="forum-footer-option forum-footer-selectall forum-footer-option-first">
                                <noindex>
                                    <a rel="nofollow" href="javascript:void(0);" onclick="SelectPosts('<?=$arParams["iIndex"]?>');" name=""><?= GetMessage("F_SELECT_ALL") ?></a>
                                </noindex>
                            </span>
                        </div>
                        <div class="forum-post-moderate">
                            <select name="ACTION_MESSAGE">
                                <option value=""><?= GetMessage("F_MANAGE_MESSAGES") ?></option>
                                <option value="HIDE"><?= GetMessage("F_HIDE_MESSAGES") ?></option>
                                <option value="SHOW"><?= GetMessage("F_SHOW_MESSAGES") ?></option>
                                <option value="MOVE"><?= GetMessage("F_MOVE_MESSAGES") ?></option>
                                <?
                                if ($arResult["USER"]["RIGHTS"]["EDIT"] == "Y"):
                                    ?>
                                    <option value="DEL"><?= GetMessage("F_DELETE_MESSAGES") ?></option>
                                    <?
                                endif;
                                ?>
                            </select>&nbsp;<input
                                onmousedown="this.form.type.value='messages';this.form.ACTION.value=this.form.ACTION_MESSAGE.value;"
                                <?
                                ?>type="submit" value="OK"/>
                        </div>
                        <div class="forum-topic-moderate">
                            <select name="ACTION_TOPIC">
                                <option value=""><?= GetMessage("F_MANAGE_TOPIC") ?></option>
                                <option
                                    value="<?= ($arResult["TOPIC"]["APPROVED"] == "Y" ? "HIDE_TOPIC" : "SHOW_TOPIC") ?>"><?
                                    ?><?= ($arResult["TOPIC"]["APPROVED"] == "Y" ? GetMessage("F_HIDE_TOPIC") : GetMessage("F_SHOW_TOPIC")) ?></option>
                                <option
                                    value="<?= ($arResult["TOPIC"]["SORT"] != 150 ? "SET_ORDINARY" : "SET_TOP") ?>"><?
                                    ?><?= ($arResult["TOPIC"]["SORT"] != 150 ? GetMessage("F_UNPINN_TOPIC") : GetMessage("F_PINN_TOPIC")) ?></option>
                                <option
                                    value="<?= ($arResult["TOPIC"]["STATE"] == "Y" ? "STATE_N" : "STATE_Y") ?>"><?
                                    ?><?= ($arResult["TOPIC"]["STATE"] == "Y" ? GetMessage("F_CLOSE_TOPIC") : GetMessage("F_OPEN_TOPIC")) ?></option>
                                <option value="MOVE_TOPIC"><?= GetMessage("F_MOVE_TOPIC") ?></option>
                                <?
                                if ($arResult["USER"]["RIGHTS"]["EDIT"] == "Y"):
                                    ?>
                                    <option value="EDIT_TOPIC"><?= GetMessage("F_EDIT_TOPIC") ?></option>
                                    <option value="DEL_TOPIC"><?= GetMessage("F_DELETE_TOPIC") ?></option>
                                    <?
                                endif;
                                ?>
                            </select>&nbsp;<input
                                onmousedown="this.form.type.value='topic';this.form.ACTION.value=this.form.ACTION_TOPIC.value;"
                                <?
                                ?>type="submit" value="OK"/>
                        </div>
                    </div>
                </form>
            <?}*/?>
        </div>
    </div>

<?
if (!empty($arResult["ERROR_MESSAGE"])):
    ?>
    <div class="forum-note-box forum-note-error">
        <div class="forum-note-box-text"><?= ShowError($arResult["ERROR_MESSAGE"], "forum-note-error"); ?></div>
    </div>
    <?
endif;
if (!empty($arResult["OK_MESSAGE"])):
    ?>
    <div class="forum-note-box forum-note-success">
        <div class="forum-note-box-text"><?= ShowNote($arResult["OK_MESSAGE"], "forum-note-success") ?></div>
    </div>
    <?
endif;

// View new posts
if ($arResult["VIEW"] == "Y"):
    ?><? $GLOBALS["APPLICATION"]->IncludeComponent(
    "bitrix:forum.message.template",
    ".preview",
    Array(
        "MESSAGE" => $arResult["MESSAGE_VIEW"],
        "ATTACH_MODE" => $arParams["ATTACH_MODE"],
        "ATTACH_SIZE" => $arParams["ATTACH_SIZE"],
        "arResult" => $arResult,
        "arParams" => $arParams
    ),
    $component->__parent,
    array("HIDE_ICONS" => "Y")
); ?><?
endif;
?>
<div class="line-separator"></div>
<script type="text/javascript">
   

    <?if (intVal($arParams["MID"]) > 0):?>
    location.hash = 'message<?=$arParams["MID"]?>';
    <?endif;?>
    if (typeof oText != "object")
        var oText = {};
    oText['cdt'] = '<?=GetMessageJS("F_DELETE_TOPIC_CONFIRM")?>';
    oText['cdm'] = '<?=GetMessageJS("F_DELETE_CONFIRM")?>';
    oText['cdms'] = '<?=GetMessageJS("F_DELETE_MESSAGES_CONFIRM")?>';
    oText['no_data'] = '<?=GetMessageJS('JS_NO_MESSAGES')?>';
    oText['no_action'] = '<?=GetMessageJS('JS_NO_ACTION')?>';
    oText['quote_text'] = '<?=GetMessageJS("JQOUTE_AUTHOR_WRITES");?>';
    oText['show'] = '<?=GetMessageJS("F_SHOW")?>';
    oText['hide'] = '<?=GetMessageJS("F_HIDE")?>';
    oText['wait'] = '<?=GetMessageJS("F_WAIT")?>';

    BX.message({
        topic_read_url: '<?=CUtil::JSUrlEscape($arResult['CURRENT_PAGE']);?>',
        page_number: '<?=intval($arResult['PAGE_NUMBER']);?>'
    });
    <?
    if ($GLOBALS["USER"]->IsAuthorized() && $bShowedHeader):
    ?>
    function ShowFirstPost(oA) {
        var div = oA.parentNode.parentNode.parentNode.nextSibling.firstChild;
        div.style.display = (div.style.display == 'none' ? '' : 'none');
        oA.innerHTML = (div.style.display == 'none' ? '<?=GetMessageJS("F_COLLAPSE")?>' : '<?=GetMessageJS("F_SHOW")?>');
        BX.ajax.get(
            '/bitrix/components/bitrix/forum/templates/.default/user_settings.php',
            {
                'save': 'first_post',
                'value': (div.style.display == 'none' ? 'hide' : 'show'),
                'sessid': '<?=bitrix_sessid()?>'
            }
        );
        return false;
    }
    <?
    endif;
    ?>
</script>