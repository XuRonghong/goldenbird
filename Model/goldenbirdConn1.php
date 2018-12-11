<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
//$hostname_goldenbirdConn1 = "localhost";
//$database_goldenbirdConn1 = "goldenbird";
//$username_goldenbirdConn1 = "root";
//$password_goldenbirdConn1 = "123456";
//$goldenbirdConn1 = mysql_pconnect($hostname_goldenbirdConn1, $username_goldenbirdConn1, $password_goldenbirdConn1) or trigger_error(mysql_error(),E_USER_ERROR);
//
//mysql_query("set names utf8");


//------------------------新版寫法------------------------------
$DB = new WADB();
class WADB
{
    /* Database Host */
    private $sDbDetail;         // Database Details
    private $iNoOfRecords;      // Total No of Records
    private $oQueryResult;      // Results of sql query
    private $aSelectRecords;    // Array
    private $aArrRec;           // Array
    private $bInsertRecords;    // Boolean
    private $iInsertRecId;      // Integer - the primary key for inserted record
    private $bUpdateRecords;    // Boolean
    private $oDbLink;           // DB Link

    /* Constructor */
    function __construct()
    {
        $this->oDbLink = mysqli_connect(SYSTEM_DBHOST, SYSTEM_DBUSER, SYSTEM_DBPWD, SYSTEM_DBNAME) or die ("MySQL DB could not be connected");
        mysqli_query($this->oDbLink, "set names 'utf8'");
    }

    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    {
        if (PHP_VERSION < 6) {
            $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
        }

//        $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
        $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($this->oDbLink, $theValue) : mysqli_escape_string($this->oDbLink, $theValue);

        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
            case "float":
                $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }
        return $theValue;
    }

    /* Select Records */
    function SelectRecords ($sSqlQuery, $type=MYSQLI_ASSOC)
    {
        unset($this->aSelectRecords);
        $this->oQueryResult = mysqli_query($this->oDbLink, $sSqlQuery) or die(mysqli_error($this->oDbLink));
        $this->iNoOfRecords = mysqli_num_rows($this->oQueryResult);
        if ($this->iNoOfRecords > 0) {
            while ($oRow = mysqli_fetch_array($this->oQueryResult, $type)) {
                $this->aSelectRecords[] = $oRow;
            }
            mysqli_free_result($this->oQueryResult);
        }else{
            $this->aSelectRecords = null;
        }
        $this->aArrRec = $this->aSelectRecords;
        return $this->aArrRec;
    }
    
    /* get mysql_num_rows total */
    function getTotalRows ()
    {
        return $this->iNoOfRecords;
    }

    /* Insert Records */
    function InsertRecords ($sSqlQuery)
    {
        $this->bInsertRecords = mysqli_query($this->oDbLink, $sSqlQuery) or die (mysqli_error($this->oDbLink));
        $this->iInsertRecId = mysqli_insert_id($this->oDbLink);
        return $this->iInsertRecId;
    }

    /* Update Records */
    function UpdateRecords($sSqlQuery)
    {
        return mysqli_query($this->oDbLink, $sSqlQuery) or die(mysqli_error($this->oDbLink));
    }
    function DeleteRecords($sSqlQuery)
    {
        return mysqli_query($this->oDbLink, $sSqlQuery) or die(mysqli_error($this->oDbLink));
    }



    /* from index.php
     * @param int $startRow_activityClick
     * @param int $maxRows_activityClick
     * return mysqli_retch_assoc
     */
    function getActivityClick($startRow_activityClick=0,$maxRows_activityClick=0)
    {
        $sql = "SELECT * 
                FROM `activity` JOIN `group`  
                  ON activity.gId = group.gId 
                ORDER BY activity.aClick DESC ";
        if ($maxRows_activityClick)
            $sql = sprintf(" %s LIMIT %d, %d ", $sql, $startRow_activityClick, $maxRows_activityClick);
        return $this->SelectRecords($sql);
    }

    /* from seenews.php
     * @param int $colname_activityMain
     * @param int
     * return mysqli_retch_assoc
     */
    function getActivityById($colname_activityMain=0)
    {
        $sql = "SELECT * 
                FROM `activity` LEFT JOIN `message`  
                  ON activity.aId = message.aId 
                WHERE activity.aId = %d 
                ORDER BY activity.gId ASC ";
        if ($colname_activityMain)
            $sql = sprintf($sql, $this->GetSQLValueString($colname_activityMain, "int"));
        return $this->SelectRecords($sql);
    }

    /* from seenews.php
     * @param int $colname_activityGid
     * @param int
     * return mysqli_retch_assoc
     */
    function getGroupById($colname_activityGid=0)
    {
        $sql = "SELECT * 
                FROM `group` 
                WHERE gId = %d 
                ORDER BY gId ASC ";
        if ($colname_activityGid)
            $sql = sprintf($sql, $this->GetSQLValueString($colname_activityGid, "int"));
        return $this->SelectRecords($sql);
    }

    /* from admin.php
     * @param int
     * @param int
     * return mysqli_retch_assoc
     */
    function getGroupManage()
    {
        $sql = "SELECT * 
                FROM `group_manage` 
                WHERE 1 
                ORDER BY gmId ASC ";
        return $this->SelectRecords($sql);
    }

    /* from admin.php
     * @param $loginUsername
     * @param $password
     * return mysqli_retch_assoc
     */
    function getGroupManageByUSPW($loginUsername='', $password='')
    {
        $sql = "SELECT gmId, gUs, gPw 
                FROM group_manage 
                WHERE gUs LIKE %s 
                  AND gPw LIKE %s 
                ORDER BY gmId ASC ";
        if ($loginUsername!='')
            $sql = sprintf($sql,
                $this->GetSQLValueString($loginUsername, "text"),
                $this->GetSQLValueString($password, "text")
            );
        return $this->SelectRecords($sql);
    }

    /* from classify.php
     * @param int
     * @param int
     * return mysqli_retch_assoc
     */
    function getClass()
    {
        $sql = "SELECT * 
                FROM `class` 
                WHERE 1 
                ORDER BY cId ASC ";
        return $this->SelectRecords($sql);
    }

    /* from classify.php
     * @param int
     * @param int
     * return mysqli_retch_assoc
     */
    function getGroupByClass($cid=0)
    {
        $sql = "SELECT * 
                FROM `class` JOIN `group` 
                ON class.cId = group.cId 
                WHERE class.cId = %d 
                ORDER BY group.gId DESC ";
        if ($cid)
            $sql = sprintf($sql,
                $this->GetSQLValueString($cid, "int")
            );
        return $this->SelectRecords($sql);
    }

    /* from seenews.php
     * post SQL query
     */
    function setMessageWithId($aId=0,$mContent='',$uPhone='')
    {
        $sql = "INSERT INTO message (mContent, uPhone, aId) 
                VALUES (%s, %s, %s) ";
        $sql = sprintf( $sql,
                    $this->GetSQLValueString($mContent, "text"),
                    $this->GetSQLValueString($uPhone, "text"),
                    $this->GetSQLValueString($aId, "int")
                );
        return $this->InsertRecords($sql);
    }

    /* from seenews.php
     * put SQL query
     */
    function putActivityWithClick($colname_activityMain=0)
    {
        $sql = "UPDATE activity SET aClick= aClick + 1 WHERE aId= %d ";
        $sql = sprintf( $sql, $this->GetSQLValueString($colname_activityMain, "int") );
        return $this->UpdateRecords($sql);
    }
}

?>