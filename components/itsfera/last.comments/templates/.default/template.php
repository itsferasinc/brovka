<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<h2>Комментарии на форумах</h2>

<?if (!empty($arResult['FORUM'])) { ?>
    <? foreach ($arResult['FORUM'] as $arItem) { ?>
        <div class="comment-last">
            <div class="comment-last-title"><a href="<?=$arItem['URL']?>"><?=$arItem['TITLE']?></a></div>
            <div class="comment-last-user"><i class="flaticon-dark-one-man"></i> <a href="/people/user/<?=$arItem['USER_ID']?>/profile/"><?=$arItem['USER']?></a></div>
        </div>
    <?}
}?>


<h2>Комментарии в блогах</h2>

<?if (!empty($arResult['BLOG'])) { ?>
    <? foreach ($arResult['BLOG'] as $arItem) { ?>
        <div class="comment-last">
            <div class="comment-last-title"><a href="<?=$arItem['URL']?>"><?=$arItem['TITLE']?></a></div>
            <div class="comment-last-user"><i class="flaticon-dark-one-man"></i> <a href="/people/user/<?=$arItem['USER_ID']?>/profile/"><?=$arItem['USER']?></a></div>
        </div>
    <?}
}?>

