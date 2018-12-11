<?php ob_start(); ?>
<?php require_once('Connections/goldenbirdConn1.php'); 
	require_once("./_imageCompression.php");	//lib_壓縮圖片
?>
<?php
if (!isset($_SESSION)) {
  @session_start();
}


if($_GET['gmId']!=$_SESSION['gmId'] || !isset($_GET['gmId'])){header("Location: admin.php");}

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
	//上傳圖片
	if($_FILES['upfile']['size']>1000000){
		die( '上傳檔案錯誤，可能太大');	
	}
	else if($_FILES['upfile']['size']>0){
		//echo $_FILES['upfile']['type'];		
		
	
	//$upload_dir =  "../uploadFiles/";
     $upload_dir =  "upload/";
     
     $upload_file = $upload_dir . $_GET['gmId']."_". $to = iconv("UTF-8", "big5", $_FILES["upfile"]["name"]);     
	 
	  if(!is_dir($upload_dir))mkdir ("upload/",0644); 

	  
	  
	  /****************使用圖片壓縮lib****************/
	  $filename=(_UPLOADPIC($_FILES["upfile"],$maxsize=1000000,$upload_file,$newname='date'));  
     $show_pic_scal=show_pic_scal(230, 230, $filename);  
     resize($filename,$show_pic_scal[0],$show_pic_scal[1]); 
	  
     //������������������������������������������������������
     //if (!move_uploaded_file($_FILES["upfile"]["tmp_name"], $upload_file))echo 'upload errer';
	

	}
	
	//圖片名稱寫入資料庫
	if(!isset($_FILES['upfile']['name']) || $_FILES['upfile']['name']==NULL)
	{$f="無圖片";}else{$f=$_FILES['upfile']['name'];}
	
	
	
	
  $insertSQL = sprintf("INSERT INTO `group` (gName, gIntroduction, gImages, cId, gmId) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['gName'], "text"),
                       GetSQLValueString($_POST['gIntro'], "text"),
					   GetSQLValueString($f, "text"),
                       GetSQLValueString($_POST['type'], "int"),					   
                       GetSQLValueString($_POST['gmId'], "int"));

  mysql_select_db($database_goldenbirdConn1, $goldenbirdConn1);
  $Result1 = mysql_query($insertSQL, $goldenbirdConn1) or die(mysql_error());

  $insertGoTo = "adminindex.php?gmId=" . $_SESSION['gmId'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_goldenbirdConn1, $goldenbirdConn1);
$query_class = "SELECT * FROM `class`";
$class = mysql_query($query_class, $goldenbirdConn1) or die(mysql_error());
$row_class = mysql_fetch_assoc($class);
$totalRows_class = mysql_num_rows($class);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新增主題</title>
<style type="text/css">
<!--
body,td,th {
	font-size: 10pt;
}
a {
	font-size: 10pt;
	color: #A4BAE0;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #6666CC;
}
a:hover {
	text-decoration: underline;
	color: #CC3300;
}
a:active {
	text-decoration: none;
	color: #2C4886;
}
.style2 {color: #FFFFFF}
.style3 {color: #666666}
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
    <td><table width="100%" border="0">
        <tr>
          <td>&nbsp;</td>
          <td align="right" valign="bottom"></td>
        </tr>
      </table></td>
    <td background="img/he3_5.gif">&nbsp;</td>
  </tr>
  <tr>
    <td background="img/he3_6.gif">&nbsp;</td>
    <td align="center" background="img/he3_7.gif"><hr />      <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">上一頁<br />
      <br />
    </a></td>
    <td background="img/he3_8.gif">&nbsp;</td>
  </tr>
  <tr>
    <td background="img/he3_4.gif">&nbsp;</td>
    <td align="center" bgcolor="#A4BAE0"><span class="style2">新增主題</span></td>
    <td background="img/he3_5.gif">&nbsp;</td>
  </tr>
  <tr>
    <td background="img/he3_4.gif">&nbsp;</td>
    <td align="center">
      <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1" id="form1">
     
         <table border="0">
           <tr>
             <td align="right" bgcolor="#19245F"><span class="style2">主題名稱</span></td>
             <td align="left" bgcolor="#E1E9F4"><input type="text" name="gName" id="gName" /></td>
           </tr>
  <tr>
    <td align="right" bgcolor="#19245F"><span class="style2">主題類別</span></td>
    <td align="left" bgcolor="#E1E9F4"><label>
      
      
      <select name="type" id="type">
        <option value="" <?php if (!(strcmp("", 0))) {echo "selected=\"selected\"";} ?>></option>
        <?php
do {  
?>
        <option value="<?php echo $row_class['cId']?>"<?php if (!(strcmp($row_class['cId'], 0))) {echo "selected=\"selected\"";} ?>><?php echo $row_class['cName']?></option>
        <?php
} while ($row_class = mysql_fetch_assoc($class));
  $rows = mysql_num_rows($class);
  if($rows > 0) {
      mysql_data_seek($class, 0);
	  $row_class = mysql_fetch_assoc($class);
  }
?>
      </select>
      
      </label></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#19245F"><span class="style2">主題圖片</span></td>
    <td align="left" bgcolor="#E1E9F4"><input type="file" name="upfile" id="upfile"/></td></tr>
    <tr>
      <td align="right" bgcolor="#19245F"><span class="style2">主題簡介</span></td>
    <td align="left" bgcolor="#E1E9F4"><label>
      <textarea name="gIntro" id="gIntro" cols="45" rows="5"></textarea>
    </label></td></tr>
</table>

          

        
        <input type="submit" name="submit" id="submit" value="加入主題" />
        <input type="hidden" name="MM_insert" value="form1" />
        <input name="gmId" type="hidden" id="gmId" value="<?php echo $_SESSION['gmId']; ?>" />
      </form><table border="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>      </td>
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
mysql_free_result($class);

?>
<?php ob_end_flush();  ?>