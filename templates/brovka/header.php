<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Page\Asset;
CModule::IncludeModule("blog");
global $USER, $BLOG_URL;
define("USER_ID",$USER->getid());
if (strpos($GLOBALS["APPLICATION"]->GetCurPage(true), SITE_DIR."people/index.php") === 0 || strpos($GLOBALS["APPLICATION"]->GetCurPage(true), SITE_DIR."groups/index.php") === 0)
	$GLOBALS["bRightColumnVisible"] = true;
else
	$GLOBALS["bRightColumnVisible"] = ($GLOBALS["APPLICATION"]->GetProperty("hide_sidebar") == "Y" ? false : true);

if(defined("INDEX_PAGE"))
    $index = true;
else
    $inner = true;
$arBlog = CBlog::GetByOwnerID(USER_ID);
if(!$arBlog['URL']) $arBlog['URL'] = 'error';
define("BLOG_URL",$arBlog['URL']);

if ($USER->IsAuthorized())
    $auth = true;
?>
    <!DOCTYPE html>
    <html lang="<?= LANGUAGE_ID ?>">

    <head id="Head">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=1200px">
		<meta name="mailru-verification" content="318aab81e8ffd699" />
        <?if(!defined("REPLACE_OG")){?>
            <?$APPLICATION->IncludeFile(
                "/local/include/og.php",
                Array(),
                Array("MODE" => "html")
            );
            /*?>
            <meta property="og:title" content="<?=$APPLICATION->ShowTitle(false)?> - Brovka.net" />
            <meta property="og:type" content="website" />
            <meta property="og:description" content="<?=$APPLICATION->GetProperty("description")?>" />
            <meta property="og:image" content="http://brovka.net/local/templates/brovka/images/logo_op.png" />
            <meta property="og:url" content="<?=$_SERVER['SCRIPT_URI']?>" />
            */?>
        <?}?>
        <?$APPLICATION->ShowHead()?>
        <title><?$APPLICATION->ShowTitle()?> - Brovka.net</title>
        <?/*
        <link rel="stylesheet" type="text/css" href="<?= SITE_TEMPLATE_PATH ?>/blog.css" />
        <link rel="stylesheet" type="text/css" href="<?= SITE_TEMPLATE_PATH ?>/common.css" />
        <link rel="stylesheet" type="text/css" href="<?= SITE_TEMPLATE_PATH ?>/colors.css" /> */?>
        <link rel="shortcut icon" type="image/x-icon" href="<?=SITE_TEMPLATE_PATH?>/favicon.png" />
        <link rel="icon" type="image/png" href="<?=SITE_TEMPLATE_PATH?>/favicon-16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="<?=SITE_TEMPLATE_PATH?>/favicon-76.png" sizes="76x76">
        <link rel="icon" type="image/png" href="<?=SITE_TEMPLATE_PATH?>/favicon-120.png" sizes="120x120">
        <link rel="icon" type="image/png" href="<?=SITE_TEMPLATE_PATH?>/favicon-152.png" sizes="152x152">
        <?
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/css/bootstrap_noadapt.css");
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/fonts/flaticon/flaticon.css");
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/js/fancybox/jquery.fancybox.min.css");
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/css/select2.min.css");
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/css/styles.css");
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/css/responsive.css");

        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery-3.2.1.min.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery.airStickyBlock.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/bootstrap.min.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/fancybox/jquery.fancybox.min.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/select2.full.min.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery.mask.min.js");
        Asset::getInstance()->addJs("https://yastatic.net/share2/share.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/main.js");
        ?>
        <link rel="yandex-tableau-widget" href="/manifest.json" />

    </head>

    <body>
        <?if (IsModuleInstalled("im")) $APPLICATION->IncludeComponent("bitrix:im.messenger", "", Array(), null, array("HIDE_ICONS" => "Y")); ?>
            <div id="panel">
                <?$APPLICATION->ShowPanel();?>
            </div>
            <?/*
    <div class="container fixed-menu">
        <header class="row">
            <div class="col-xs-9">
                <div class="row">
                    <div itemscope itemtype="http://schema.org/Organization" class="col-xs-2">
                        <a href="/"><img itemprop="logo" class="logo" src="<?=SITE_TEMPLATE_PATH?>/images/logo.png" title="Brovka.net" alt="Brovka.net"></a>
                </div>
                <div class="col-xs-10 menu-div">
                    <?$APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "main",
                            Array(
                                "ROOT_MENU_TYPE"	=>	"top",
                                "MAX_LEVEL"	=>	"1",
                                "USE_EXT"	=>	"N",
                                "MENU_CACHE_TYPE" => "A",
                                "MENU_CACHE_TIME" => "36000000",
                                "MENU_CACHE_USE_GROUPS" => "N",
                                "MENU_CACHE_GET_VARS" => Array()
                            )
                        );?>

                </div>
                </div>
                </div>
                <div class="col-xs-3">
                    <div class="row">
                        <div class="search col-xs-12">
                            <form action="/search/">
                                <input type="text" placeholder="Поиск по сайту" name="q">
                                <button type="submit"><i class="flaticon-search"></i></button>
                            </form>
                        </div>
                        <div class="col-xs-9 reg-div">
                            <ul>
                                <i class="flaticon-man"></i>
                                <li><a href="/registration/?auth" class="fancybox">Вход</a></li>
                                <li><a href="/registration/" class="fancybox">Регистрация</a></li>
                            </ul>
                        </div>
                        <div class="col-xs-3">
                            <div class="search-div">
                                <button><i class="flaticon-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                </header>
                </div>
                */ if($APPLICATION->GetDirProperty("FON")) $bg = $APPLICATION->GetDirProperty("FON"); else $bg = SITE_TEMPLATE_PATH.'/images/index_bg.png'; ?>
                <div class="wrapper" <?/*style="background-image: url('<?=$bg?>')" */?>>
                    <?$APPLICATION->IncludeComponent(
            "bitrix:advertising.banner",
            "background",
            Array(
                "CACHE_TIME" => "0",
                "CACHE_TYPE" => "A",
                "NOINDEX" => "Y",
                "QUANTITY" => "1",
                "TYPE" => "FON_BANNER"
            )
        );?>
                        <div class="container headcontainer">
                            <header class="row">
                                <div class="col-xs-9">
                                    <div class="row">
                                        <div itemscope itemtype="http://schema.org/Organization" class="col-xs-2">
                                            <a href="/"><img itemprop="logo" class="logo" src="<?=SITE_TEMPLATE_PATH?>/images/logo.png" title="Brovka.net" alt="Brovka.net"></a>
                                        </div>
                                        <div class="col-xs-10 menu-div">
                                            <?$APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "main",
                                Array(
                                    "ROOT_MENU_TYPE"	=>	"top",
                                    "MAX_LEVEL"	=>	"1",
                                    "USE_EXT"	=>	"N",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_TIME" => "36000000",
                                    "MENU_CACHE_USE_GROUPS" => "N",
                                    "MENU_CACHE_GET_VARS" => Array()
                                )
                            );?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="row">

                                        <?if(!$auth){?>
                                            <div class="col-xs-12">
                                                <div class="search" style="display: none">
                                                    <form action="/search/" method="get">
                                                        <input type="text" placeholder="Поиск по сайту" name="q">
                                                        <button type="submit"><i class="flaticon-search"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-xs-9 reg-div">
                                                <ul>
                                                    <i class="flaticon-man"></i>
                                                    <li><a href="#regpopup" data-fancybox class="" onclick='$(".authlinkbtn").trigger("click");'>Вход</a></li>
                                                    <?/* /registration/?auth */?>
                                                    <li><a href="#regpopup" data-fancybox onclick='$(".reglinkbtn").trigger("click");'>Регистрация</a></li>
                                                </ul>
                                                <div style="display:none;" id="regpopup">
                                                    <div class="tabs-div">
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            <li role="presentation" class="active">
                                                                <a href="#login" class="authlinkbtn" aria-controls="home" role="tab" data-toggle="tab">Вход</a>
                                                            </li>
                                                            <li role="presentation">
                                                                <a href="#reg" class="reglinkbtn" aria-controls="reg" role="tab" data-toggle="tab">Регистрация</a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content">
                                                            <div role="tabpanel" class="tab-pane active" id="login">
                                                                <div class="register-tab-wrap">
                                                                    <?$APPLICATION->IncludeComponent(
                                                                        "bitrix:system.auth.form",
                                                                        "auth",
                                                                        array(
                                                                            "REGISTER_URL" => SITE_DIR."auth/",
                                                                            "PROFILE_URL" => SITE_DIR."blogs/user/#user_id#/",
                                                                            "SHOW_ERRORS" => "Y",
                                                                            "PATH_TO_BLOG" => SITE_DIR."blogs/user/#user_id#/blog/",
                                                                            "PATH_TO_BLOG_NEW_POST" => SITE_DIR."people/user/#user_id#/blog/edit/new/",
                                                                            "PATH_TO_NEW_BLOG" => SITE_DIR."blogs/user/#user_id#/blog/",
                                                                            "PATH_TO_SONET_MESSAGES" => SITE_DIR."blogs/messages/",
                                                                            "PATH_TO_SONET_LOG" => SITE_DIR."people/log/",
                                                                            "COMPONENT_TEMPLATE" => "auth",
                                                                            "FORGOT_PASSWORD_URL" => "",
                                                                            "BLOG_GROUP_ID" => array(
                                                                                0 => "1",
                                                                                1 => "",
                                                                            )
                                                                        ),
                                                                        false
                                                                    );?>
                                                                </div>
                                                            </div>
                                                            <div role="tabpanel" class="tab-pane" id="reg">
                                                                <div class="register-tab-wrap">
                                                                    <?$APPLICATION->IncludeComponent("bitrix:main.register", "register", Array(
                                                                        "AUTH" => "Y",	// Автоматически авторизовать пользователей
                                                                        "REQUIRED_FIELDS" => array(	// Поля, обязательные для заполнения
                                                                            0 => "EMAIL",
                                                                            1 => "NAME",
                                                                        ),
                                                                        "SET_TITLE" => "Y",	// Устанавливать заголовок страницы
                                                                        "SHOW_FIELDS" => array(
                                                                            "EMAIL", "NAME", "LAST_NAME"
                                                                        ),
                                                                        "SUCCESS_PAGE" => "/registration/success/",	// Страница окончания регистрации
                                                                        "USER_PROPERTY" => "",	// Показывать доп. свойства
                                                                        "USER_PROPERTY_NAME" => "",	// Название блока пользовательских свойств
                                                                        "USE_BACKURL" => "Y",	// Отправлять пользователя по обратной ссылке, если она есть
                                                                    ),
                                                                        false
                                                                    );?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-xs-3">
                                                <div class="search-div">
                                                    <button><i class="flaticon-search"></i></button>
                                                </div>
                                            </div>
                                            <script>
                                                $('.search-div').bind('click', function () {
                                                    $('.search').animate({width: "show"}, 600);
                                                });
                                                $(document).mouseup(function (e) {
                                                    var container = $(".search");
                                                    if (!container.is(e.target) && container.has(e.target).length === 0) {
                                                        $('.search').animate({width: "hide"}, 600);
                                                    } else {

                                                    }
                                                });
                                            </script>

                                        <?}else{?>
                                            <div class="col-xs-12">
                                                <div class="search">
                                                    <form action="/search/" method="get">
                                                        <input type="text" placeholder="Поиск по сайту" name="q">
                                                        <button type="submit"><i class="flaticon-search"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                            <?/*
                                                <ul>
                                                    <i class="flaticon-man"></i>
                                                    <li><a href="./?logout=yes">Выход</a></li>
                                                    <li><a href="/profile/">Мой профиль</a></li>
                                                </ul>
                                        <?*/}?>
                                    </div>
                                </div>
                            </header>
                        </div>
                        <div class="container airSticky_stop-block">
                            <div class="content row">
                                <div class="col-xs-9">
                                    <div class="row">
                                        <div class="col-xs-12 content-list">
                                            <div class="content-block">
                                                <?if($inner){?>

                                                    <?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumbs", Array(
	"START_FROM" => "0",	// Номер пункта, начиная с которого будет построена навигационная цепочка
		"PATH" => "",	// Путь, для которого будет построена навигационная цепочка (по умолчанию, текущий путь)
		"SITE_ID" => "s1",	// Cайт (устанавливается в случае многосайтовой версии, когда DOCUMENT_ROOT у сайтов разный)
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?>

                                                        <?}?>










                                                            <?/*
<?= SITE_DIR ?>
                                                                <?$APPLICATION->IncludeFile(
					$APPLICATION->GetTemplatePath(SITE_DIR."include/company_logo.php"),
					Array(),
					Array("MODE"=>"html")
				);?>
                                                                    <?$APPLICATION->IncludeComponent(
					"bitrix:menu",
					"main",
					Array(
						"ROOT_MENU_TYPE"	=>	"top",
						"MAX_LEVEL"	=>	"1",
						"USE_EXT"	=>	"N",
						"MENU_CACHE_TYPE" => "A",
						"MENU_CACHE_TIME" => "36000000",
						"MENU_CACHE_USE_GROUPS" => "N",
						"MENU_CACHE_GET_VARS" => Array()
					)
				);?>
                                                                        <?$APPLICATION->IncludeComponent(
	"bitrix:system.auth.form",
	"auth",
	array(
		"REGISTER_URL" => SITE_DIR."auth/",
		"PROFILE_URL" => SITE_DIR."blogs/user/#user_id#/",
		"SHOW_ERRORS" => "N",
		"PATH_TO_BLOG" => SITE_DIR."blogs/user/#user_id#/blog/",
		"PATH_TO_BLOG_NEW_POST" => SITE_DIR."people/user/#user_id#/blog/edit/new/",
		"PATH_TO_NEW_BLOG" => SITE_DIR."blogs/user/#user_id#/blog/",
		"PATH_TO_SONET_MESSAGES" => SITE_DIR."blogs/messages/",
		"PATH_TO_SONET_LOG" => SITE_DIR."people/log/",
		"COMPONENT_TEMPLATE" => "auth",
		"FORGOT_PASSWORD_URL" => "",
		"BLOG_GROUP_ID" => array(
			0 => "1",
			1 => "",
		)
	),
	false
);?>
                                                                            <?$APPLICATION->IncludeComponent("bitrix:menu", "submenu", Array(
					"ROOT_MENU_TYPE"	=>	"left",
					"MAX_LEVEL"	=>	"1",
					"CHILD_MENU_TYPE"	=>	"left",
					"USE_EXT"	=>	"Y",
					"MENU_CACHE_TYPE" => "A",
					"MENU_CACHE_TIME" => "36000000",
					"MENU_CACHE_USE_GROUPS" => "Y",
					"MENU_CACHE_GET_VARS" => array(
						0 => "SECTION_ID",
						1 => "page",
					),
					)
				);?>
                                                                                <?$APPLICATION->IncludeComponent("bitrix:search.form", "main", Array(
								"PAGE"	=>	SITE_DIR."search/"
								)
							);?>
                                                                                    <h1>
                                                                                        <?$APPLICATION->ShowTitle(false)?>
                                                                                    </h1>
                                                                                    */?>
