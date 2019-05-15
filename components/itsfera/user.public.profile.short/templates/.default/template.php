<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<div class="guid-item-detail row">
    <div class="col-xs-12">
        <?$renderImageBg = CFile::ResizeImageGet($arResult['USER']['UF_BG_PROFILE'], Array("width" => 800, "height" => 300), BX_RESIZE_IMAGE_EXACT, true);?>
        <?if( !empty($arResult['USER']['UF_BG_PROFILE']) && $arResult['USER']['UF_BG_PROFILE'] != "" ):?>
            <div class="guid-detail-img" style="background-image: url(<?=$renderImageBg['src'];?>); height: 300px;"  alt="">
                <div class="bg-overlay"></div>
            </div>
        <?else:?>
            <div class="guid-detail-img" style="background-image: url('<?=SITE_TEMPLATE_PATH?>/images/guid_detail_bg.png'); height: 300px;"  alt=""></div>
        <?endif;?>
    </div>
    <div class="guid-item-person-wrap">
        <div id="ava-<?=$arResult['ID'];?>" class="col-xs-4">
            <div data-bx-image="<?=$arResult['USER']['FORUM']['AVATAR_SRC']?>" class="guid-avatar" style="background-image: url(<?=$arResult['USER']['FORUM']['AVATAR_SRC']?>)">
                <img style="display: none" alt="<?=$arResult['USER']['NAME'];?>" title="<?=$arResult['USER']['NAME'];?>" src="<?=$arResult['USER']['FORUM']['AVATAR_SRC']?>">
            </div>


            <?$APPLICATION->IncludeComponent(
                "bitrix:socialnetwork.user_profile",
                "template",
                array(
                    "AVATAR_SIZE" => "300",
                    "DATE_TIME_FORMAT" => "d.m.Y H:i:s",
                    "ID" => $arParams['ID'],
                    "ITEMS_COUNT" => "6",
                    "PAGE_VAR" => "",
                    "PATH_TO_GROUP" => "",
                    "PATH_TO_GROUP_CREATE" => "",
                    "PATH_TO_GROUP_EDIT" => "",
                    "PATH_TO_GROUP_SEARCH" => "",
                    "PATH_TO_LOG" => "",
                    "PATH_TO_MESSAGES_CHAT" => "",
                    "PATH_TO_MESSAGES_USERS_MESSAGES" => "",
                    "PATH_TO_MESSAGE_FORM" => "",
                    "PATH_TO_SEARCH" => "",
                    "PATH_TO_SEARCH_INNER" => "",
                    "PATH_TO_USER" => "",
                    "PATH_TO_USER_EDIT" => "",
                    "PATH_TO_USER_FEATURES" => "",
                    "PATH_TO_USER_FRIENDS" => "",
                    "PATH_TO_USER_FRIENDS_ADD" => "",
                    "PATH_TO_USER_FRIENDS_DELETE" => "",
                    "PATH_TO_USER_GROUPS" => "",
                    "PATH_TO_USER_SETTINGS_EDIT" => "",
                    "PATH_TO_USER_SUBSCRIBE" => "",
                    "SET_NAV_CHAIN" => "Y",
                    "SET_TITLE" => "Y",
                    "SHORT_FORM" => "N",
                    "SHOW_YEAR" => "Y",
                    "SONET_USER_FIELDS_SEARCHABLE" => array(
                        0 => "LOGIN",
                        1 => "NAME",
                        2 => "SECOND_NAME",
                        3 => "LAST_NAME",
                        4 => "PERSONAL_BIRTHDAY",
                        5 => "PERSONAL_PROFESSION",
                        6 => "PERSONAL_GENDER",
                        7 => "PERSONAL_COUNTRY",
                        8 => "PERSONAL_STATE",
                        9 => "PERSONAL_CITY",
                        10 => "PERSONAL_ZIP",
                        11 => "PERSONAL_STREET",
                        12 => "PERSONAL_MAILBOX",
                        13 => "WORK_COMPANY",
                        14 => "WORK_DEPARTMENT",
                        15 => "WORK_POSITION",
                        16 => "WORK_COUNTRY",
                        17 => "WORK_STATE",
                        18 => "WORK_CITY",
                        19 => "WORK_ZIP",
                        20 => "WORK_STREET",
                        21 => "WORK_MAILBOX",
                    ),
                    "SONET_USER_PROPERTY_SEARCHABLE" => array(
                    ),
                    "USER_FIELDS_CONTACT" => array(
                    ),
                    "USER_FIELDS_MAIN" => array(
                        0 => "NAME",
                        1 => "SECOND_NAME",
                        2 => "LAST_NAME",
                    ),
                    "USER_FIELDS_PERSONAL" => array(
                    ),
                    "USER_PROPERTY_CONTACT" => array(
                    ),
                    "USER_PROPERTY_MAIN" => array(
                    ),
                    "USER_PROPERTY_PERSONAL" => array(
                    ),
                    "USER_VAR" => "",
                    "COMPONENT_TEMPLATE" => "template"
                ),
                false
            );?>



        </div>
        <div class="col-xs-8">
            <div class="guid-detail-description">
                <div class="guid-detail-item-description">
                    <h1><?=$arResult['USER']['NAME'];?></h1>
                    <?/*<p class="h4">Авторитет на сайте: 324234</p>*/?>
                    <p class="guid-detail-fishing-way"><?=$arResult["PREVIEW_TEXT"];?></p>
                </div>

                <?/*
                <div class="guid-show-contacts">
                    <a class="note" href="javascript:;" onclick="guideShowContacts(<?=$arResult['ID'];?>)">Показать контакты</a>
                </div>

                <div class="content-block-links fishing-location flaticon-location">
                    <?foreach($arResult["PROPERTIES"]["LOCATION"]["VALUE"] as $i => $location):?>
                        <a class="link-way" href="/guides/?arrFilter_31=<?=abs(crc32($arResult["DISPLAY_PROPERTIES"]["LOCATION"]["VALUE"][$i]))?>&set_filter=Найти<?//=$fishingType["DETAIL_PAGE_URL"];?>"><?=$location;?></a><br>
                    <?endforeach;?>
                </div>

                <div class="content-block-links fishing-way flaticon-fishing-rod">
                    <?foreach($arResult["DISPLAY_PROPERTIES"]["FISHING_TYPE"]["LINK_ELEMENT_VALUE"] as $fishingType):?>
                        <a class="link-way" href="/guides/?arrFilter_30=<?=abs(crc32($fishingType['ID']))?>&set_filter=Найти<?//=$fishingType["DETAIL_PAGE_URL"];?>"><?=$fishingType["NAME"];?></a>
                    <?endforeach;?>
                </div>

                <div class="content-block-links fish-type flaticon-fish">
                    <?foreach($arResult["DISPLAY_PROPERTIES"]["FISH_TYPE"]["LINK_ELEMENT_VALUE"] as $fishType):?>
                        <a class="link-way" href="/guides/?arrFilter_33=<?=abs(crc32($fishingType['ID']))?>&set_filter=Найти<?//=$fishType["DETAIL_PAGE_URL"];?>"><?=$fishType["NAME"];?></a>
                    <?endforeach;?>
                </div>
                */?>
            </div>

        </div>
    </div>
</div>

<h2>Последние записи</h2>
<?$arBlog = CBlog::GetByOwnerID($arResult['USER']['ID']);
GLOBAL $replaceBasePath;
$replaceBasePath = '/people/user/'.$arResult['USER']['ID'].'/profile/';
?>
<?$APPLICATION->IncludeComponent("bitrix:blog.blog", "blog_short", Array(
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
    "MESSAGE_COUNT" => "2",	// Количество сообщений, выводимых на страницу
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
    "SET_NAV_CHAIN" => "N",	// Добавлять пункт в цепочку навигации
    "SET_TITLE" => "N",	// Устанавливать заголовок страницы
    "SHOW_RATING" => "",	// Включить рейтинг
    "USER_VAR" => "",	// Имя переменной для идентификатора пользователя блога
    "YEAR" => $year,	// Год для фильтрации
),
    false
);?>


