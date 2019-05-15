<?

define("DEBUG_MODE",true);


function myPrintR( $value , $file="", $line="", $title)
{
    if (DEBUG_MODE===true){
        if ( is_bool($value)) $value = $value?"true":"false";
        echo '<b>'.$title.'</b><br>';
        echo $file.' '.$line.'<pre>';
        print_r( $value );
        echo '</pre>';

    }
}

function dump( $value , $file="", $line="", $title="")
{
    return myPrintR( $value, $file, $line, $title);
}

/**
 * Генерация пароля
 * @param int $length
 * @return string
 */
function generatePassword($length = 8, $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
{
    $count = mb_strlen($chars);
    for ($i = 0, $result = ''; $i < $length; $i++) {
        $index = rand(0, $count - 1);
        $result .= mb_substr($chars, $index, 1);
    }

    return $result;
}

/*
 * Вспомогательные функции.
 * */

/** Склонение существительных с числительными
 * @param int $n число
 * @param string $form1 Единственная форма: 1 секунда
 * @param string $form2 Двойственная форма: 2 секунды
 * @param string $form5 Множественная форма: 5 секунд
 * @return string Правильная форма
 */
function pluralForm($n, $form1, $form2, $form5) {
    $n = abs($n) % 100;
    $n1 = $n % 10;
    if ($n > 10 && $n < 20) return $form5;
    if ($n1 > 1 && $n1 < 5) return $form2;
    if ($n1 == 1) return $form1;
    return $form5;
}

function arrayToString($array)
{
    $sResult = "";
    foreach($array as $k=>$val){
        $val = htmlspecialchars ( $val );
        $sResult.="[".$k."=\"".$val."\"]";
        if ( isset($array[$k+1])) $sResult.="\n";
    }
    return $sResult;
}

function stringToArray($string)
{
    $arResult= array();
    $string = mb_substr($string,1,-1);
    $arStrings = explode("][",$string);

    foreach ($arStrings as $string){ //NAME="Майкл"
        preg_match( '#([^=].*)="(.*)"#', $string, $arMatches);
        if ( $arMatches[1] && $arMatches[2]){
            $arResult[ $arMatches[1] ] = htmlspecialchars_decode ($arMatches[2]);
        }
    }
    return $arResult;

}


/**
 * Размер файла в кило/мега/гига/тера/пета байтах
 * @param int $filesize — размер файла в байтах
 *
 * @return string — возвращаем размер файла в Б, КБ, МБ, ГБ или ТБ
 */
function filesize_format($filesize)
{
    $formats = array('б','Кб','Мб','Гб','Тб');// варианты размера файла
    $format = 0;// формат размера по-умолчанию

    // прогоняем цикл
    while ($filesize > 1024 && count($formats) != ++$format)
    {
        $filesize = round($filesize / 1024, 2);
    }

    // если число большое, мы выходим из цикла с
    // форматом превышающим максимальное значение
    // поэтому нужно добавить последний возможный
    // размер файла в массив еще раз
    $formats[] = 'Тб';

    return $filesize.$formats[$format];
}

//получаем код значения пользовательского свойства типа список
function getUserFieldEnumIdByCode($sXmlId)
{
    $rsField = CUserFieldEnum::GetList(array(), array("XML_ID" => $sXmlId));
    if($arEnumField = $rsField->GetNext()){
        return $arEnumField['ID'];
    }
    return false;
}


//получает айдишник значения в свойстве типа список
function getPropertyEnumIdByCode($propertyCode, $enumCode,$iIblockId)
{
    CModule::IncludeModule("iblock");

    if (strlen($enumCode)>0 && strlen($propertyCode)>0) {
        $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"),
            Array("IBLOCK_ID"=>$iIblockId, "CODE"=>$propertyCode, 'EXTERNAL_ID'=>$enumCode));
        if($enum_fields = $property_enums->GetNext()){
            return $enum_fields["ID"];
        }
    }
    return false;

}

//получает айдишник значения в свойстве типа список
function getPropertyEnumIdByValue($propertyCode, $sValue,$iIblockId)
{
    CModule::IncludeModule("iblock");

    if (strlen($sValue)>0 && strlen($propertyCode)>0) {
        $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"),
            Array("IBLOCK_ID"=>$iIblockId, "CODE"=>$propertyCode, 'VALUE'=>$sValue));
        if($enum_fields = $property_enums->GetNext()){
            return $enum_fields["ID"];
        }
    }
    return false;

}

//получает айдишник значения в свойстве типа список
function getPropertyEnumCodeById($propertyCode, $enumId,$iIblockId)
{
    CModule::IncludeModule("iblock");

    if (intval($enumId)>0 && strlen($propertyCode)>0) {
        $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"),
            Array("IBLOCK_ID"=>$iIblockId, "CODE"=>$propertyCode, 'ID'=>intval($enumId)));
        if($enum_fields = $property_enums->GetNext()){
            return $enum_fields["EXTERNAL_ID"];
        }
    }
    return "";

}



function getPropertyIdByCode ( $propertyCode,  $iIblockID = false, $bRefreshCache = false )
{
    if ( strlen($propertyCode)>0 ){

        $obCache = new CPHPCache;
        $iReturnId = 0;
        $CACHE_ID = 'getPropertyIdByCode'.$propertyCode.$iIblockID;
        $iCacheTime = 10800; //3 часа

        if($obCache->StartDataCache($iCacheTime, $CACHE_ID)):

            if(CModule::IncludeModule('iblock')) {
                $arFilter = Array("CODE"=>$propertyCode);
                if ($iIblockID){
                    $arFilter['IBLOCK_ID'] = $iIblockID;
                }

                $properties = CIBlockProperty::GetList(Array("id"=>"desc", "name"=>"asc"), $arFilter);
                if ($prop_fields = $properties->GetNext()){
                    $iReturnId = $prop_fields["ID"];
                }
            }

            $obCache->EndDataCache($iReturnId);
        else:
            $iReturnId = $obCache->GetVars();
        endif;
        unset($obCache);

        return $iReturnId;

    }
    return false;
}


/**
 *
 * Возвращает ID раздела по его коду
 *
 * @param $sIBlockCode
 * @param bool $bRefreshCache
 * @return int
 */

function getSectionIdByCode($sSectionCode,  $bRefreshCache = false)
{
    $obCache = new CPHPCache;
    $iReturnId = 0;
    $CACHE_ID = 'getSectionIdByCode'.$sSectionCode;
    $iCacheTime = 10800; //3 часа

    if($obCache->StartDataCache($iCacheTime, $CACHE_ID)):

        if(CModule::IncludeModule('iblock')) {
            $arFilter = Array('CODE'=>$sSectionCode);
            $db_list = CIBlockSection::GetList(Array(), $arFilter, false,array("ID"));
            if($ar_result = $db_list->GetNext()){
                $iReturnId = $ar_result['ID'];
            }
        }

        $obCache->EndDataCache($iReturnId);
    else:
        $iReturnId = $obCache->GetVars();
    endif;
    unset($obCache);
    return $iReturnId;
}

/**
 *
 * Возвращает ID инфоблока по символьному коду
 *
 * @param $sIBlockCode
 * @param bool $bRefreshCache
 * @return int
 */
function getIBlockIdByCode($sIBlockCode, $bRefreshCache = false)
{
    $arFastIblocks = array(
        "goods"=>16,
        "brands"=>5,
        "goods_offers"=>17,
        "shops"=>4,
		"preorder"=>28 
    );
    if ( array_key_exists($sIBlockCode,$arFastIblocks))
        return $arFastIblocks[$sIBlockCode];


    $obCache = new CPHPCache;
    $iReturnId = 0;
    $CACHE_ID = 'getIBlockIdByCode'.$sIBlockCode.'_______';
    $iCacheTime = 10800; //3 часа

    if($obCache->StartDataCache($iCacheTime, $CACHE_ID)):

        if(CModule::IncludeModule('iblock')) {
            $arFilter = array(
                'CODE' => $sIBlockCode,
                'ACTIVE' => 'Y',
                'CHECK_PERMISSIONS' => 'N'
            );
            $dbItems = CIBlock::GetList(array('ID' => 'ASC'), $arFilter, false);
            if($arItem = $dbItems->Fetch()) {
                $iReturnId = intval($arItem['ID']);
            }
        }

        $obCache->EndDataCache($iReturnId);
    else:
        $iReturnId = $obCache->GetVars();
    endif;
    unset($obCache);
    return $iReturnId;
}


//функционал вставки слайдера в контент [slider]section_id[/slider]
function sliderIncluder(&$content,$sliderIblockId,$sliderStartKeyWord,$sliderEndKeyWord)
{
    $width = 167;
    $height = 127;

    $widthBig = 1000;
    $heightBig = 800;

    CModule::IncludeModule("iblock");
    $from = 0;
    while ($startPos = mb_strpos($content,$sliderStartKeyWord,$from)){    //

        $startPos += strlen($sliderStartKeyWord);
        $endPos = mb_strpos($content,$sliderEndKeyWord) - $startPos ;
        $sectionId = mb_substr($content,$startPos,$endPos);

        if ($sectionId>0){
            $arFilter = Array('IBLOCK_ID'=>$sliderIblockId, 'ID'=>$sectionId);
            $db_list = CIBlockSection::GetList(Array(), $arFilter, true);
            if($ar_res = $db_list->GetNext()){

                $resultHtml = "";
                $arSelect = Array("ID", "NAME", "PREVIEW_PICTURE","DETAIL_PICTURE");
                $arFilter = Array("IBLOCK_ID"=>$sliderIblockId, "ACTIVE"=>"Y","SECTION_ID"=>$sectionId);

                $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>100), $arSelect);

                $resultHtml.="<div class='photos_block'>";
                while($ob = $res->GetNextElement()){
                    $arFields = $ob->GetFields();
                    //$arFields["PREVIEW_PICTURE"] = CFile::GetFileArray($arFields["PREVIEW_PICTURE"]);


                    $file = CFile::ResizeImageGet($arFields["DETAIL_PICTURE"], array('width'=>$width, 'height'=>$height), BX_RESIZE_IMAGE_EXACT, true); //BX_RESIZE_IMAGE_PROPORTIONAL
                    $fileBig = CFile::ResizeImageGet($arFields["DETAIL_PICTURE"], array('width'=>$widthBig, 'height'=>$heightBig), BX_RESIZE_IMAGE_PROPORTIONAL, true); //

                    $resultHtml.='<div class="photo_item"><a rel="gallery" class="highslide " onclick="return hs.expand(this)" href="'.$fileBig['src'].'"><img src="'.$file["src"].'" data-desc="'.$arFields["NAME"].'"></a></div>';

                }
                $resultHtml.='</div>';
            }else {

                $resultHtml = '';

            }



            //вставляем полученную фигную в детейл текст
            $returnText = mb_substr($content,0,$startPos-strlen($sliderStartKeyWord));
            $returnText .= $resultHtml;
            $returnText .= mb_substr($content,$startPos+$endPos+strlen($sliderStartKeyWord)+1);
            $content = $returnText;

        }
        $from = $startPos;
    }
}


//функционал вставки слайдера в контент [slider]section_id[/slider]
function photoIncluder(&$content,$photoIblockId,$photoStartKeyWord,$photoEndKeyWord)
{
    $width = 778;
    $height = 431;

    CModule::IncludeModule("iblock");
    $from = 0;
    while ($startPos = mb_strpos($content,$photoStartKeyWord,$from)){    //

        $startPos += strlen($photoStartKeyWord);
        $endPos = mb_strpos($content,$photoEndKeyWord) - $startPos ;
        $photoId = mb_substr($content,$startPos,$endPos);

        if ($photoId>0){



            $resultHtml = "";
            $arSelect = Array("ID", "NAME", "PREVIEW_PICTURE","DETAIL_PICTURE");
            $arFilter = Array("IBLOCK_ID"=>$photoIblockId, "ACTIVE"=>"Y","ID"=>$photoId);

            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);

            if($ob = $res->GetNextElement()){
                $arFields = $ob->GetFields();
                $resultHtml.="<div onclick=\"return {'disappearSlider': {}}\" class=\"disappear-slider js-widget\">";
                //$arFields["DETAIL_PICTURE"]  = CFile::GetFileArray($arFields["DETAIL_PICTURE"]);
                $file = CFile::ResizeImageGet($arFields["DETAIL_PICTURE"], array('width'=>$width, 'height'=>$height), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                $resultHtml.='<img src="'.$file["src"].'">';
                $resultHtml.='</div>';
            }else {
                $resultHtml = '';
            }


            //вставляем полученную фигную в детейл текст
            $returnText = mb_substr($content,0,$startPos-strlen($photoStartKeyWord));
            $returnText .= $resultHtml;
            $returnText .= mb_substr($content,$startPos+$endPos+strlen($photoStartKeyWord)+1);
            $content = $returnText;

        }
        $from = $startPos;
    }
}

//функция добавляет новые значения в свойства типа список
function addPropertyListItem($propCode, $value,$iblock)
{

    if (is_array($value)){
        if (!$value['VALUE']) return false;
    }elseif (!$value) {
        return false;
    }

    $properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("CODE"=>$propCode, "IBLOCK_ID"=>$iblock));
    if ($prop_fields = $properties->GetNext()){
        $propId = $prop_fields['ID'];
    }else {
        return false;
    }

    $propFilter = array("IBLOCK_ID" => $iblock, "PROPERTY_ID" => $propId, "VALUE" => $value);
    $arPropFields = array("PROPERTY_ID" => $propId, 'VALUE'=>$value);


    if (is_array($value) && strlen($value['EXTERNAL_ID'])>0){
        $propFilter = array("IBLOCK_ID" => $iblock, "PROPERTY_ID" => $propId, 'EXTERNAL_ID'=>$value['EXTERNAL_ID']);
        $arPropFields = array_merge(  array("PROPERTY_ID" => $propId), $value );
    }elseif (is_array($value)){
        $propFilter = array_merge (array("IBLOCK_ID" => $iblock, "PROPERTY_ID" => $propId), $value);
        $arPropFields = array_merge(  array("PROPERTY_ID" => $propId), $value );
    }

    $iPropIdStatus = 0;
    $obPropertyEnum = new CIBlockPropertyEnum;
    $rsPropertyEnums = CIBlockPropertyEnum::GetList(false, $propFilter);
    if($arPropertyEnums = $rsPropertyEnums->GetNext()){
        $obPropertyEnum->Update($arPropertyEnums['ID'], $arPropFields);
        $iPropIdStatus = $arPropertyEnums['ID'];
    }else{
        $iPropIdStatus = $obPropertyEnum->Add($arPropFields);
    }
    return $iPropIdStatus;
}

/**
 * Получаем массив соответствий код св-ва -> ид св-ва для инфоблока
 * @param $iIblockId
 * @return array
 */
function getIblockProperties( $iIblockId )
{
    $arResult = array();
    $properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$iIblockId));
    while ($prop_fields = $properties->GetNext()){
        $arResult[ $prop_fields["CODE"] ] = $prop_fields["ID"];
    }
    return $arResult;
}

function AddOrderProperty($code, $value, $order) {
    if (!strlen($code)) {
        return false;
    }
    if (CModule::IncludeModule('sale')) {
        if ($arProp = CSaleOrderProps::GetList(array(), array('CODE' => $code))->Fetch()) {
            return CSaleOrderPropsValue::Add(array(
                'NAME' => $arProp['NAME'],
                'CODE' => $arProp['CODE'],
                'ORDER_PROPS_ID' => $arProp['ID'],
                'ORDER_ID' => $order,
                'VALUE' => $value,
            ));
        }
    }
}



function getCityIdByCode($code){

    CModule::IncludeModule("iblock");
    $arSelect = Array("ID");
    $arFilter = Array("IBLOCK_ID"=>getIBlockIdByCode("cities"), "CODE"=>$code, "ACTIVE"=>"Y");
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
    if($ob = $res->GetNextElement())
    {
        $arFields = $ob->GetFields();
        return $arFields['ID'];
    }
}