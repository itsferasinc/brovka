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
?>

<div class="news-detail">
	<div class="guid-item-detail row">
		<div class="col-xs-12">
			<?$renderImageBg = CFile::ResizeImageGet($arResult["PROPERTIES"]["BACKGROUND"]["VALUE"], Array("width" => 800, "height" => 300), BX_RESIZE_IMAGE_EXACT, false);?> 
			<div class="guid-detail-img" style="background-image: url(<?=$renderImageBg['src'];?>); height: 300px;"  alt="">
			</div>
			
		</div>
		<div class="guid-item-person-wrap">
			<div class="col-xs-4">
				<div class="guid-avatar">
					<img alt="<?=$arResult['PREVIEW_PICTURE']['ALT'];?>" title="<?=$arResult['PREVIEW_PICTURE']['TITLE'];?>"src="<?=$arResult["PREVIEW_PICTURE"]["SRC"]?>" alt="">
				</div>
			</div>
			<div class="col-xs-8">
				<div class="guid-detail-description">
					<div class="guid-detail-item-description">
						<h1><?=$arResult['NAME'];?></h1>
						<?/*<p class="h4">Авторитет на сайте: 324234</p>*/?>
						<p class="guid-detail-fishing-way"><?=$arResult["PREVIEW_TEXT"];?></p>
					</div>

					<div class="guid-show-contacts">
						<a class="note" href="javascript:;" onclick="guideShowContacts(<?=$arResult['ID'];?>)">Показать контакты</a>
					</div>

					<div class="content-block-links fishing-way flaticon-anchor">
					<?foreach($arResult["DISPLAY_PROPERTIES"]["FISHING_TYPE"]["LINK_ELEMENT_VALUE"] as $fishingType):?>
						<a class="link-way" href="/guides/?arrFilter_30=<?=abs(crc32($fishingType['ID']))?>&set_filter=Найти<?//=$fishingType["DETAIL_PAGE_URL"];?>"><?=$fishingType["NAME"];?></a>
					<?endforeach;?>	
					</div>

					<div class="content-block-links fish-type flaticon-fish">
					<?foreach($arResult["DISPLAY_PROPERTIES"]["FISH_TYPE"]["LINK_ELEMENT_VALUE"] as $fishType):?>
						<a class="link-way" href="/guides/?arrFilter_33=<?=abs(crc32($fishingType['ID']))?>&set_filter=Найти<?//=$fishType["DETAIL_PAGE_URL"];?>"><?=$fishType["NAME"];?></a>
					<?endforeach;?>
					</div>

				</div>		

			</div>
		</div>
		<div class="col-xs-12">
			<?=$arResult["DETAIL_TEXT"];?>
		</div>
	</div>
	<div class="line-separator"></div>
</div>

<?$arBlog = CBlog::GetByOwnerID($arResult["PROPERTIES"]["USER_ID"]["VALUE"]);
GLOBAL $replaceBasePath,$APPLICATION;
$replaceBasePath = '/guides/'.$arResult['CODE'].'/';
?>
<?$APPLICATION->IncludeComponent("bitrix:blog.blog", "blog", Array(
    "BLOG_URL" => $arBlog["URL"],	// Путь блога
    "BLOG_VAR" => "",	// Имя переменной для идентификатора блога
    "CACHE_TIME" => "7200",	// Время кеширования (сек.)
    "CACHE_TIME_LONG" => "604600",	// Время кеширования остальных страниц
    "CACHE_TYPE" => "A",	// Тип кеширования
    "CATEGORY_ID" => $category,	// Идентификатор тега для фильтрации
    "DATE_TIME_FORMAT" => "d.m.Y H:i:s",	// Формат показа даты и времени
    "DAY" => $day,	// День для фильтрации
    "FILTER_NAME" => "arFilter",	// Имя массива со значениями фильтра для фильтрации сообщений
    "IMAGE_MAX_HEIGHT" => "600",	// Максимальная высота изображения
    "IMAGE_MAX_WIDTH" => "600",	// Максимальная ширина изображения
    "MESSAGE_COUNT" => "10",	// Количество сообщений, выводимых на страницу
    "MONTH" => $month,	// Месяц для фильтрации
    "NAV_TEMPLATE" => "modern",	// Имя шаблона для постраничной навигации
    "PAGE_VAR" => "",	// Имя переменной для страницы
    "PATH_TO_BLOG" => "/",	// Шаблон пути к странице блога
    "PATH_TO_BLOG_CATEGORY" => "/",	// Шаблон пути к странице блога c фильтром по тегу
    "PATH_TO_POST" => "/blogs/#blog#/#post_id#/",	// Шаблон пути к странице с сообщением блога
    "PATH_TO_POST_EDIT" => "/",	// Шаблон пути к странице редактирования сообщения блога
    "PATH_TO_SMILE" => "",	// Путь к папке со смайликами относительно корня сайта
    "PATH_TO_USER" => "/",	// Шаблон пути к странице пользователя блога
    "POST_PROPERTY_LIST" => "",	// Показывать доп. свойства сообщения в блоге
    "POST_VAR" => "",	// Имя переменной для идентификатора сообщения блога
    "RATING_TYPE" => "",	// Вид кнопок рейтинга
    "SEO_USER" => "N",	// Запретить индексацию ссылки на профиль пользователя поисковыми ботами
    "SET_NAV_CHAIN" => "Y",	// Добавлять пункт в цепочку навигации
    "SET_TITLE" => "Y",	// Устанавливать заголовок страницы
    "SHOW_RATING" => "",	// Включить рейтинг
    "USER_VAR" => "",	// Имя переменной для идентификатора пользователя блога
    "YEAR" => $year,	// Год для фильтрации
),
    false
);?>


<?/*
<div class="news-detail">
	<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
		<img
			class="detail_picture"
			border="0"
			src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
			width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>"
			height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>"
			alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
			title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
			/>
	<?endif?>
	<?if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
		<span class="news-date-time"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></span>
	<?endif;?>
	<?if($arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
		<h3><?=$arResult["NAME"]?></h3>
	<?endif;?>
	<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arResult["FIELDS"]["PREVIEW_TEXT"]):?>
		<p><?=$arResult["FIELDS"]["PREVIEW_TEXT"];unset($arResult["FIELDS"]["PREVIEW_TEXT"]);?></p>
	<?endif;?>
	<?if($arResult["NAV_RESULT"]):?>
		<?if($arParams["DISPLAY_TOP_PAGER"]):?><?=$arResult["NAV_STRING"]?><br /><?endif;?>
		<?echo $arResult["NAV_TEXT"];?>
		<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?><br /><?=$arResult["NAV_STRING"]?><?endif;?>
	<?elseif(strlen($arResult["DETAIL_TEXT"])>0):?>
		<?echo $arResult["DETAIL_TEXT"];?>
	<?else:?>
		<?echo $arResult["PREVIEW_TEXT"];?>
	<?endif?>
	<div style="clear:both"></div>
	<br />
	<?foreach($arResult["FIELDS"] as $code=>$value):
		if ('PREVIEW_PICTURE' == $code || 'DETAIL_PICTURE' == $code)
		{
			?><?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?
			if (!empty($value) && is_array($value))
			{
				?><img border="0" src="<?=$value["SRC"]?>" width="<?=$value["WIDTH"]?>" height="<?=$value["HEIGHT"]?>"><?
			}
		}
		else
		{
			?><?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?><?
		}
		?><br />
	<?endforeach;
	foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>

		<?=$arProperty["NAME"]?>:&nbsp;
		<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
			<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
		<?else:?>
			<?=$arProperty["DISPLAY_VALUE"];?>
		<?endif?>
		<br />
	<?endforeach;
	if(array_key_exists("USE_SHARE", $arParams) && $arParams["USE_SHARE"] == "Y")
	{
		?>
		<div class="news-detail-share">
			<noindex>
			<?
			$APPLICATION->IncludeComponent("bitrix:main.share", "", array(
					"HANDLERS" => $arParams["SHARE_HANDLERS"],
					"PAGE_URL" => $arResult["~DETAIL_PAGE_URL"],
					"PAGE_TITLE" => $arResult["~NAME"],
					"SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
					"SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
					"HIDE" => $arParams["SHARE_HIDE"],
				),
				$component,
				array("HIDE_ICONS" => "Y")
			);
			?>
			</noindex>
		</div>
		<?
	}
	?>
</div>
*/?>





