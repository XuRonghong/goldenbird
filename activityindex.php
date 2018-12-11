<?php require_once('Connections/goldenbirdConn1.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "admin.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

if($_GET['gmId']!=$_SESSION['gmId']){header("Location: admin.php");}

$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "admin.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
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
}

$colname_groupActivity = "-1";
if (isset($_GET['gId'])) {
  $colname_groupActivity = $_GET['gId'];
}
mysql_select_db($database_goldenbirdConn1, $goldenbirdConn1);
$query_groupActivity = sprintf("SELECT * FROM activity WHERE gId = %s ORDER BY activity.aId DESC", GetSQLValueString($colname_groupActivity, "int"));
$groupActivity = mysql_query($query_groupActivity, $goldenbirdConn1) or die(mysql_error());
$row_groupActivity = mysql_fetch_assoc($groupActivity);
$totalRows_groupActivity = mysql_num_rows($groupActivity);

$colname_group = "-1";
if (isset($_GET['gId'])) {
  $colname_group = $_GET['gId'];
}
mysql_select_db($database_goldenbirdConn1, $goldenbirdConn1);
$query_group = sprintf("SELECT gId, gName, gImages, gIntroduction, class.cName FROM `group` JOIN `class` ON group.cId=class.cId WHERE gId = %s", GetSQLValueString($colname_group, "int"));
$group = mysql_query($query_group, $goldenbirdConn1) or die(mysql_error());
$row_group = mysql_fetch_assoc($group);
$totalRows_group = mysql_num_rows($group);
 
if (!isset($_SESSION)) {
  session_start();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>主題活動</title>

<link href="css/header2.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
<!--
.style1 {color: #666666}
.style2 {
	font-size: 12pt;
	color: #990000;
	font-weight: bold;
}
body p {
}
table tr td {
	padding: 2px;
}
#Group tr td {
	color: #6CF;
	background-color: #09C;
	font-weight: bold;
	margin: 0px;
	padding: 7px;
}
-->
</style></head>

<body>
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="18" height="30">&nbsp;</td>
    <td background="img/he3_2.gif">&nbsp;</td>
    <td width="18" height="30">&nbsp;</td>
  </tr>
  <tr>
    <td background="img/he3_4.gif">&nbsp;</td>
    <td>&nbsp;</td>
    <td background="img/he3_5.gif">&nbsp;</td>
  </tr>
  <tr>
    <td background="img/he3_6.gif">&nbsp;</td>
    <td background="img/he3_7.gif"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#CC6600" id='menu1'>
      <tr>
        <td width="16%" height="20" align="center" valign="middle"><a href="index.php">首頁</a></td>
        <td width="20%" height="20" align="center" valign="middle"><a href="adminindex.php">團體內主題總覽</a><a href="adminindex.php"></a></td>
        <td width="23%" align="center" valign="middle"><?php echo $row_group['gName']; ?></td>
        <td width="6%" height="20" align="center" valign="middle">&nbsp;</td>
        <td width="11%" height="20" align="center" valign="middle"><span class="style1"></td>
        <td width="24%" height="20" align="center" valign="middle"><a href="<?php echo $logoutAction ?>">登出系統</a></td>
      </tr>
    </table></td>
    <td background="img/he3_8.gif">&nbsp;</td>
  </tr>
  <tr>
    <td background="img/he3_4.gif">&nbsp;</td>
    <td><?php if ($totalRows_groupActivity == 0) { // Show if recordset empty ?>
  <table width="100%" border="0">
    <tr>
      <td align="center"><span class="style2">目前無任何消息發佈</span></td>
      </tr>
  </table>
  <?php } // Show if recordset empty ?>
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="2">
        <tr>
          <td align="left">&nbsp;</td>
          <td align="right">&nbsp;</td>
        </tr>
      </table>
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#668BB2" id="Group">
        <tr>
          <td width="21%" align="left" valign="middle"><?php 
			if($row_group['gImages']=="無圖片"){echo "<img src='img/goldenbird.png' width='80'></img>";}
			else{
echo "<img src='upload/{$_GET['gmId']}_{$row_group['gImages']}' width='80'></img>";
			}
			 ?></td>
          <td width="34%" height="20" align="center" valign="middle"><?php echo $row_group['gName']; ?></td>
          <td width="5%" height="20" align="center" valign="middle">&nbsp;</td>
          <td width="7%" height="20" align="center" valign="middle"></td>
          <td width="28%" height="20" align="center" valign="middle"><?php echo $row_group['cName']; ?></td>
        </tr>
        <tr>
          <td height="20" align="left" colspan="5"="middle"><?php echo nl2br($row_group['gIntroduction']); ?></td>
        </tr>
      </table>
      <table width="650" border="0" cellpadding="1" cellspacing="5" bordercolor="#A4BAE0">
        <?php if ($totalRows_groupActivity > 0) { // Show if recordset not empty ?>
  <?php do { ?>
    <tr>
      <td align="left" valign="top" colspan="2"><a href="activitymain.php?gmId=<?php echo $_SESSION['gmId']; ?>&amp;gId=<?php echo $row_groupActivity['gId']; ?>&amp;aId=<?php echo $row_groupActivity['aId']; ?>"><?php echo $row_groupActivity['aTitle']; ?></a></td>
      
      <td width="141" align="center" valign="middle" rowspan="2"><?php 
			if($row_groupActivity['aImage']=="無圖片"){echo "<img src='img/goldenbird.png' width='100'></img>";}
			else{
echo "<img src='upload/{$row_groupActivity['gId']}/{$row_groupActivity['aImage']}' width='100'></img>";
			}
			 ?></td>
    </tr>
    <tr>
      <td width="164" align="left"><span class="style1"><?php echo $row_groupActivity['aTime']; ?></span></td>
      <td width="319" align="left"><span class="style1"><?php echo $row_groupActivity['aLocation']." （".$row_groupActivity['aAddress']."）"; ?></a></span></td>
      
    </tr>
    <tr>
      <td align="left" colspan="3"><span class="style1">
	  <?php echo nl2br(str_replace(" ","&nbsp;",$row_groupActivity['aContent'])); ?></span><hr /></td>
      
      
    </tr>
    <?php } while ($row_groupActivity = mysql_fetch_assoc($groupActivity)); ?>
          <?php } // Show if recordset not empty ?>
      </table>
<table width="650" border="0">
        <tr>
          <td width="216" align="left">共有<?php echo $totalRows_groupActivity ?>活動</td>
          <td width="424" align="right"><a href="addActivity.php?gmId=<?php echo $_SESSION['gmId']; ?>&amp;gId=<?php echo $row_group['gId']; ?>">＋</a></td>
        </tr>
    </table></td>
    <td background="img/he3_5.gif">&nbsp;</td>
  </tr>
  <tr>
    <td background="img/he3_6.gif">&nbsp;</td>
    <td background="img/he3_7.gif">&nbsp;</td>
    <td background="img/he3_8.gif">&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($groupActivity);

mysql_free_result($group);
?>
