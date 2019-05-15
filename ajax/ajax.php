<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("iblock");

global $APPLICATION, $USER;
$user_id = $USER->getid();
$result = array("error" => 0);


switch($_REQUEST["action"]){
	case "editThemes":
	    $update = false;
        if($_REQUEST['type'] == 'forum')
            $ENTITY_TYPE_ID = "T_FORUMS";
        else
            $ENTITY_TYPE_ID = "T_BLOGS";

        $arSelect = Array("ID", "IBLOCK_ID", "NAME");
        $arFilter = Array("IBLOCK_ID"=>getIBlockIdByCode("thems"), "PROPERTY_".$ENTITY_TYPE_ID=>$_REQUEST['id'], "ACTIVE"=>"Y");
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        while($ob = $res->GetNextElement()){
            $arFields = $ob->GetFields();
            $foundlinks[] = $arFields['ID'];
            $posts = array();
            $resProp = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $arFields['ID'], "sort", "asc", array("CODE" => $ENTITY_TYPE_ID));
            while ($obProp = $resProp->GetNext())
                $posts[$obProp['VALUE']] = $obProp['VALUE'];

            if(!in_array($arFields['ID'],$_REQUEST['themes'])){
                unset($posts[$_REQUEST['id']]);
                $update = true;
            }
            //удаление если найдены, а нет в запросе

            if($update) {
                CIBlockElement::SetPropertyValueCode($arFields['ID'], $ENTITY_TYPE_ID, $posts);
                $result['message'][] = "Темы удалена";
            }
        }
        // добавление если найдены
        foreach ($_REQUEST['themes'] as $themeId) {
            if(!in_array($themeId,$foundlinks)) {
                $VALUES = array();
                $resProp = CIBlockElement::GetProperty(getIBlockIdByCode("thems"), $themeId, "sort", "asc", array("CODE" => $ENTITY_TYPE_ID));
                while ($ob = $resProp->GetNext())
                    $VALUES[$ob['VALUE']] = $ob['VALUE'];
                $VALUES[] = $_REQUEST['id'];
                CIBlockElement::SetPropertyValueCode($themeId, $ENTITY_TYPE_ID, $VALUES);
                $result['message'][] = "Тема добавлена";
            }
        }

	    break;
	case "addlike":
        if ($USER->IsAuthorized()) {
            if ($_REQUEST['type'] == 'forum')
                $ENTITY_TYPE_ID = "FORUM_TOPIC";
            else
                $ENTITY_TYPE_ID = "BLOG_POST";
            $arAddVote = array(
                "ENTITY_TYPE_ID" => $ENTITY_TYPE_ID,
                "ENTITY_ID" => $_REQUEST['topic'],
                "VALUE" => 1,
                "USER_ID" => $user_id,
                "USER_IP" => $_SERVER['REMOTE_ADDR'],
            );
            $res = CRatings::AddRatingVote($arAddVote);
            if ($res)
                $result['message'] = "Голос + добавлен";
            else
                $result['error'] = 1;
            $result['votes'] = CRatings::GetRatingVoteResult($ENTITY_TYPE_ID, $_REQUEST['topic']);
        }else{
            $result['message'] = "Голосование возможно только для пользователей, пройдите регистрацию.";
            $result['error'] = 1;
        }
		break;
	case "deletelike":
        if ($USER->IsAuthorized()) {
            if ($_REQUEST['type'] == 'forum')
                $ENTITY_TYPE_ID = "FORUM_TOPIC";
            else
                $ENTITY_TYPE_ID = "BLOG_POST";
            $arAddVote = array(
                "ENTITY_TYPE_ID" => $ENTITY_TYPE_ID,
                "ENTITY_ID" => $_REQUEST['topic'],
                "VALUE" => -1,
                "USER_ID" => $user_id,
                "USER_IP" => $_SERVER['REMOTE_ADDR'],
            );
            $res = CRatings::AddRatingVote($arAddVote);
            if ($res)
                $result['message'] = "Голос - добавлен";
            else
                $result['error'] = 1;
            $result['votes'] = CRatings::GetRatingVoteResult($ENTITY_TYPE_ID, $_REQUEST['topic']);
        }else{
            $result['message'] = "Голосование возможно только для пользователей, пройдите регистрацию.";
            $result['error'] = 1;
        }
		break;
    case "favorites_add":
        if ($USER->IsAuthorized()) {
            if ($_REQUEST['type'] == 'forum')
                $_REQUEST['topic'] = "f" . $_REQUEST['topic'];
            else
                $_REQUEST['topic'] = "b" . $_REQUEST['topic'];
            $rsUser = CUser::GetByID($user_id);
            $arUser = $rsUser->Fetch();
            $favs = array_filter(explode(",", $arUser['UF_FAVORITES']));
            $favs[] = $_REQUEST['topic'];
            $favs = array_unique($favs);
            $user = new CUser;
            $user->Update($user_id, Array("UF_FAVORITES" => implode(",", $favs)));
            if ($user->LAST_ERROR)
                $result['error'] = $user->LAST_ERROR;
            else
                $result['message'] = "Добавлено в избранное";
            break;
        }else{
            $result['message'] = "Добавление в избранное возможно только для пользователей, пройдите регистрацию.";
            $result['error'] = 1;
        }
    case "favorites_del":
        if ($USER->IsAuthorized()) {
            if ($_REQUEST['type'] == 'forum')
                $_REQUEST['topic'] = "f" . $_REQUEST['topic'];
            else
                $_REQUEST['topic'] = "b" . $_REQUEST['topic'];
            $rsUser = CUser::GetByID($user_id);
            $arUser = $rsUser->Fetch();
            $favs = array_filter(explode(",", $arUser['UF_FAVORITES']));
            foreach ($favs as $id => $val)
                if ($val == $_REQUEST['topic'])
                    unset($favs[$id]);
            $user = new CUser;
            $user->Update($user_id, Array("UF_FAVORITES" => implode(",", $favs)));
            if ($user->LAST_ERROR)
                $result['error'] = $user->LAST_ERROR;
            else
                $result['message'] = "Добавлено в избранное";
        }else{
            $result['message'] = "Добавление в избранное возможно только для пользователей, пройдите регистрацию.";
            $result['error'] = 1;
        }
        break;
    case "topic_hide_index":
        CModule::IncludeModule("forum");
        CModule::IncludeModule("blog");
        if ($_REQUEST['type'] == 'forum')
        {
            global $USER_FIELD_MANAGER;
            $USER_FIELD_MANAGER->Update( 'FORUM_TOPIC', $_REQUEST['topic'], array(
                'UF_HIDEONINDEX'  => 1
            ));
            $result['message'] = "Запись убрана с главной";
            $result['error'] = 0;
        }
        else
        {
            $arFields = array("UF_HIDEONINDEX" => "Y");
            $updateID = CBlogPost::Update($_REQUEST['topic'], $arFields);
            if($updateID) {
                $result['message'] = "Запись убрана с главной";
                $result['error'] = 0;
            }else{
                $result['message'] = "Ошибка";
                $result['error'] = 1;
            }
        }
        break;
    case "topic_unhide_index":

        CModule::IncludeModule("forum");
        CModule::IncludeModule("blog");
        if ($_REQUEST['type'] == 'forum')
        {
            global $USER_FIELD_MANAGER;
            $USER_FIELD_MANAGER->Update( 'FORUM_TOPIC', $_REQUEST['topic'], array(
                'UF_HIDEONINDEX'  => false
            ));
            $result['message'] = "Запись возвращена на главную";
            $result['error'] = 0;
        }
        else
        {
            $arFields = array("UF_HIDEONINDEX" => false);
            $updateID = CBlogPost::Update($_REQUEST['topic'], $arFields);
            if($updateID) {
                $result['message'] = "Запись возвращена на главную";
                $result['error'] = 0;
            }else{
                $result['message'] = "Ошибка";
                $result['error'] = 1;
            }
        }

        break;
    case "guideShowContacts":
        $resProp = CIBlockElement::GetProperty(getIBlockIdByCode("guide"), $_REQUEST['guide'], "sort", "asc", array("CODE" => "PHONE"));
        while ($obProp = $resProp->GetNext())
            $PHONES[] = $obProp['VALUE'];
        $resProp = CIBlockElement::GetProperty(getIBlockIdByCode("guide"), $_REQUEST['guide'], "sort", "asc", array("CODE" => "EMAIL"));
        while ($obProp = $resProp->GetNext())
            $EMAILS[] = $obProp['VALUE'];


        $result['contacts']['email'] = implode(", ",$EMAILS);
        $result['contacts']['phones'] = implode(", ",$PHONES);
        $result['error'] = 0;

        break;
}

echo json_encode($result);
