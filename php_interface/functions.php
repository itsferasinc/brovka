<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/forum/classes/general/topic.php");
CModule::IncludeModule("forum");

class ITSForumTopic extends CForumTopic
{
    public static function GetList($arOrder = Array("SORT" => "ASC"), $arFilter = Array(), $bCount = false, $iNum = 0, $arAddParams = array())
    {
        global $DB, $USER_FIELD_MANAGER, $USER, $APPLICATION;
        $arOrder = (is_array($arOrder) ? $arOrder : array());
        $arFilter = (is_array($arFilter) ? $arFilter : array());
        $arAddParams = (is_array($arAddParams) ? $arAddParams : array($arAddParams));
        $arSqlSearch = array();
        $arSqlSelect = array();
        $arSqlGroup = array();
        $arSqlOrder = array();
        $strSqlSearch = "";
        $strSqlSelect = "";
        $strSqlGroup = "";
        $strSqlOrder = "";
        $UseGroup = false;

        foreach ($arFilter as $key => $val) {
            $key_res = CForumNew::GetFilterOperation($key);
            $key = strtoupper($key_res["FIELD"]);
            $strNegative = $key_res["NEGATIVE"];
            $strOperation = $key_res["OPERATION"];
            switch ($key) {
                case "UF_HIDEONINDEX":
                    $arSqlSearch[] = " UFT.".$key." IS NULL OR NOT (UFT.".$key." ".$strOperation." '".$val."' )";
                    break;
                case "STATE":
                case "APPROVED":
                case "XML_ID":
                    $val = CForumNew::prepareField($strOperation, "string", $val);
                    if ($val == '')
                        $arSqlSearch[] = ($strNegative == "Y" ? "NOT" : "") . "(FT." . $key . " IS NULL OR " . ($DB->type == "MSSQL" ? "LEN" : "LENGTH") . "(FT." . $key . ")<=0)";
                    else if ($strOperation == "IN")
                        $arSqlSearch[] = ($strNegative == "Y" ? " NOT " : "") . "(FT." . $key . " IN (" . $val . ") )";
                    else
                        $arSqlSearch[] = ($strNegative == "Y" ? " FT." . $key . " IS NULL OR NOT " : "") . "(FT." . $key . " " . $strOperation . " '" . $val . "' )";
                    break;
                case "ID":
                case "USER_START_ID":
                case "SOCNET_GROUP_ID":
                case "OWNER_ID":
                case "FORUM_ID":
                case "SORT":
                case "POSTS":
                case "TOPICS":
                    if(is_array($val)){
                        foreach ($val as $v)
                            $val_int[] = intVal($v);
                        $val = implode(", ", $val_int);
                        $arSqlSearch[] = ($strNegative == "Y" ? " NOT " : "") . "(FT." . $key . " IN (" . $DB->ForSql($val) . ") )";
                    }
                    elseif (($strOperation != "IN") && (intVal($val) > 0)) {
                        $arSqlSearch[] = ($strNegative == "Y" ? " FT." . $key . " IS NULL OR NOT " : "") . "(FT." . $key . " " . $strOperation . " " . intVal($val) . " )";
                    }
                    elseif (($strOperation == "IN") && ((is_array($val) && (array_sum($val) > 0)) || (strlen($val) > 0))) {
                        if (!is_array($val))
                            $val = explode(',', $val);
                        $val_int = array();
                        foreach ($val as $v)
                            $val_int[] = intVal($v);
                        $val = implode(", ", $val_int);
                        $arSqlSearch[] = ($strNegative == "Y" ? " NOT " : "") . "(FT." . $key . " IN (" . $DB->ForSql($val) . ") )";
                    } else
                        $arSqlSearch[] = ($strNegative == "Y" ? "NOT" : "") . "(FT." . $key . " IS NULL OR FT." . $key . "<=0)";
                    break;
                case "RENEW_TOPIC":
//					vhodnye parametry tipa array("TID"=>time);
//					pri TID = 0 peredaetsya FORUM_LAST_VISIT
                    $arSqlTemp = array();
                    $strSqlTemp = $val[0];
                    unset($val[0]);
                    if (is_array($val) && !empty($val)) {
                        foreach ($val as $k => $v)
                            $arSqlTemp[] = "(FT.ID=" . intVal($k) . ") AND (FT.LAST_POST_DATE > " . $DB->CharToDateFunction($DB->ForSql($v), "FULL") . ")";

                        $val_int = array();
                        foreach (array_keys($val) as $k)
                            $val_int[] = intVal($k);
                        $keys = implode(", ", $val_int);

                        $arSqlSearch[] =
                            "(FT.ID IN (" . $DB->ForSql($keys) . ") AND ((" . implode(") OR (", $arSqlTemp) . ")))
							OR
							(FT.ID NOT IN (" . $DB->ForSql($keys) . ") AND (FT.LAST_POST_DATE > " . $DB->CharToDateFunction($DB->ForSql($strSqlTemp), "FULL") . "))";
                    }
                    break;
                case "START_DATE":
                case "LAST_POST_DATE":
                    if (strlen($val) <= 0)
                        $arSqlSearch[] = ($strNegative == "Y" ? "NOT" : "") . "(FT." . $key . " IS NULL)";
                    else
                        $arSqlSearch[] = ($strNegative == "Y" ? " FT." . $key . " IS NULL OR NOT " : "") . "(FT." . $key . " " . $strOperation . " " . $DB->CharToDateFunction($DB->ForSql($val), "FULL") . ")";
                    break;
            }
        }

        if (count($arSqlSearch) > 0)
            $strSqlSearch = " AND (" . implode(") AND (", $arSqlSearch) . ")";
        if (count($arSqlSelect) > 0)
            $strSqlSelect = ", " . implode(", ", $arSqlSelect);
        if ($UseGroup) {
            foreach ($arSqlSelect as $key => $val) {
                if (substr($key, 0, 1) != "!")
                    $arSqlGroup[$key] = $val;
            }
            if (!empty($arSqlGroup)):
                $strSqlGroup = ", " . implode(", ", $arSqlGroup);
            endif;
        }
        foreach ($arOrder as $by => $order) {
            $by = strtoupper($by);
            $order = strtoupper($order);
            if ($order != "ASC") $order = "DESC";
            if (in_array($by, array("TOTAL_VALUE"))) {
                $arSqlOrder[] = "RV." . $by . " " . $order;
            }
            if (in_array($by, array("UF_DATE_CREATE"))) {
                $arSqlOrder[] = "UFT." . $by . " " . $order;
            }
            if (in_array($by, array("ID", "FORUM_ID", "TOPIC_ID", "TITLE", "TAGS", "DESCRIPTION", "ICON",
                "STATE", "APPROVED", "SORT", "VIEWS", "USER_START_ID", "USER_START_NAME", "START_DATE",
                "POSTS", "LAST_POSTER_ID", "LAST_POSTER_NAME", "LAST_POST_DATE", "LAST_MESSAGE_ID",
                "POSTS_UNAPPROVED", "ABS_LAST_POSTER_ID", "ABS_LAST_POSTER_NAME", "ABS_LAST_POST_DATE", "ABS_LAST_MESSAGE_ID",
                "SOCNET_GROUP_ID", "OWNER_ID", "HTML"))):
                $arSqlOrder[] = "FT." . $by . " " . $order;
            else:
                $arSqlOrder[] = "FT.SORT " . $order;
                $by = "SORT";
            endif;
        }
        $arSqlOrder = array_unique($arSqlOrder);
        DelDuplicateSort($arSqlOrder);
        if (count($arSqlOrder) > 0)
            $strSqlOrder = " ORDER BY " . implode(", ", $arSqlOrder);

        if ($bCount || (is_set($arAddParams, "bDescPageNumbering") && intVal($arAddParams["nTopCount"]) <= 0)) {
            $strSql =
                "SELECT COUNT(FT.ID) as CNT 
				FROM b_forum_topic FT
				WHERE 1 = 1 
				" . $strSqlSearch;
            $db_res = $DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);
            $iCnt = 0;
            if ($ar_res = $db_res->Fetch()):
                $iCnt = intVal($ar_res["CNT"]);
            endif;
            if ($bCount):
                return $iCnt;
            endif;
        }

        $arSQL = array("select" => "", "join" => "");
        if (!empty($arAddParams["sNameTemplate"])) {
            $arSQL = array_merge_recursive(
                CForumUser::GetFormattedNameFieldsForSelect(array_merge(
                    $arAddParams, array(
                    "sUserTablePrefix" => "U_START.",
                    "sForumUserTablePrefix" => "FU_START.",
                    "sFieldName" => "USER_START_NAME_FRMT",
                    "sUserIDFieldName" => "FT.USER_START_ID"))),
                CForumUser::GetFormattedNameFieldsForSelect(array_merge(
                    $arAddParams, array(
                    "sUserTablePrefix" => "U_LAST.",
                    "sForumUserTablePrefix" => "FU_LAST.",
                    "sFieldName" => "LAST_POSTER_NAME_FRMT",
                    "sUserIDFieldName" => "FT.LAST_POSTER_ID"))),
                CForumUser::GetFormattedNameFieldsForSelect(array_merge(
                    $arAddParams, array(
                    "sUserTablePrefix" => "U_ABS_LAST.",
                    "sForumUserTablePrefix" => "FU_ABS_LAST.",
                    "sFieldName" => "ABS_LAST_POSTER_NAME_FRMT",
                    "sUserIDFieldName" => "FT.ABS_LAST_POSTER_ID"))));
            $arSQL["select"] = ",\n\t" . implode(",\n\t", $arSQL["select"]);
            $arSQL["join"] = "\n" . implode("\n", $arSQL["join"]);
        }

        if ($UseGroup) {
            $strSql =
                " SELECT F_T.*, FT.FORUM_ID, FT.TOPIC_ID, FT.TITLE, FT.TAGS, FT.DESCRIPTION, FT.ICON, \n" .
                "	FT.STATE, FT.APPROVED, FT.SORT, FT.VIEWS, FT.USER_START_ID, FT.USER_START_NAME, \n" .
                "	" . CForumNew::Concat("-", array("FT.ID", "FT.TITLE_SEO")) . " as TITLE_SEO, \n" .
                "	" . $DB->DateToCharFunction("FT.START_DATE", "FULL") . " as START_DATE, \n" .
                "	FT.POSTS, FT.LAST_POSTER_ID, FT.LAST_POSTER_NAME, \n" .
                "	" . $DB->DateToCharFunction("FT.LAST_POST_DATE", "FULL") . " as LAST_POST_DATE, \n" .
                "	FT.LAST_POST_DATE AS LAST_POST_DATE_ORIGINAL, FT.LAST_MESSAGE_ID, \n" .
                "	FT.POSTS_UNAPPROVED, FT.ABS_LAST_POSTER_ID, FT.ABS_LAST_POSTER_NAME, \n" .
                "	" . $DB->DateToCharFunction("FT.ABS_LAST_POST_DATE", "FULL") . " as ABS_LAST_POST_DATE, \n" .
                "	FT.ABS_LAST_POST_DATE AS ABS_LAST_POST_DATE_ORIGINAL, FT.ABS_LAST_MESSAGE_ID, \n" .
                "	FT.SOCNET_GROUP_ID, FT.OWNER_ID, FT.HTML, FT.XML_ID" . $arSQL["select"] . " \n" .
                " FROM ( \n" .
                "		SELECT FT.ID" . $strSqlSelect . " \n" .
                "		FROM b_forum_topic FT \n" .
                "		WHERE 1 = 1 " . $strSqlSearch . " \n" .
                "		GROUP BY FT.ID " . $strSqlGroup . " \n" .
                " ) F_T \n" .
                " INNER JOIN b_forum_topic FT ON (F_T.ID = FT.ID) " . $arSQL["join"] . " \n" .
                " LEFT JOIN b_rating_voting RV ON ( RV.ENTITY_TYPE_ID = 'FORUM_TOPIC' AND RV.ENTITY_ID = FT.TOPIC_ID ) \n" .
                //" LEFT JOIN b_rating_voting RV ON ( RV.ENTITY_TYPE_ID = 'FORUM_TOPIC' AND RV.ENTITY_ID = FT.TOPIC_ID ) \n".
                $strSqlOrder;
        } else {
            $strSql =
                " SELECT FT.ID, RV.TOTAL_VALUE, FT.FORUM_ID, FT.TOPIC_ID, FT.TITLE, FT.TAGS, FT.DESCRIPTION, FT.ICON, \n" .
                "	FT.STATE, FT.APPROVED, FT.SORT, FT.VIEWS, FT.USER_START_ID, FT.USER_START_NAME, \n" .
                "	" . CForumNew::Concat("-", array("FT.ID", "FT.TITLE_SEO")) . " as TITLE_SEO, \n" .
                "	" . $DB->DateToCharFunction("FT.START_DATE", "FULL") . " as START_DATE, \n" .
                "	" . $DB->DateToCharFunction("UFT.UF_DATE_CREATE", "SHORT") . " as UF_DATE_CREATE, \n" .
                "	UFT.UF_THEME as UF_THEME, \n" .
                "	UFT.UF_HIDEONINDEX as UF_HIDEONINDEX, \n" .
                "	FT.POSTS, FT.LAST_POSTER_ID, FT.LAST_POSTER_NAME, \n" .
                "	" . $DB->DateToCharFunction("FT.LAST_POST_DATE", "FULL") . " as LAST_POST_DATE, \n" .
                "	FT.LAST_POST_DATE AS LAST_POST_DATE_ORIGINAL, FT.LAST_MESSAGE_ID, \n" .
                "	FT.POSTS_UNAPPROVED, FT.ABS_LAST_POSTER_ID, FT.ABS_LAST_POSTER_NAME, \n" .
                "	" . $DB->DateToCharFunction("FT.ABS_LAST_POST_DATE", "FULL") . " as ABS_LAST_POST_DATE, \n" .
                "	FT.ABS_LAST_POST_DATE AS ABS_LAST_POST_DATE_ORIGINAL, FT.ABS_LAST_MESSAGE_ID, \n" .
                "	FT.SOCNET_GROUP_ID, FT.OWNER_ID, FT.HTML, FT.XML_ID" . $strSqlSelect . $arSQL["select"] . " \n" .
                " FROM b_forum_topic FT " . $arSQL["join"] . " \n" .
                " LEFT JOIN b_rating_voting RV ON ( RV.ENTITY_TYPE_ID = 'FORUM_TOPIC' AND RV.ENTITY_ID = FT.ID ) \n" .
                " LEFT JOIN b_uts_forum_topic UFT ON ( UFT.VALUE_ID = FT.ID ) \n" .
                " WHERE 1 = 1 " . $strSqlSearch . " \n" .
                $strSqlOrder;
        }

        $iNum = intVal($iNum);
        if ($iNum > 0 || intVal($arAddParams["nTopCount"]) > 0) {
            $iNum = ($iNum > 0) ? $iNum : intVal($arAddParams["nTopCount"]);
            $strSql .= "\nLIMIT 0," . $iNum;
        }
        GLOBAL $USER_FIELD_MANAGER;
        if (!$iNum && is_set($arAddParams, "bDescPageNumbering") && intVal($arAddParams["nTopCount"]) <= 0) {
            $db_res = new CDBResult();
            $db_res->SetUserFields($USER_FIELD_MANAGER->GetUserFields("FORUM_TOPIC"));
            $db_res->NavQuery($strSql, $iCnt, $arAddParams);
        } else {
            $db_res = $DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);
            $db_res->SetUserFields($USER_FIELD_MANAGER->GetUserFields("FORUM_TOPIC"));
        }
        //echo'<pre>db_res:';print_r($db_res->fetch());echo"</pre>";
        return new _CTopicDBResult($db_res, $arAddParams);
    }
}
class ITSGlobal
{
    public static function getUserName($id)
    {
        $rsUser = CUser::GetByID($id);
        $arUser = $rsUser->Fetch();
        if(strlen($arUser['NAME'])>0)
            $nameArr[] = $arUser['NAME'];
        if(strlen($arUser['LAST_NAME'])>0)
            $nameArr[] = $arUser['LAST_NAME'];
        if(is_array($nameArr))
            return implode(" ",$nameArr);
        else
            return $arUser['LOGIN'];
    }
    public static function getLocationByFacet($id)
    {
        GLOBAL $DB;
        $strSql =
            "SELECT *
				FROM b_iblock_6_index_val
				WHERE ID = ".$id."
			";
        $db_res = $DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);
        $iCnt = 0;
        $ar_res = $db_res->Fetch();
        return $ar_res['VALUE'];
    }
}

function getNumEnding($number, $endingArray)
{
    $number = $number % 100;
    if ($number>=11 && $number<=19) {
        $ending=$endingArray[2];
    }
    else {
        $i = $number % 10;
        switch ($i)
        {
            case (1): $ending = $endingArray[0]; break;
            case (2):
            case (3):
            case (4): $ending = $endingArray[1]; break;
            default: $ending=$endingArray[2];
        }
    }
    return $ending;
}
function getTextBetweenTags($string, $tagname) {
    $pattern = "/\[$tagname ?.*\](.*)\[\/$tagname\]/";
    preg_match_all($pattern, $string, $matches);
    return $matches[1];
}
function getTextBetweenNormalTags($string, $tagname) {
    $pattern = "/\[$tagname ID=([0-9]*)\]/";
    preg_match_all($pattern, $string, $matches);
    return $matches[1];
}
