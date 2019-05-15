<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arResult = array();



if ($this->StartResultCache(false))
{
    cmodule::includemodule('forum');
    cmodule::includemodule('blog');

    $userId = $arParams['ID'];
    $rsUser = CUser::GetByID($userId);
    $arUser = $rsUser->Fetch();

    $arUser['FORUM'] = CForumUser::GetByUSER_ID($userId);
    $arUser['FORUM']['AVATAR_SRC'] = cfile::getpath($arUser['FORUM']['AVATAR']);
    $arResult['USER'] = $arUser;


    $this->IncludeComponentTemplate();
}