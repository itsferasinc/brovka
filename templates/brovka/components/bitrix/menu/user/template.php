<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<ul>
	<?foreach($arResult as $arItem):?>
		<?if ($arItem["PERMISSION"] > "D"):
            if($arItem['PARAMS']['BLOG_URL'] == 'Y')
                $arItem["LINK"] = '/blogs/'.BLOG_URL.'/';
            ?>
            <li>
                <i class="flaticon-<?=$arItem['PARAMS']['icon']?$arItem['PARAMS']['icon']:"man"?>"></i>
                <a class="notes-link" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
            </li>
		<?endif?>
	<?endforeach?>
</ul>