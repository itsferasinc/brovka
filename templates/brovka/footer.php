<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);
GLOBAL $USER;
CModule::IncludeModule("socialnetwork");
CModule::IncludeModule("sale");

$name = trim($USER->GetFullName());
if(strlen($name)<1)
    $name = $USER->GetLogin();
$dbFriends = CSocNetUserRelations::GetRelatedUsers(USER_ID, SONET_RELATIONS_FRIEND, false);
if($dbFriends) {
    $friends = $dbFriends->SelectedRowsCount();
}
$account = CSaleUserAccount::GetByUserID(USER_ID, "RUB");
if($account['CURRENT_BUDGET'] <= 0)
    $account['CURRENT_BUDGET'] = 0;
$rsUser = CUser::GetByID(USER_ID);
$arUser = $rsUser->Fetch();
$sonetPic = array(
"M" => "default_user_picture_male",
"" => "default_user_picture_unknown",
"F" => "default_user_picture_female",
);
$userSocPic = COption::GetOptionString("socialnetwork", $sonetPic[$arUser["PERSONAL_GENDER"]]);
if($arUser['PERSONAL_PHOTO'])
    $photo = CFile::ResizeImageGet($arUser['PERSONAL_PHOTO'], array('width'=>80, 'height'=>80), BX_RESIZE_IMAGE_EXACT , false);
else
    $photo = CFile::ResizeImageGet($userSocPic, array('width'=>80, 'height'=>80), BX_RESIZE_IMAGE_EXACT , false);
$logoutBtn = $APPLICATION->GetCurPageParam("logout=yes", array());
?>
    </div>
    </div>
    </div>
    </div>


    <?if ($GLOBALS["bRightColumnVisible"]){?>
        <div class="col-xs-3">
            <div class="row">
                <div class="col-xs-12 aside-column">
                    <?if($USER->IsAuthorized()){?>
                        <div class="profile">
                            <div class="user-name">
                                <div class="avatar" style="background-image: url('<?=$photo['src']?>')"></div>
                                <div class="name">
                                    <h2>
                                        <?=$name;?>
                                    </h2>
                                    <a href="/profile/">Настройки профиля</a>
                                    <br>
                                    <a href="<?=$logoutBtn?>">Выход</a>
                                </div>
                            </div>
                            <div class="user-buttons">
                                <?/*
                                <div class="subscribers">
                                    <span><?=$friends?></span>
                                    <p>
                                        <?=getNumEnding($friends, array("подписчик","подписчика","подписчиков"))?>
                                    </p>
                                </div>
                                <?/* Блок счета <div class="price"><?=CCurrencyLang::CurrencyFormat($account['CURRENT_BUDGET'], "RUB")?></div>
                            <button class="deposit">Пополнить</button> */?>
                            <a class="note" href="/blogs/new/">
                                <i class="flaticon-plus"></i>
                                <span>Создать запись</span>
                            </a>
                        </div>
                        <div class="user-list">
                            <?$APPLICATION->IncludeComponent(
                                    "bitrix:menu",
                                    "user",
                                    Array(
                                        "ROOT_MENU_TYPE"	=>	"user",
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
                <?}?>
                    <?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	".default", 
	array(
		"AREA_FILE_SHOW" => "sect",
		"AREA_FILE_SUFFIX" => "inc",
		"AREA_FILE_RECURSIVE" => "Y",
		"EDIT_MODE" => "html",
		"EDIT_TEMPLATE" => "sect_inc.php",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "page",
                                "AREA_FILE_SUFFIX" => "inc",
                                "AREA_FILE_RECURSIVE" => "N",
                                "EDIT_MODE" => "html",
                                "EDIT_TEMPLATE" => "page_inc.php"
                            )
                        );?>
            </div>
        </div>
        </div>
        <?}?>
            </div>
            </div>

            <footer>
                <div class="container footer-adapt">
                    
                    <div class="row">
                        <div class="col-xs-10">
                            <div class="row">
                                <div class="col-xs-3">
                                    <?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", Array(
                                    "ROOT_MENU_TYPE" => "bottom1",
                                    "MAX_LEVEL"	=>	"1",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_TIME" => "36000000",
                                    "MENU_CACHE_USE_GROUPS" => "N",
                                    "MENU_CACHE_GET_VARS" => Array()
                                )
                            );?>
                                </div>
                                <div class="col-xs-3">
                                    <?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", Array(
                                    "ROOT_MENU_TYPE" => "bottom2",
                                    "MAX_LEVEL"	=>	"1",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_TIME" => "36000000",
                                    "MENU_CACHE_USE_GROUPS" => "N",
                                    "MENU_CACHE_GET_VARS" => Array()
                                )
                            );?>
                                </div>
                                <div class="col-xs-3">
                                    <?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", array(
	"ROOT_MENU_TYPE" => "bottom3",
		"MAX_LEVEL" => "1",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "36000000",
		"MENU_CACHE_USE_GROUPS" => "N",
		"MENU_CACHE_GET_VARS" => ""
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "N"
	)
);?>
                                </div>
                                <div class="col-xs-3 copyright">
                                    <ul>
                                        <li>
                                            <?$APPLICATION->IncludeFile(
                                        $APPLICATION->GetTemplatePath(SITE_DIR."include/company_footer_name.php"),
                                        Array(),
                                        Array("MODE" => "html")
                                    );?>

                                                <?$APPLICATION->IncludeFile(
                                        $APPLICATION->GetTemplatePath(SITE_DIR."include/copyright.php"),
                                        Array(),
                                        Array("MODE" => "html")
                                    );?>
                                        </li>
                                        <li class="row">
                                            <?$APPLICATION->IncludeFile(
                                        $APPLICATION->GetTemplatePath(SITE_DIR."include/copyright_add.php"),
                                        Array(),
                                        Array("MODE" => "html")
                                    );?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                </footer>
            





            <?/*
<?$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "sidebar",
    Array(
        "AREA_FILE_SHOW" => "page",
        "AREA_FILE_SUFFIX" => "inc",
        "AREA_FILE_RECURSIVE" => "N",
        "EDIT_MODE" => "html",
        "EDIT_TEMPLATE" => "page_inc.php"
        )
);?>
                <?$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "sidebar",
    Array(
        "AREA_FILE_SHOW" => "sect",
        "AREA_FILE_SUFFIX" => "inc",
        "AREA_FILE_RECURSIVE" => "Y",
        "EDIT_MODE" => "html",
        "EDIT_TEMPLATE" => "sect_inc.php"
    )
);?>
                    <?$APPLICATION->ShowViewContent("sidebar")?>
                        */?>

                        </body>

                        </html>
