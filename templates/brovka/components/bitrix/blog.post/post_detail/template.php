<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if (!$this->__component->__parent || empty($this->__component->__parent->__name) || $this->__component->__parent->__name != "bitrix:blog"):
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/blog/templates/.default/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/blog/templates/.default/themes/blue/style.css');
endif; ?>

<?CUtil::InitJSCore(array("image"));?>

<?
if(strlen($arResult["MESSAGE"])>0)
{
	?>
	<div class="blog-textinfo blog-note-box">
		<div class="blog-textinfo-text">
			<?=$arResult["MESSAGE"]?>
		</div>
	</div>
	<?
}
if(strlen($arResult["ERROR_MESSAGE"])>0)
{
	?>
	<div class="blog-errors blog-note-box blog-note-error">
		<div class="blog-error-text">
			<?=$arResult["ERROR_MESSAGE"]?>
		</div>
	</div>
	<?
}
if(strlen($arResult["FATAL_MESSAGE"])>0)
{
	?>
	<div class="blog-errors blog-note-box blog-note-error">
		<div class="blog-error-text">
			<?=$arResult["FATAL_MESSAGE"]?>
		</div>
	</div>
	<?
}
elseif(strlen($arResult["NOTE_MESSAGE"])>0)
{
	?>
	<div class="blog-textinfo blog-note-box">
		<div class="blog-textinfo-text">
			<?=$arResult["NOTE_MESSAGE"]?>
		</div>
	</div>
	<?
}
else
{
	if(!empty($arResult["Post"])>0)
	{
		$className = "blog-post";
		$className .= " blog-post-first";
		$className .= " blog-post-alt";
		$className .= " blog-post-year-".$arResult["Post"]["DATE_PUBLISH_Y"];
		$className .= " blog-post-month-".IntVal($arResult["Post"]["DATE_PUBLISH_M"]);
		$className .= " blog-post-day-".IntVal($arResult["Post"]["DATE_PUBLISH_D"]);
		?>
		<script>
		BX.viewImageBind(
			'blg-post-<?=$arResult["Post"]["ID"]?>',
			{showTitle: false},
			{tag:'IMG', attr: 'data-bx-image'}
		);
		</script>
        <?
        //$url = str_replace("#post_id#", $arResult['Post']['ID'], $arResult['Post']['PATH']);
        $url = "/blogs/".$arResult['Blog']['URL']."/".($arResult['Post']['CODE']?$arResult['Post']['CODE']:$arResult['Post']['ID'])."/";
        //$urlToAuthor = "/blogs/".$arResult['Blog']['URL']."/";
        $urlToAuthor = "/people/user/".$arResult['Post']['AUTHOR_ID']."/profile/";
        $arResult['AuthorName'] = ITSGlobal::getUserName($arResult['Post']['AUTHOR_ID']);
        $arResult["urlToEdit"] = "/blogs/edit/".$arResult['Post']['ID']."/";
        ?>

            <div class="block forumblogpost" data-id="<?=$arResult['Post']['ID']?>">
               <h1><?=trim($arResult["Post"]["TITLE"])?></h1>
                <div class="info">
                    <i class="flaticon-time"></i>
                    <p class="h4"><?=$arResult["Post"]["DATE_PUBLISH_FORMATED"]?></p>
                    <i class="flaticon-man"></i>
                    <p class="h4">Автор:
                        <a class="name-link" href="<?=$urlToAuthor?>">
                            <?=$arResult['AuthorName']?>
                        </a>
                    </p>
                    <i class="flaticon-bubble"></i>
                    <p class="h4">Комментариев: <a class="name-link" href="<?=$url?>"><?=$arResult["Post"]["NUM_COMMENTS"]?></a></p>
                    <i class="flaticon-eye"></i>
                    <p class="h4">Просмотров: <?=$arResult["Post"]["VIEWS"]?></p>
                    <i class="flaticon-eye"></i>
                    <p class="h4">Рейтинг: <?=(int)$arResult["RATING"]["TOTAL_VALUE"]?></p>
                    
                </div>
                    <div class="text-body">
                        <?=$arResult["Post"]["textFormated"]?>
                    </div>

                <?GLOBAL $themes; $themes = array();
                $themes['PROPERTY_T_BLOGS'] = $arResult['Post']['ID'];?>
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
                    <p><a href="/local/ajax/themes.php?id=<?=$arResult['Post']['ID']?>&type=blog" class="fancybox">Указать темы</a></p>
					<button class="hide-button" onclick="topic_hide_index(<?=$arResult['Post']['ID']?>,'blog',$(this))" <?if(in_array("f".$arResult['Post']['ID'], $arResult['FAVORITES'])){?>style="display: none"<?}?>>
						<span>Убрать с главной</span>
					</button>
					<button class="hide-button" onclick="topic_unhide_index(<?=$arResult['Post']['ID']?>,'blog',$(this))" <?if(in_array("f".$arResult['Post']['ID'], $arResult['FAVORITES'])){?>style="display: block"<?}?>>
						<span>Вернуть на главную</span>
					</button>
                <?}?>
                <div class="separator"></div>
                <div class="content-block-footer">
                    <button class="plus-button" onclick="upvote(<?=$arResult['Post']['ID']?>,'blog')">
                        <i class="flaticon-plus"></i>
                        <span><?=(int)$arResult['RATING']['TOTAL_POSITIVE_VOTES']?></span>
                    </button>
                    <button class="minus-button" onclick="downvote(<?=$arResult['Post']['ID']?>,'blog')">
                        <i class="flaticon-minus"></i>
                        <span><?=(int)$arResult['RATING']['TOTAL_NEGATIVE_VOTES']?></span>
                    </button>
                    <button class="favorite-button" onclick="favorites_add(<?=$arResult['Post']['ID']?>,'blog',$(this))" <?if(in_array("b".$arResult['Post']['ID'], $arResult['FAVORITES'])){?>style="display: none"<?}?>>
                        <i class="flaticon-star"></i>
                        <span>Добавить в избранное</span>
                    </button>
                    <button class="favorite-button favorite-active" onclick="favorites_del(<?=$arResult['Post']['ID']?>,'blog',$(this))" <?if(in_array("b".$arResult['Post']['ID'], $arResult['FAVORITES'])){?>style="display: block"<?}?>>
                        <i class="flaticon-star"></i>
                        <span>Избранное</span>
                    </button>
                    <div class="share">
                        <span>ПОДЕЛИТЬСЯ:</span>
                        <div class="ya-share2" data-services="vkontakte,twitter,facebook,gplus,odnoklassniki" data-counter></div>
                    </div>
                </div>
				<?if($USER->isadmin() || $arResult['Blog']['OWNER_ID'] == $USER->getid()){?>
				<div class="blog-post-meta-util">
					<?if(strLen($arResult["urlToHide"])>0):?>
						<span class="blog-post-hide-link"><a href="javascript:if(confirm('<?=GetMessage("BLOG_MES_HIDE_POST_CONFIRM")?>')) window.location='<?=$arResult["urlToHide"]."&".bitrix_sessid_get()?>'"><span class="blog-post-link-caption"><?=GetMessage("BLOG_MES_HIDE")?></span></a></span>
					<?endif;?>
					<?if(strLen($arResult["urlToEdit"])>0):?>
						<span class="blog-post-edit-link"><a href="<?=$arResult["urlToEdit"]?>"><span class="blog-post-link-caption"><?=GetMessage("BLOG_BLOG_BLOG_EDIT")?></span></a></span>
					<?endif;?>
					<?if(strLen($arResult["urlToDelete"])>0):?>
						<span class="blog-post-delete-link"><a href="javascript:if(confirm('<?=GetMessage("BLOG_MES_DELETE_POST_CONFIRM")?>')) window.location='<?=$arResult["urlToDelete"]."&".bitrix_sessid_get()?>'"><span class="blog-post-link-caption"><?=GetMessage("BLOG_BLOG_BLOG_DELETE")?></span></a></span>
					<?endif;?>
				</div>
				<?}?>
            </div>

		<?
	}
	else
		echo GetMessage("BLOG_BLOG_BLOG_NO_AVAIBLE_MES");
}

?>


<?$APPLICATION->IncludeComponent(
	"bitrix:blog.post.comment", 
	".default", 
	array(
		"AJAX_POST" => "N",
		"ALLOW_IMAGE_UPLOAD" => "R",
		"ALLOW_VIDEO" => "Y",
		"BLOG_URL" => $arResult["Blog"]["URL"],
		"BLOG_VAR" => "user",
		"CACHE_TIME" => "86400",
		"CACHE_TYPE" => "A",
		"COMMENTS_COUNT" => "25",
		"COMMENT_ID_VAR" => "",
		"DATE_TIME_FORMAT" => "d.m.Y H:i:s",
		"EDITOR_CODE_DEFAULT" => "N",
		"EDITOR_DEFAULT_HEIGHT" => "200",
		"EDITOR_RESIZABLE" => "N",
		"ID" => $arResult["Post"]["ID"],
		"IMAGE_MAX_HEIGHT" => "600",
		"IMAGE_MAX_WIDTH" => "600",
		"NO_URL_IN_COMMENTS" => "A",
		"NO_URL_IN_COMMENTS_AUTHORITY" => "",
		"PAGE_VAR" => "",
		"PATH_TO_BLOG" => "",
		"PATH_TO_SMILE" => "",
		"PATH_TO_USER" => "",
		"POST_VAR" => "post",
		"RATING_TYPE" => "",
		"SEO_USER" => "N",
		"SHOW_RATING" => "",
		"SHOW_SPAM" => "Y",
		"SIMPLE_COMMENT" => "N",
		"SMILES_COUNT" => "4",
		"USER_CONSENT" => "N",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"USER_VAR" => "",
		"USE_ASC_PAGING" => "N",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?>
