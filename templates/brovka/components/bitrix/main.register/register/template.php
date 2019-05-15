<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
?>


<?if($USER->IsAuthorized()):?>

<p><?echo GetMessage("MAIN_REGISTER_AUTH")?></p>

<?else:?>
<?
if (count($arResult["ERRORS"]) > 0):
	foreach ($arResult["ERRORS"] as $key => $error)
		if (intval($key) == 0 && $key !== 0) 
			$arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);

	ShowError(implode("<br />", $arResult["ERRORS"]));
    ?><script>
        $.fancybox.open({
            src: "#regpopup",
            type :'inline'
        });
        $(".reglinkbtn").trigger("click");

    </script><?

elseif($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):
?>
<p><?echo GetMessage("REGISTER_EMAIL_WILL_BE_SENT")?></p>
<?endif?>

<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data" id="reg-form">
<?if($arResult["BACKURL"] <> ''):?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?endif;?>
    <input name="REGISTER[LOGIN]" type="hidden"  value="blanket">
    <div>
        <label for="name">Имя <span class="starrequired">*</span></label>
        <input id="name" name="REGISTER[NAME]" type="text">
    </div>
    <div>
        <label for="lastname">Фамилия</label>
        <input id="lastname" name="REGISTER[LAST_NAME]" type="text">
    </div>
    <div>
        <label for="email">E-mail <span class="starrequired">*</span></label>
        <input id="email" name="REGISTER[EMAIL]" type="email">
    </div>
    <div>
        <label for="password">Пароль <span class="starrequired">*</span></label>
        <input id="password" name="REGISTER[PASSWORD]" type="password">
    </div>
    <div>
        <label for="password">Пароль ещё раз <span class="starrequired">*</span></label>
        <input id="password" name="REGISTER[CONFIRM_PASSWORD]" type="password">
    </div>
    <?/*
    <div class="checkbox-div">
        <input id="check1" class="custom-checkbox" type="checkbox">
        <label for="check1" class=" flaticon-check">Я согласен с условиями</label><br>
        <a href="#"> пользовательского соглашения</a>
    </div>
    <?*/if ($arResult["USE_CAPTCHA"] == "Y"){?>
        <div>
            <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
            <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
            <input name="captcha_word" maxlength="50" value="" />
        </div>
    <?}?>
    <div class="agreement-block">
        <?$APPLICATION->IncludeComponent(
            "bitrix:main.userconsent.request",
            "",
            array(
                "ID" => 1,
                "IS_CHECKED" => "Y",
                "AUTO_SAVE" => "Y",
                "IS_LOADED" => "Y",
                "REPLACE" => array(
                    'button_caption' => 'Зарегистрироваться',
                    'fields' => array('Email', 'Имя')
                ),
            )
        );?>
    </div>
    <div>
        <input type="submit" value="Зарегистрироваться" name="register_submit_button">
    </div>

    <p><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
    <p><span class="starrequired">*</span><?=GetMessage("AUTH_REQ")?></p>

</form>
<?endif?>
