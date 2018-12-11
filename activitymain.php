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

if (!isset($_SESSION)) {
  session_start();
}

if($_GET['gmId']!=$_SESSION['gmId'] || !isset($_GET['gmId'])){header("Location: admin.php");}


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



if ((isset($_GET['aId'])) && ($_GET['aId'] != "") && (isset($_POST['aId']))) {
  $deleteSQL = sprintf("DELETE FROM message WHERE aId=%s",
                       GetSQLValueString($_GET['aId'], "int"));

  mysql_select_db($database_goldenbirdConn1, $goldenbirdConn1);
  $Result1 = mysql_query($deleteSQL, $goldenbirdConn1) or die(mysql_error());

  $deleteGoTo = "activityindex.php?gId=" . $row_acitvityMain['gId'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

if ((isset($_POST['mId'])) && ($_POST['mId'] != "")) {
  $deleteSQL = sprintf("DELETE FROM message WHERE mId=%s",
                       GetSQLValueString($_POST['mId'], "int"));

  mysql_select_db($database_goldenbirdConn1, $goldenbirdConn1);
  $Result1 = mysql_query($deleteSQL, $goldenbirdConn1) or die(mysql_error());
  
  header('refresh: 0');
}

$colname2_Group = "-1";
if (isset($_GET['gId'])) {
  $colname2_Group = $_GET['gId'];
}
mysql_select_db($database_goldenbirdConn1, $goldenbirdConn1);
$query_Group = sprintf("SELECT gName, gImages, gIntroduction, class.cName FROM `group` JOIN `class` ON group.cId=class.cId WHERE gId = %s", GetSQLValueString($colname2_Group, "int"));
$Group = mysql_query($query_Group, $goldenbirdConn1) or die(mysql_error());
$row_Group = mysql_fetch_assoc($Group);
$totalRows_Group = mysql_num_rows($Group);


$colname_acitvityMain = "-1";
if (isset($_GET['aId'])) {
  $colname_acitvityMain = $_GET['aId'];
}
mysql_select_db($database_goldenbirdConn1, $goldenbirdConn1);
$query_acitvityMain = sprintf("SELECT * FROM `activity` WHERE aId = %s", GetSQLValueString($colname_acitvityMain, "int"));
$acitvityMain = mysql_query($query_acitvityMain, $goldenbirdConn1) or die(mysql_error());
$row_acitvityMain = mysql_fetch_assoc($acitvityMain);
$totalRows_acitvityMain = mysql_num_rows($acitvityMain);

$colname_mess = "-1";
if (isset($_GET['aId'])) {
  $colname_mess = $_GET['aId'];
}
mysql_select_db($database_goldenbirdConn1, $goldenbirdConn1);
$query_mess = sprintf("SELECT * FROM message WHERE aId = %s", GetSQLValueString($colname_mess, "int"));
$mess = mysql_query($query_mess, $goldenbirdConn1) or die(mysql_error());
$row_mess = mysql_fetch_assoc($mess);
$totalRows_mess = mysql_num_rows($mess);



if ((isset($_GET['aId'])) && ($_GET['aId'] != "") && (isset($_POST['aId']))) {
  $deleteSQL = sprintf("DELETE FROM activity WHERE aId=%s",
                       GetSQLValueString($_GET['aId'], "int"));

  mysql_select_db($database_goldenbirdConn1, $goldenbirdConn1);
  $Result1 = mysql_query($deleteSQL, $goldenbirdConn1) or die(mysql_error());
  
  
  //刪除圖檔  
	if( file_exists( $_POST['aImage'] ) )unlink( $_POST['aImage'] );


  $deleteGoTo = "activityindex.php?gId=" . $row_acitvityMain['gId'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>活動修改</title>

<link href="css/header2.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
<!--
.style1 {
	color: #FFFFFF
}
.style2 {
	font-size: 12pt;
	color: #CCCCCC;
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
#apDiv1 {
	position:absolute;
	width:106px;
	height:60px;
	z-index:1;
	left: 556px;
	top: 291px;
}
-->
</style>
<script type="text/javascript">
function MM_popupMsg(msg) { //v1.0
  alert(msg);
}
</script>
</head>

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
    <td background="img/he3_7.gif"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#CC6600" id='menu1' >
      <tr>
        <td width="12%" height="20" align="center" valign="middle"><a href="index.php">首頁</a></td>
        <td width="21%" height="20" align="center" valign="middle"><a href="adminindex.php">團體內主題總覽</a><a href="adminindex.php"></a></td>
        <td width="24%" align="center" valign="middle"><a href="activityindex.php?gmId=<?php echo $_SESSION['gmId']; ?>&amp;gId=<?php echo $row_acitvityMain['gId']; ?>"><?php echo $row_Group['gName']; ?>的活動</a></td>
        <td width="19%" height="20" align="center" valign="middle">修改專欄</td>
        <td width="3%" height="20" align="center" valign="middle"><span class="style1"></td>
        <td width="21%" height="20" align="center" valign="middle"><a href="<?php echo $logoutAction ?>">登出系統</a></td>
      </tr>
    </table></td>
    <td background="img/he3_8.gif">&nbsp;</td>
  </tr>
  <tr>
    <td background="img/he3_4.gif">&nbsp;</td>
    <td align="center"><?php if ($totalRows_acitvityMain == 0) { // Show if recordset empty ?>
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
          <td width="21%" height="20" align="left" valign="middle"><?php echo $row_Group['gImages']; ?></td>
          <td width="34%" height="20" align="center" valign="middle"><?php echo $row_Group['gName']; ?></td>
          <td width="5%" height="20" align="center" valign="middle">&nbsp;</td>
          <td width="7%" height="20" align="center" valign="middle"><span class="style1"></td>
          <td width="28%" height="20" align="center" valign="middle"><?php echo $row_Group['cName']; ?></td>
        </tr>
        <tr>
          <td height="20" align="left" colspan="5"="middle"><?php echo nl2br($row_Group['gIntroduction']); ?></td>
        </tr>
      </table>
      <br />
      <p align="right">發佈時間：<?php echo $row_acitvityMain['aPoTime']; ?></p>
      <table border="0">
      <tr>
        <td></td><td align="right">
        <form id="form1" name="form1" method="post" action="">
             <input name="aImage" type="hidden" id="aImage" value="<?php echo "upload/{$row_acitvityMain['gId']}/{$row_acitvityMain['aImage']}"; ?>" />
          <input name="aId" type="hidden" id="aId" value="<?php echo $row_acitvityMain['aId']; ?>" />
        
            <input name="submit" type="submit" id="submit" onclick="return(confirm('確定刪除本活動?'))" value="Ｘ" />
            </form></td></tr>
  <tr>
    <td width="88" align="right" bgcolor="#660000" class="style1">活動標題</td>
    <td width="416" align="left" bgcolor="#E1E9F4" class="style3"><?php echo $row_acitvityMain['aTitle']; ?></td>
  </tr>
  <tr>
  <td align="right" bgcolor="#660000" class="style1">活動時間</td>
  <td align="left" bgcolor="#E1E9F4" class="style3">
    <label><?php echo $row_acitvityMain['aTime']; ?></label></td></tr>
    <tr>
    <td align="right" bgcolor="#660000" class="style1">活動地點</td>
    <td align="left" bgcolor="#E1E9F4" class="style3">
      <label></label>
      <?php echo $row_acitvityMain['aLocation']." （".$row_acitvityMain['aAddress']."）"; ?></td></tr>
    <tr>
      <td align="right" bgcolor="#660000" class="style1">活動圖片</td>
      <td align="left" bgcolor="#E1E9F4" class="style3"><label></label>
        <?php 
			if($row_acitvityMain['aImage']=="無圖片"){echo "<img src='img/goldenbird.png' width='100'></img>";}
			else{
echo "<img src='upload/{$row_acitvityMain['gId']}/{$row_acitvityMain['aImage']}' width='100'></img>";
			}
			 ?>
    </tr>
 <tr>
    <td align="right" bgcolor="#660000" class="style1">活動內容</td>
    <td align="left" bgcolor="#E1E9F4" class="style3">
      <label><?php echo nl2br(str_replace(" ","&nbsp;",$row_acitvityMain['aContent'])); ?></label></td></tr>
</table>
      <p align="right">點閱率：<?php echo $row_acitvityMain['aClick']; ?><br />
      </p>
      <br/><div align="left">此活動的留言:</div>
      <table width="650" border="0" cellpadding="1" cellspacing="5" bordercolor="#A4BAE0">
        <?php do { ?>
            <?php if ($totalRows_mess > 0) { // Show if recordset not empty ?>
  <tr>
    <td width="141" align="left" valign="middle">
	<?php if($row_mess['uPhone']!="Guest")echo substr($row_mess['uPhone'],strlen($row_mess['uPhone'])-9); 
			else echo "Guest"; ?>
    </td>
    <td width="319" align="left" valign="top"><span class="style1"></a></span>
      <?php echo nl2br( str_replace(" ","&nbsp;",$row_mess['mContent'])); ?></td>
    <td><?php echo $row_mess['mTime']; ?></td>
    <td>
      <form id="form2" name="form2" method="post" action="">
        <input name="mId" type="hidden" id="mId" value="<?php echo $row_mess['mId']; ?>" />
        
        <input name="del_mess" type="submit" id="del_mess" onclick="return(confirm('確定刪除本留言?'))" value="Ｘ" />
      </form>
    </td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <?php } // Show if recordset not empty ?>
<?php } while ($row_mess = mysql_fetch_assoc($mess)); ?>
<tr>
          <td colspan="4"><hr/></td>
        </tr>
      </table>
      <p align="right">&nbsp; </p>
      <table width="650" border="0">
        <tr>
          <td width="80" align="left">&nbsp;</td>
          <td width="560" align="right">&nbsp;</td>
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
mysql_free_result($Group);

mysql_free_result($acitvityMain);

mysql_free_result($mess);
?>
