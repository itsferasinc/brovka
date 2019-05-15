<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

CJSCore::Init('ajax','tabs');

if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR']){
    ShowMessage($arResult['ERROR_MESSAGE']);
    ?><script>
        $.fancybox.open({
            src: "#regpopup",
            type :'inline'
        });
        $(".authlinkbtn").trigger("click");

    </script><?
}

if ($arResult["FORM_TYPE"] != "login") 
{
	?><div id="user-menu">
		<div id="user-name"><?=GetMessage("AUTH_HELLO")?> <a href="<?=$arResult["urlToOwnProfile"]?>"><?=$arResult["USER_LOGIN"]?></a>!</div>
		<ul class="mdash-list">
			<li><a href="<?=$arResult["urlToOwnProfile"]?>"><?=GetMessage("AUTH_PROFILE")?></a></li><?

			if (!empty($arResult["urlToCreateMessageInBlog"]))
			{
				?><li><a href="<?=$arResult["urlToCreateMessageInBlog"]?>"><?=GetMessage("AUTH_BLOG_MESSAGE")?></a></li><?
			}
			if (array_key_exists("PATH_TO_SONET_LOG", $arParams) && strlen($arParams["PATH_TO_SONET_LOG"]) > 0)
			{
				?><li>
					<a href="<?=$arParams["PATH_TO_SONET_LOG"]?>"><?=GetMessage("AUTH_SONET_LOG")?></a><?
					if (intval($arResult["LOG_COUNTER"]) > 0)
						echo " (".intval($arResult["LOG_COUNTER"]).")";
				?></li><?
			}
		?></ul>
		<a href="<?=$GLOBALS["APPLICATION"]->GetCurPageParam("logout=yes", array("logout"))?>" id="logout" title="<?=GetMessage("AUTH_LOGOUT")?>"><?=GetMessage("AUTH_LOGOUT")?></a>
	</div>
    <?
}
else 
{
	?><form action="<?=$arResult["AUTH_URL"]?>" METHOD="POST" target="_top" id="reg-form">
        <input type="hidden" name="AUTH_FORM" value="Y" />
        <input type="hidden" name="TYPE" value="AUTH" />

        <div>
            <label for="email">E-mail</label>
            <input id="email" type="email" name="USER_LOGIN" value="<?=$arResult["USER_LOGIN"]?>">
        </div>
        <div>
            <label for="password">Пароль</label>
            <input id="password" type="password" name="USER_PASSWORD">
        </div>
        <div>
            <input type="submit" value="Войти" name="Login">
        </div>
	</form>


    <a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>">
        <?=GetMessage("AUTH_FORGOT_PASSWORD")?>
    </a>

    <?if($arResult["AUTH_SERVICES"]):?>
        <?$APPLICATION->IncludeComponent(
            "ulogin:auth",
            ".default",
            array(
                "GROUP_ID" => array(
                    0 => "3",
                    1 => "4",
                    2 => "11",
                ),
                "LOGIN_AS_EMAIL" => "Y",
                "SEND_EMAIL" => "Y",
                "SOCIAL_LINK" => "Y",
                "ULOGINID1" => "0987f586",
                "ULOGINID2" => "",
                "COMPONENT_TEMPLATE" => ".default"
            ),
            false
        );?>
        <?/*
        $APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "",
            array(
                "AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
                "AUTH_URL"=>$arResult["AUTH_URL"],
                "POST"=>$arResult["POST"],
                "POPUP"=>"N",
                "SUFFIX"=>"form",
            ),
            $component,
            array("HIDE_ICONS"=>"Y")
        );*/
        ?>
    <?endif;
}
/*
if($arResult["NEW_USER_REGISTRATION"] == "Y")
				{
					?><a href="<?=$arResult["AUTH_REGISTER_URL"]?>" title="<?=GetMessage("AUTH_REGISTER_DESC")?>"><?=GetMessage("AUTH_REGISTER")?></a><?
					?>&nbsp;&nbsp;&nbsp;<a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" title="<?=GetMessage("AUTH_FORGOT_PASSWORD")?>">?</a><?
				}
				else
				{
					?><a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>"><?=GetMessage("AUTH_FORGOT_PASSWORD")?></a><?
				}
            if ($arResult["STORE_PASSWORD"] == "Y")
            {
            ?><tr>
            <td>&nbsp;</td>
            <td><?
                ?><input type="checkbox" id="remember-checkbox" class="checkbox" name="USER_REMEMBER" value="Y" /><?
                ?><label for="remember-checkbox" class="remember"><?=GetMessage("AUTH_REMEMBER_ME")?></label><?
                ?></td>
        </tr><?
            }
*/
?>