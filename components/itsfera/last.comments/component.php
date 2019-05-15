<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arResult = array();



if ($this->StartResultCache(false))
{
    cmodule::includemodule('forum');
    cmodule::includemodule('blog');

    $arOrder = Array("DATE_CREATE" => "DESC");
    $arSelectedFields = Array("ID", "BLOG_ID", "POST_ID", "PARENT_ID", "AUTHOR_ID", "AUTHOR_NAME", "AUTHOR_EMAIL", "AUTHOR_IP", "AUTHOR_IP1", "TITLE", "POST_TEXT", "DATE_CREATE");
    $dbComment = CBlogComment::GetList($arOrder, $arFilter, false, array("nTopCount"=>10), $arSelectedFields);
    while ($arComment = $dbComment->Fetch())
    {
        $users[$arComment['AUTHOR_ID']] = $arComment['AUTHOR_ID'];
        $bPosts[$arComment['POST_ID']] = $arComment['POST_ID'];
        $blogComment[] = array(
            'USER_ID' => $arComment['AUTHOR_ID'],
            'POST_ID' => $arComment['POST_ID'],
        );
    }

    $res = CForumMessage::GetListEx(array("POST_DATE"=>"DESC"), array('NEW_TOPIC'=>'N'), false, 10);
    while($arMessage = $res->Fetch())
    {
        $users[$arMessage['AUTHOR_ID']] = $arMessage['AUTHOR_ID'];
        $fPosts[$arMessage['TOPIC_ID']] = $arMessage['TOPIC_ID'];
        $forumComment[] = array(
            'USER_ID' => $arMessage['AUTHOR_ID'],
            'POST_ID' => $arMessage['TOPIC_ID'],
            'MESSAGE_ID' => $arMessage['ID'],
            'USER' => $arMessage['NAME']
        );
    }


    $SORT = Array("ID" => "DESC");
    $arFilter = Array("ID" => $bPosts);
    $dbPosts = CBlogPost::GetList(
        $SORT,
        $arFilter
    );
    while ($arPost = $dbPosts->Fetch())
    {
        $arBlog = CBlog::GetByID($arPost['BLOG_ID']);

        foreach ($blogComment as &$item) {
            if($item['POST_ID'] == $arPost['ID']) {
                $item['TITLE'] = $arPost['TITLE'];
                $item['URL'] = '/blogs/'.$arBlog['URL'].'/'.$arPost['CODE'].'/';
            }
        }
    }
    $db_res = CForumTopic::GetList(array("SORT"=>"ASC"), array("@ID"=>$fPosts));
    while ($ar_res = $db_res->Fetch())
    {
        foreach ($forumComment as &$item) {
            if($item['POST_ID'] == $ar_res['ID']) {
                $item['TITLE'] = $ar_res['TITLE'];
                $item['URL'] = '/forum/messages/forum'.$ar_res['FORUM_ID'].'/message'.$item['MESSAGE_ID'].'/'.$ar_res['TITLE_SEO'].'#message'.$item['MESSAGE_ID'];
                $item['USER_ID'] = $ar_res['LAST_POSTER_ID'];
                if(!$item['USER'])
                    $item['USER'] = $ar_res['LAST_POSTER_NAME'];
            }
        }
    }


    $comments = array_merge($blogComment,$forumComment);
    $by = 'id';
    $order = 'desc';
    $rsUsers = CUser::getList($by, $order, array('ID'=>$users), $arParams);
    while ($rsUser = $rsUsers->Fetch())
    {
        foreach ($blogComment as &$comment) {
            if($comment['USER_ID'] == $rsUser['ID'])
                if(!$comment['USER'])
                    $comment['USER'] = $rsUser['NAME'].' '.$comment['LAST_NAME'];
        }
    }
    $arResult['BLOG'] = $blogComment;
    $arResult['FORUM'] = $forumComment;
    $this->IncludeComponentTemplate();
}