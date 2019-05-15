<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

GLOBAL $FAVORITES;
$arResult = array();

CModule::IncludeModule("blog");
CModule::IncludeModule("forum");
CModule::IncludeModule("iblock");
if(!$_REQUEST['page'])
    $_REQUEST['page'] = 0;

if ($this->StartResultCache(false)) {
    if (!CModule::IncludeModule("blog") || !CModule::IncludeModule("forum"))
        return;

    if($arParams['SHOW_BLOG'] == "Y"){

        switch ($_REQUEST['sortby']) {
            case 'new':
                $SORT = Array(
                    "UF_DATE_CREATE" => "DESC",
                    "RATING_TOTAL_VALUE" => "DESC"
                );
                break;
            case 'popular':
                $SORT = Array(
                    //"UF_DATE_CREATE" => "DESC",
                    "RATING_TOTAL_VALUE" => "DESC"
                );
                break;
            case 'commented':
                $SORT = Array(
                    "NUM_COMMENTS" => "DESC",
                );
                break;
            default:
                $SORT = Array(
                    "UF_DATE_CREATE" => "DESC",
                    "RATING_TOTAL_VALUE" => "DESC",
                );
        }

        $arFilter = Array("ACTIVE" => "Y");
        if($arParams['DRAFTS'] == 'Y') {
            $arFilter['PUBLISH_STATUS'] = "K";
            $arFilter['AUTHOR_ID'] = $USER->getid();
        }
        else
            $arFilter['PUBLISH_STATUS'] = "P";
        if($arParams['BLOG_IDS'])
            $arFilter['ID'] = $arParams['BLOG_IDS'];
        elseif(isset($arParams['BLOG_IDS']) && is_array($arParams['BLOG_IDS']) == 0)
            $arFilter['ID'] = 1;
        if($_REQUEST['arFilter'] == 'guides') {
            $arFilter['AUTHOR_ID'] = array();
            if($_REQUEST['guide']) {
                $res = CIBlockElement::GetList(Array(),
                    Array("IBLOCK_ID"=>getIBlockIdByCode("guide"), "CODE"=>$_REQUEST['guide'], "ACTIVE"=>"Y"),
                    false, false,
                    Array("ID", "NAME", "PROPERTY_USER_ID")
                );
                if($ob = $res->GetNextElement())
                {
                    $arFields = $ob->GetFields();
                    $arFilter['AUTHOR_ID'] = $arFields['PROPERTY_USER_ID_VALUE'];
                }
            }
            else {
                $rsUser = CUser::GetList(
                    $userBy,
                    $userOrder,
                    array('GROUPS_ID' => array(12)),
                    $userParams
                );
                while ($aRuser = $rsUser->Fetch())
                    $arFilter['AUTHOR_ID'][] = $aRuser['ID'];
            }
        }
        if($arParams['HIDEONINDEX'] == "Y")
            $arFilter['UF_HIDEONINDEX'] = "Y";
        $SELECT = array("ID", "BLOG_ID", "USER_ID", "URL", "PUBLISH_STATUS","TITLE", "DATE_PUBLISH", "NUM_COMMENTS",
            "DATE_PUBLISH_DAY", "RATING_TOTAL_POSITIVE_VOTES", "RATING_TOTAL_VOTES", "UF_DATE_CREATE", "UF_HIDEONINDEX"
        );
        $dbPosts = CBlogPost::GetList(
            $SORT,
            $arFilter,
            false,
            ($arParams['SHOW_ALL']=="Y")?false:Array("nPageSize" => 200),
            $SELECT
        );
        while ($arPost = $dbPosts->Fetch()) {
            $arBlog = CBlog::GetByID($arPost['BLOG_ID']);
            //echo'<pre>';print_r($arPost);echo"</pre>";
            //echo '<b>'.$arPost['UF_DATE_CREATE'].'</b> - '.$arPost['TITLE'].' [ '.$arPost['UF_HIDEONINDEX'].' ]<br>';
            $posts[] = array(
                'ID' => $arPost['ID'],
                'BLOG_ID' => $arPost['BLOG_ID'],
                'URL' => $arBlog['URL'],
                'USER_ID' => $arPost['AUTHOR_ID'],
                'TITLE' => $arPost['TITLE'],
                'TYPE' => 'blog',
                'RATING' => $arPost['RATING_TOTAL_VALUE'],
                'COMMENTS' => $arPost['NUM_COMMENTS'],
                'DATE' => $arPost['UF_DATE_CREATE'],
                'DATE_STMP' => MakeTimeStamp($arPost['UF_DATE_CREATE'], "DD.MM.YYYY HH:MI:SS"),
                'FULLDATE' => $arPost['DATE_PUBLISH'],
                'FULLDATE_STMP' => MakeTimeStamp($arPost['DATE_PUBLISH'], "DD.MM.YYYY HH:MI:SS")
            );
        }
    }
    if($arParams['SHOW_FORUM'] == "Y") {
        switch ($_REQUEST['sortby']) {
            case 'new':
                $SORT = Array(
                    "UF_DATE_CREATE" => "DESC",
                    //"TOTAL_VALUE" => "DESC"
                );
                break;
            case 'popular':
                $SORT = Array(
                    //"UF_DATE_CREATE" => "DESC",
                    "TOTAL_VALUE" => "DESC"
                );
                break;
            case 'commented':
                $SORT = Array(
                    "NUM_COMMENTS" => "DESC",
                );
                break;
            default:
                $SORT = Array(
                    "UF_DATE_CREATE" => "DESC",
                    //"TOTAL_VALUE" => "DESC"
                );
        }
        $arFilter = array("ACTIVE" => "Y");
        if($arParams['FORUM_IDS'])
            $arFilter['ID'] = $arParams['FORUM_IDS'];
        elseif(isset($arParams['FORUM_IDS']) && is_array($arParams['FORUM_IDS']) == 0)
            $arFilter['ID'] = 1;
        if($arParams['HIDEONINDEX'] == "Y")
            $arFilter['UF_HIDEONINDEX'] = 1;
        if($arParams['INDEX'] == "Y")
            $arFilter['!FORUM_ID'] = array(262,261,255,241,242,244,243,245,246,251,249,252,250,247,248,254,253,256,257,258,259,260,263,264);
        $res = ITSForumTopic::GetList($SORT, $arFilter, false, ($arParams['SHOW_ALL']=="Y")?false:200);
        while ($arTopic = $res->Fetch()) {
            //echo '<b>'.$arTopic['START_DATE'].'</b> ('.$arTopic['UF_DATE_CREATE'].') - '.$arTopic['TITLE'].'('.$arTopic['ID'].') [ '.$arTopic['TOTAL_VALUE'].' ]<br>';
            //echo '<b>'.$arTopic['START_DATE'].'</b> - '.$arTopic['TITLE'].' [ '.$arTopic['UF_HIDEONINDEX'].' ]<br>';
            //echo'<pre>';print_r($arTopic);echo"</pre>";
            $posts[] = array(
                'ID' => $arTopic['ID'],
                'FORUM_ID' => $arTopic['FORUM_ID'],
                'TITLE' => $arTopic['TITLE'],
                'TYPE' => 'forum',
                'RATING' => $arTopic['TOTAL_VALUE'],
                'COMMENTS' => $arTopic['POSTS'],
                'DATE' => $arTopic['UF_DATE_CREATE'],
                'DATE_STMP' => MakeTimeStamp($arTopic['UF_DATE_CREATE'], "DD.MM.YYYY HH:MI:SS"),
                'FULLDATE' => $arTopic['START_DATE'],
                'FULLDATE_STMP' => MakeTimeStamp($arTopic['START_DATE'], "DD.MM.YYYY HH:MI:SS")
            );
        }
    }

    function array_orderby()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }
    switch ($_REQUEST['sortby']) {
        case 'popular':
            $sorted = array_orderby($posts, 'RATING', SORT_DESC, 'RATING', SORT_DESC);
            break;
        case 'new':
            $sorted = array_orderby($posts, 'FULLDATE_STMP', SORT_DESC, 'RATING', SORT_DESC);
            break;
        case 'commented':
            $sorted = array_orderby($posts, 'COMMENTS', SORT_DESC, 'COMMENTS', SORT_DESC);
            break;
        default:
            $sorted = array_orderby($posts, 'DATE_STMP', SORT_DESC, 'RATING', SORT_DESC);
    }

    //$arResult['ITEMS'] = array_slice($sorted, $_REQUEST['page']*$arParams['ONPAGE'], $arParams['ONPAGE']);
    //$arResult['NavPageMaxpages'] = floor(count($sorted)/$arParams['ONPAGE']);
    //$arResult['NavPageNomer'] = $_REQUEST['page'];
    //echo"<pre>";print_r($sorted);echo "</pre>";
    CPageOption::SetOptionString("main", "nav_page_in_session", "N");
    $rsDirContent = new CDBResult;
    $rsDirContent->InitFromArray($sorted);
    $rsDirContent->NavStart($arParams['ONPAGE'], false);
    define(BX_DISABLE_INDEX_PAGE, true);
    //$arResult["NAV_STRING"] = $rsDirContent->GetPageNavString("", 'modern');
    //$arResult["PAGE_START"] = $rsDirContent->SelectedRowsCount() - ($rsDirContent->NavPageNomer - 1) * $rsDirContent->NavPageSize;
    //$arResult['NavPageMaxpages'] = floor(count($sorted)/$arParams['ONPAGE']);
    //$arResult["NavPageNomer"] = $rsDirContent->NavPageNomer-1;
    $arResult["RESULT"] = $rsDirContent;



    $rsUser = CUser::GetByID($USER->getid());
    $arUser = $rsUser->Fetch();

    $FAVORITES = array_filter(explode(",",$arUser['UF_FAVORITES']));

    $this->IncludeComponentTemplate();
}