<?
/**
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
?>

<div class="bx-auth-profile">

<?ShowError($arResult["strProfileError"]);?>
<?
if ($arResult['DATA_SAVED'] == 'Y')
	ShowNote(GetMessage('PROFILE_DATA_SAVED'));
?>
<script type="text/javascript">
<!--
var opened_sections = [<?
$arResult["opened"] = $_COOKIE[$arResult["COOKIE_PREFIX"]."_user_profile_open"];
$arResult["opened"] = preg_replace("/[^a-z0-9_,]/i", "", $arResult["opened"]);
if (strlen($arResult["opened"]) > 0)
{
	echo "'".implode("', '", explode(",", $arResult["opened"]))."'";
}
else
{
	$arResult["opened"] = "reg";
	echo "'reg'";
}
?>];
//-->

var cookie_prefix = '<?=$arResult["COOKIE_PREFIX"]?>';
</script>

<form method="post" name="form1" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data">
    <?=$arResult["BX_SESSION_CHECK"]?>
    <input type="hidden" name="lang" value="<?=LANG?>" />
    <input type="hidden" name="ID" value=<?=$arResult["ID"]?> />







	<?
	if($arResult["ID"]>0)
	{
    ?>
    <div class="info profile-form-info">
        <?
		if (strlen($arResult["arUser"]["TIMESTAMP_X"])>0)
		{
		?>
		<div class="profile-refresh-date">
			<p class="h4"><?=GetMessage('LAST_UPDATE')?></p>
			<p class="h4"><?=$arResult["arUser"]["TIMESTAMP_X"]?></p>
        </div>
		
		<?
		}
		?>
		<?
		if (strlen($arResult["arUser"]["LAST_LOGIN"])>0)
		{
		?>
		<div class="profile-last-auth">
			<p class="h4"><?=GetMessage('LAST_LOGIN')?></p>
			<p class="h4"><?=$arResult["arUser"]["LAST_LOGIN"]?></p>
		</div>
		<?
		}
        ?>
    </div>
    <?
	}
	?>

        <div class="inputs">
            <label>
                <span class="label-title"><?=GetMessage("main_profile_title")?></span>
                <input class="simple-input" type="text" name="TITLE" value="<?=$arResult["arUser"]["TITLE"]?>">
            </label>     
        </div>

        <div class="inputs">
            <label>
                <span class="label-title"><?=GetMessage("NAME")?></span>
                <input class="simple-input" type="text" name="NAME" value="<?=$arResult["arUser"]["NAME"]?>">
            </label>           
        </div>

        <div class="inputs">
            <label>
                <span class="label-title"><?=GetMessage("LAST_NAME")?></span>
                <input class="simple-input" type="text" name="LAST_NAME" value="<?=$arResult["arUser"]["LAST_NAME"]?>">
            </label>         
        </div>

        <div class="inputs">
            <label>
                <span class="label-title"><?=GetMessage("SECOND_NAME")?></span>
                <input class="simple-input" type="text" name="SECOND_NAME" value="<?=$arResult["arUser"]["SECOND_NAME"]?>">
            </label>           
        </div>

        <div class="inputs">
            <label>
                <span class="label-title"><?=GetMessage('EMAIL')?><?if($arResult["EMAIL_REQUIRED"]):?><span class="starrequired">*</span><?endif?></span>
                <input class="simple-input" type="text" name="EMAIL" value="<?=$arResult["arUser"]["EMAIL"]?>">
            </label>            
        </div>

        <div class="inputs">
            <label>
                <span class="label-title"><?=GetMessage("LOGIN")?><span class="starrequired">*</span></span>
                <input class="simple-input" type="text" name="LOGIN" value="<?=$arResult["arUser"]["LOGIN"]?>">
            </label>            
        </div>

        <div class="inputs">
            <label>
                <span class="label-title"><?=GetMessage("NEW_PASSWORD")?></span>
                <input class="simple-input" type="password" name="NEW_PASSWORD" value="">
            </label>            
        </div>

        <div class="inputs">
            <label>
                <span class="label-title"><?=GetMessage("NEW_PASSWORD_CONFIRM")?></span>
                 <input class="simple-input" type="password" name="NEW_PASSWORD_CONFIRM" value="">
            </label>          
        </div>

        <div class="inputs">
            <label>
                <span class="label-title"><?=GetMessage("USER_PROFESSION")?></span>
                <input class="simple-input" type="text" name="PERSONAL_PROFESSION" value="<?=$arResult["arUser"]["PERSONAL_PROFESSION"]?>">
            </label>            
        </div>

        <div class="inputs">
            <label>
                <span class="label-title"><?=GetMessage("USER_WWW")?></span>
                <input class="simple-input" type="text" name="PERSONAL_WWW" value="<?=$arResult["arUser"]["PERSONAL_WWW"]?>">
            </label>            
        </div>
        
        <div class="inputs">
            <label>
                <span class="label-title"><?=GetMessage("USER_BIRTHDAY_DT")?> (<?=$arResult["DATE_FORMAT"]?>)</span>
                <?$APPLICATION->IncludeComponent(
                    'bitrix:main.calendar',
                    'calendar',
                    array(
                        'SHOW_INPUT' => 'Y',
                        'FORM_NAME' => 'form1',
                        'INPUT_NAME' => 'PERSONAL_BIRTHDAY',
                        'INPUT_VALUE' => $arResult["arUser"]["PERSONAL_BIRTHDAY"],
                        'SHOW_TIME' => 'N'
                    ),
                    null,
                    array('HIDE_ICONS' => 'N')
                );?>
            </label>
        </div>

        <div class="inputs">
            <label>
                <span class="label-title"><?=GetMessage("USER_PHONE")?></span>
                <input class="simple-input profile-phone" type="text" name="PERSONAL_PHONE" value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>">
            </label>
        </div>

        <div class="inputs">
            <label>
                <span class="label-title"><?=GetMessage("USER_GENDER")?></span>
                <select name="PERSONAL_GENDER" class="selectpicker select-s">
                    <option value=""><?=GetMessage("USER_DONT_KNOW")?></option>
                    <option value="M"<?=$arResult["arUser"]["PERSONAL_GENDER"] == "M" ? " SELECTED=\"SELECTED\"" : ""?>><?=GetMessage("USER_MALE")?></option>
                    <option value="F"<?=$arResult["arUser"]["PERSONAL_GENDER"] == "F" ? " SELECTED=\"SELECTED\"" : ""?>><?=GetMessage("USER_FEMALE")?></option>
                </select>
            </label>
        </div>
        
        <div class="inputs">
            <span class="label-title"><?=GetMessage("USER_PHOTO")?></span>
            <span class="note">Выберите файл</span>
            <?=$arResult["arUser"]["PERSONAL_PHOTO_INPUT"]?>
            <?
            if (strlen($arResult["arUser"]["PERSONAL_PHOTO"])>0)
            {
                ?>
                <br />
                <?=$arResult["arUser"]["PERSONAL_PHOTO_HTML"]?>
                <?
            }
            ?>
        </div>

    <?/*
    <div class="inputs">
        <span class="label-title"><?=GetMessage("forum_AVATAR")?></span>
        <span class="note">Выберите файл</span>
        <?=$arResult["arForumUser"]["AVATAR_INPUT"]?>
        <?
        if (strlen($arResult["arForumUser"]["AVATAR"])>0)
        {
            ?>
            <br /><?=$arResult["arForumUser"]["AVATAR_HTML"]?>
            <?
        }
        ?>
    </div>
    */?>


	<?// ********************* User properties ***************************************************?>
	<?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>

		<?$first = true;?>
		<?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>
            <div class="inputs">
                <span class="label-title">
                    <?=$arUserField["EDIT_FORM_LABEL"]?>
                    <?if ($arUserField["MANDATORY"]=="Y"):?>
                        <span class="starrequired">*</span>
                    <?endif;?>
                </span>
                <?if($arUserField["USER_TYPE"]["USER_TYPE_ID"] == 'file'){?>
                <span class="note">Выберите файл</span>
                <?}?>
                <?$APPLICATION->IncludeComponent(
                        "bitrix:system.field.edit",
                        $arUserField["USER_TYPE"]["USER_TYPE_ID"],
                        array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField), null,
                        array("HIDE_ICONS"=>"Y"));
                    ?>
            </div>
		<?endforeach;?>
	<?endif;?>
	<?// ******************** /User properties ***************************************************?>
	<?/*<p><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>*/?>
    <br>
	<p class="buttons-group"><input class="note" type="submit" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD"))?>">&nbsp;&nbsp;<input class="note" type="reset" value="<?=GetMessage('MAIN_RESET');?>"></p>
</form>
<?
if($arResult["SOCSERV_ENABLED"])
{
	/*$APPLICATION->IncludeComponent("bitrix:socserv.auth.split", "social", array(
			"SHOW_PROFILES" => "Y",
			"ALLOW_DELETE" => "Y"
		),
		false
	);*/
}
?>
</div>