<?

AddEventHandler('main', 'OnBeforeEventSend', Array("itsferaHandlers", "OnBeforeEventSendHandler"));
AddEventHandler("main", "OnBeforeUserAdd", Array("itsferaHandlers", "OnBeforeUserAddHandler"));
AddEventHandler("main", "OnAfterUserAdd", Array("itsferaHandlers", "OnAfterUserAddHandler"));
AddEventHandler("main", "OnBeforeUserUpdate", Array("itsferaHandlers", "OnBeforeUserUpdateHandler"));
AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("itsferaHandlers", "OnAfterIBlockElementAddHandler"));
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("itsferaHandlers", "OnBeforeIBlockElementAddHandler"));
AddEventHandler("blog", "OnBeforePostAdd", Array("itsferaHandlers", "OnBeforePostAddUpdateHandler"));
AddEventHandler("blog", "OnBeforePostUpdate", Array("itsferaHandlers", "OnBeforePostAddUpdateHandler"));
AddEventHandler("forum", "onBeforeTopicAdd", Array("itsferaHandlers", "onBeforeTopicAddUpdateHandler"));
AddEventHandler("forum", "onBeforeTopicUpdate", Array("itsferaHandlers", "onBeforeTopicAddUpdateHandler"));



class itsferaHandlers
{
    function OnBeforeUserAddHandler(&$arFields)
    {
        $arFields["LOGIN"] = $arFields["EMAIL"];
    }
    function OnBeforeUserUpdateHandler(&$arFields)
    {
        $arFields["forum_AVATAR"] = $arFields["PERSONAL_PHOTO"];
        $arFields["blog_AVATAR"] = $arFields["PERSONAL_PHOTO"];

    }
    function OnAfterUserAddHandler(&$arFields)
    {
        CModule::IncludeModule("blog");
        $login = explode("@",$arFields["LOGIN"]);
        $login = substr(preg_replace("/[^a-z0-9]/iu", '', $login[0]),0,10);
        $arFields = array(
            "NAME" => 'Блог пользователя '.$login,
            "DESCRIPTION" => '',
            "GROUP_ID" => '1',
            "ENABLE_COMMENTS" => 'Y',
            "ENABLE_IMG_VERIF" => 'Y',
            "EMAIL_NOTIFY" => 'Y',
            "ENABLE_RSS" => "N",
            "URL" => $login,
            "ACTIVE" => "Y",
            "OWNER_ID" => $arFields["ID"],
            "EDITOR_USE_LINK"  => "Y",
            "EDITOR_USE_IMAGE"  => "Y",
            "EDITOR_USE_VIDEO"  => "Y",
            "PERMS_POST" => Array("1" => BLOG_PERMS_READ, "2" => BLOG_PERMS_READ),
            "PERMS_COMMENT" => Array("1" => BLOG_PERMS_READ, "2" => BLOG_PERMS_WRITE)
        );
        $newID = CBlog::Add($arFields);
    }
    function OnBeforeIBlockElementAddHandler(&$arFields){

        switch($arFields['IBLOCK_ID']) {
            case "6": // гиды
                GLOBAL $USER;
                $transParams = array("replace_space"=>"-","replace_other"=>"-");
                $arFields['NAME'] = $arFields['PROPERTY_VALUES'][36];
                $arFields['CODE'] = Cutil::translit($arFields['NAME'],"ru",$transParams);
                $arFields['PROPERTY_VALUES'][37] = $USER->getid();
                break;
        }
    }
    function OnAfterIBlockElementAddHandler($arFields){
        // только первые значения свойств возмем
        foreach ($arFields['PROPERTY_VALUES'] as $id => $prop)
            $PROPERTIES[$id] = $prop[key($prop)]['VALUE'];

        // Заявка от клиента
        if($arFields['IBLOCK_ID'] == getIBlockIdByCode("requests")) {
            $arEventFields = array(
                "NAME" => $arFields['NAME'],
                "PHONE" => $PROPERTIES[13],
                "EMAIL" => $PROPERTIES[12],
                "COMMENT" => $arFields['PREVIEW_TEXT'],
            );
            //CEvent::Send("ITS_FORM_REQUEST", SITE_ID, $arEventFields);
        }
    }
    function onBeforeTopicAddUpdateHandler(&$arFields){
        $arFields['UF_DATE_CREATE'] = ConvertTimeStamp(time(), "SHORT");
    }
    function OnBeforePostAddUpdateHandler(&$arFields){
        $date = explode(" ",$arFields['DATE_PUBLISH']);
        $arFields['UF_DATE_CREATE'] = $date[0];
    }
}
