<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="content-block-links-edit row padding edit-themes-form">
    <div class="">
        Поиск:
        <input class="themes-filter-box simple-input">
    </div>
    <div class=" themes-list">
    <?foreach($arResult["ITEMS"] as $arItem){
        $checked = false;
        if($_REQUEST['type'] == 'blog')
            if(in_array($_REQUEST['id'],$arItem['PROPERTIES']['T_BLOGS']['VALUE']))
                $checked = 'checked';
        if($_REQUEST['type'] == 'forum')
            if(in_array($_REQUEST['id'],$arItem['PROPERTIES']['T_FORUMS']['VALUE']))
                $checked = 'checked';
        ?>
        <div class="col-xs-6 item">
            <label>
                <input type="checkbox" class="edit-themes-inputs" name="items[]" <?=$checked?> value="<?=$arItem['ID']?>" data-name="<?=toUpper($arItem['NAME'])?>">
                <?=$arItem['NAME']?>
            </label>
        </div>
    <?}?>
    </div>
    <div class=" fixedblock">
        <span value="Сохранить" class="note button" onclick="editThemes(<?=$_REQUEST['id']?>, '<?=$_REQUEST['type']?>')">Сохранить</span>
    </div>
    <script>
        $(".themes-filter-box").keyup(function(){
            if($(this).val().length > 0) {
                console.log("search", $(this).val().toUpperCase());
                $(".edit-themes-form .item").hide();
                $(".edit-themes-inputs[data-name*='" + $(this).val().toUpperCase() + "']").closest(".item").show();
            }
            else
                $(".edit-themes-form .item").show();
        });
    </script>
</div>
