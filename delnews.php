<?php include_once('cfg.inc.php'); ?>
<?php include_once('Model/goldenbirdConn1.php'); ?>
<?php
    $vTitle = "主題刪除";

//    if($_GET['gmId']!=$_SESSION['gmId'] || !isset($_GET['gmId'])){header("Location: admin.php");}

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

    if ((isset($_GET['gId'])) && ($_GET['gId'] != "") && (isset($_POST['idNo']))) {
        $Result1 = $DB->deleteGroupById(htmlspecialchars($_GET['gId']));

        $deleteGoTo = "adminindex.php?gmId=" . $_SESSION['gmId'] . "";
        if (isset($_SERVER['QUERY_STRING'])) {
            $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
            $deleteGoTo .= $_SERVER['QUERY_STRING'];
        }
        header(sprintf("Location: %s", $deleteGoTo));
    }


    if ((isset($_GET['gId'])) && ($_GET['gId'] != "") && (isset($_POST['idNo']))) {
        //刪除圖檔
        unlink( "upload/" . htmlspecialchars($_GET['gId']) . "_*");
        $Result1 = $DB->deleteActivityById(htmlspecialchars($_GET['gId']), 'gid');

        $deleteGoTo = "adminindex.php?gmId=" . $_SESSION['gmId'] . "";
        if (isset($_SERVER['QUERY_STRING'])) {
            $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
            $deleteGoTo .= $_SERVER['QUERY_STRING'];
        }
        header(sprintf("Location: %s", $deleteGoTo));
    }


    $colname_delGroup = isset($_GET['gId'])? htmlspecialchars($_GET['gId']) : "-1";
    $row_delGroup = $DB->getGroupById($colname_delGroup)[0];
    $totalRows_delGroup = $DB->getTotalRows();
?>


<?php require_once ('_header.php'); ?>
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
            color: #333333;
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
        .style3 {color: #333333}
        .style4 {color: #666666}
        .delMess {
            color: #F00;
        }
        -->
    </style>
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
        <td>
            <table width="100%" border="0">
                <tr>
                    <td>&nbsp;</td>
                    <td align="right" valign="bottom"></td>
                </tr>
            </table>
        </td>
        <td background="img/he3_5.gif">&nbsp;</td>
    </tr>
    <tr>
        <td background="img/he3_6.gif">&nbsp;</td>
        <td align="center" background="img/he3_7.gif"><hr />
            <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">上一頁<br /><br /></a>
        </td>
        <td background="img/he3_8.gif">&nbsp;</td>
    </tr>
    <tr>
        <td background="img/he3_4.gif">&nbsp;</td>
        <td align="center" bgcolor="#A4BAE0"><span class="style2">團體主題刪除確認</span></td>
        <td background="img/he3_5.gif">&nbsp;</td>
    </tr>
    <tr>
        <td background="img/he3_4.gif">&nbsp;</td>
        <td align="center">
            <form id="form1" name="form1" method="post" action="">
                <table border="0">
                    <tr>
                        <td width="52" align="right" bgcolor="#19245F" class="style2">主題編號</td>
                        <td width="438" align="left" bgcolor="#E1E9F4" class="style3">
                            <label></label>
                            <?php echo $row_delGroup['gId']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" bgcolor="#19245F" class="style2">主題名稱</td>
                        <td align="left" bgcolor="#E1E9F4" class="style3"><?php echo $row_delGroup['gName']; ?></td>
                    </tr>
                    <tr>
                        <td align="right" bgcolor="#19245F" class="style2">主題類別</td>
                        <td align="left" bgcolor="#E1E9F4" class="style3">
                            <label></label>
                            <?php echo $row_delGroup['cName']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" bgcolor="#19245F" class="style2">主題簡介</td>
                        <td align="left" bgcolor="#E1E9F4" class="style3">
                            <label></label>
                            <?php echo nl2br($row_delGroup['gIntroduction']); ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" bgcolor="#19245F" class="style2">建立時間</td>
                        <td align="left" bgcolor="#E1E9F4" class="style3">
                            <label></label>
                            <?php echo $row_delGroup['gTime']; ?>
                        </td>
                    </tr>
                </table>
                <span class="delMess" style="">
                    <br /><br />
                    刪除主題後，連主題內的活動也會一併刪除，想清楚喔
                </span>
                <br />
                <input type="submit" name="submit" id="submit" value="確認刪除主題" />
                <input name="idNo" type="hidden" id="idNo" value="<?php echo $row_delGroup['gId']; ?>" />
            </form>
            <table border="0">
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </td>
        <td background="img/he3_5.gif">&nbsp;</td>
    </tr>
    <tr>
        <td background="img/he3_6.gif">&nbsp;</td>
        <td background="img/he3_7.gif">&nbsp;</td>
        <td background="img/he3_8.gif">&nbsp;</td>
    </tr>


<!-------------------  js in-line  ---------------------->
    <script type="text/javascript">
        $(document).ready(function(){

        });
    </script>
<?php require_once ('_footer.php'); ?>