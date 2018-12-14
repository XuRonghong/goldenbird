<?php include_once('cfg.inc.php'); ?>
<?php include_once('Model/goldenbirdConn1.php'); ?>
<?php
    $vTitle = "團體主題管理";


    // ** Logout the current user. **
    $logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
//    if($_GET['gmId']!=$_SESSION['gmId']){header("Location: ".$logoutAction);}
    if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
        $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
    }


    //假如有登出指令
    if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
        //to fully log out a visitor we need to clear the session varialbles
        $_SESSION['MM_Username'] = NULL;
        $_SESSION['MM_UserGroup'] = NULL;
        $_SESSION['PrevUrl'] = NULL;
        $_SESSION['gmId'] = NULL;
        unset($_SESSION['MM_Username']);
        unset($_SESSION['MM_UserGroup']);
        unset($_SESSION['PrevUrl']);
        unset($_SESSION['gmId']);

        $logoutGoTo = "admin.php";
        if ($logoutGoTo) {
            header("Location: $logoutGoTo");
            exit;
        }
    }


    /*
     * DreamweaverCS5 創造出的登入驗證
     */
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

    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }


    //假如有更新指令
    if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "from2")) {
        $gname = htmlspecialchars($_POST['textfield']);
        $gphone = htmlspecialchars($_POST['textfield2']);
        $gpw = htmlspecialchars($_POST['textfield4']);
        $gmid = htmlspecialchars($_POST['hiddenField']);
        $Result1 = $DB->putGroupManage($gname,$gphone,$gpw,$gmid);

        header("Location: admin.php");
    }


    //該群組資料
    $colname_groupManage = isset($_GET['gmId'])? htmlspecialchars($_GET['gmId']) : '-1';
    $row_groupManage = $DB->getGroupManage($colname_groupManage)[0];
    $totalRows_groupManage = $DB->getTotalRows();


    //根據種類選出對應群組
    $colname_group = isset($_GET['gmId'])? htmlspecialchars($_GET['gmId']) : '-1';
    $row_group = $DB->getGroupWithClassById($colname_group);
    $totalRows_group = $DB->getTotalRows();


//    $colname_groupManage = "-1";
//    if (isset($_SESSION['MM_Username'])) {
//        $colname_groupManage = $_SESSION['MM_Username'];
//    }
//    $row_groupManage = $DB->getGroupWithClassById($colname_groupManage);

?>


<?php require_once ('_header.php'); ?>
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
            <td>&nbsp;</td>
            <td background="img/he3_5.gif">&nbsp;</td>
        </tr>
        <tr>
            <td background="img/he3_6.gif">&nbsp;</td>
            <td colspan="2">
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#CC6600" id='menu1'>
                    <tr>
                        <td width="17%" height="20" align="center" valign="middle"><a href="index.php">首頁</a></td>
                        <td width="25%" height="20" align="center" valign="middle">團體內主題總覽</td>
                        <td width="12%" align="center" valign="middle">&nbsp;</td>
                        <td width="6%" height="20" align="center" valign="middle">&nbsp;</td>
                        <td width="13%" height="20" align="center" valign="middle"><span class="style1"></td>
                        <td width="27%" height="20" align="center" valign="middle"><a href="<?php echo $logoutAction ?>">登出系統</a></td>
                    </tr>
                </table>
            </td>
            <td background="img/he3_8.gif">&nbsp;</td>
        </tr>
        <tr>
            <td background="img/he3_4.gif">&nbsp;</td>
            <td valign="top">
                <table width="100%" border="0">
                    <tr>
                        <?php if ($totalRows_group == 0) { // Show if recordset empty ?>
                            <td align="center"><span class="style2">目前無任何消息發佈</span></td>
                        <?php } // Show if recordset empty ?>
                    </tr>
                </table>
                <form action="<?php echo $editFormAction; ?>" method="POST" name="from2">
                    <table width="80%" border="0" align="center" cellpadding="0" cellspacing="2" bgcolor="#ccffff">
                        <tr>
                            <td align="left">Name：
                                <input type="text" name="textfield" id="textfield" value="<?php echo $row_groupManage['gName']; ?>"/>
                                <input name="hiddenField" type="hidden" id="hiddenField" value="<?php echo $row_groupManage['gmId']; ?>" />
                            </td>
                            <td align="right">tel:
                                <input type="text" name="textfield2" id="textfield2" value="<?php echo $row_groupManage['gPhone']; ?>"  maxlength="10"/>
                            </td>
                            <td rowspan="2"><input type="submit" name="button" id="button" value="更改" /></td>
                        </tr>
                        <tr>
                            <td align="left">帳號：<?php echo $row_groupManage['gUs']; ?></td>
                            <td align="right">密碼:
                                <input type="password" name="textfield4" id="textfield4" value="<?php echo $row_groupManage['gPw']; ?>"/>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="MM_update" value="from2" />
                </form>
                <table width="650" border="1" cellpadding="1" cellspacing="0" bordercolor="#A4BAE0">
                    <tr>
                        <td width="99" align="center" valign="bottom">主題編號</td>
                        <td width="346" align="center" valign="middle">主題名稱</td>
                        <td width="121" align="center" valign="middle">主題類型</td>
                        <td width="96" align="center" valign="middle"><p>刪除/修改</p></td>
                    </tr>
                    <?php $i=0;  do { ?>
                        <tr>
                            <?php if ($totalRows_group > 0) { // Show if recordset not empty ?>
                                <td align="center"><span class="style1"><?php echo $row_group[$i]['gId']; ?></span></td>
                                <td align="left">
                                    <span class="style1">
                                        <a href="activityindex.php?gmId=<?php echo $_SESSION['gmId']? $_SESSION['gmId'] : $_GET['gmId']; ?>&amp;gId=<?php echo $row_group[$i]['gId']; ?>">
                                            <?php echo $row_group[$i]['gName']; ?>
                                        </a>
                                    </span>
                                </td>
                                <td align="center"><span class="style1"></span><?php echo $row_group[$i]['cName']; ?></td>
                                <td align="center">
                                    <span class="style1">
                                        <a href="delnews.php?gmId=<?php echo $_SESSION['gmId']; ?>&amp;gId=<?php echo $row_group[$i]['gId']; ?>">－</a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="editnews.php?gmId=<?php echo $_SESSION['gmId']; ?>&amp;gId=<?php echo $row_group[$i]['gId']; ?>">／</a>
                                    </span>
                                </td>
                            <?php } // Show if recordset not empty ?>
                        </tr>
                    <?php } while ( $row_group[$i++] ); ?>
                </table>
                <table width="650" border="0">
                    <tr>
                        <td width="212" align="left">共有<?php echo $totalRows_group ?>個主題</td>
                        <td width="385" align="right">新增:</td>
                        <td width="39" align="right"><a href="addnews.php?gmId=<?php echo $_SESSION['gmId']; ?>"><h2>＋</h2></a></td>
                    </tr>
                </table>
            </td>
            <td bgcolor="#eee" rowspan="2">
                <div id="fb-root"></div>
                <script>
                    (function(d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s); js.id = id;
                        js.src = "//connect.facebook.net/zh_TW/all.js#xfbml=1";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));
                </script>
                <div class="fb-comments" data-href="https://www.facebook.com/Goldenbird123" data-width="350"></div>
            </td>
<!--            <td background="img/he3_5.gif">&nbsp;</td>-->
        </tr>
        <tr>
            <td background="img/he3_6.gif">&nbsp;</td>
            <td background="img/he3_7.gif">&nbsp;</td>
<!--            <td background="img/he3_8.gif">&nbsp;</td>-->
        </tr>


<?php require_once ('_footer.php'); ?>