<?php include_once('cfg.inc.php'); ?>
<?php include_once('Model/goldenbirdConn1.php'); ?>
<?php
    $vTitle = "主題活動";


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
        unset($_SESSION['MM_Username']);
        unset($_SESSION['MM_UserGroup']);
        unset($_SESSION['PrevUrl']);

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

    $colname_groupActivity = isset($_GET['gId'])? htmlspecialchars($_GET['gId']) : "-1";
    $row_groupActivity = $DB->getActivityByGroup($colname_groupActivity);
    $totalRows_groupActivity = $DB->getTotalRows();


    $colname_group = isset($_GET['gId'])? htmlspecialchars($_GET['gId']) : '-1';
    $row_group = $DB->getGroupWithClassById(0, $colname_group)[0];
    $totalRows_group = $DB->getTotalRows();
 
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
        #Group tr td {
            color: #6CF;
            background-color: #09C;
            font-weight: bold;
            margin: 0px;
            padding: 7px;
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
        <td background="img/he3_7.gif">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#CC6600" id='menu1'>
                <tr>
                    <td width="16%" height="20" align="center" valign="middle"><a href="index.php">首頁</a></td>
                    <td width="20%" height="20" align="center" valign="middle"><a href="adminindex.php<?php echo '?gmId='.$_GET['gmId'];?>">團體內主題總覽</a><a href="adminindex.php"></a></td>
                    <td width="23%" align="center" valign="middle"><?php echo $row_group['gName']; ?></td>
                    <td width="6%" height="20" align="center" valign="middle">&nbsp;</td>
                    <td width="11%" height="20" align="center" valign="middle"><span class="style1"></td>
                    <td width="24%" height="20" align="center" valign="middle"><a href="<?php echo $logoutAction ?>">登出系統</a></td>
                </tr>
            </table>
        </td>
        <td background="img/he3_8.gif">&nbsp;</td>
    </tr>
    <tr>
        <td background="img/he3_4.gif">&nbsp;</td>
        <td>
            <?php if ($totalRows_groupActivity == 0) { // Show if recordset empty ?>
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
                    <td width="21%" align="left" valign="middle">
                        <?php
                        if($row_group['gImages']=="無圖片"){
                            echo "<img src='img/goldenbird.png' width='80' />";
                        }else{
                            echo "<img src='upload/{$row_group['gImages']}' width='80' />";
                        }
                        ?>
                    </td>
                    <td width="34%" height="20" align="center" valign="middle"><?php echo $row_group['gName']; ?></td>
                    <td width="5%" height="20" align="center" valign="middle">&nbsp;</td>
                    <td width="7%" height="20" align="center" valign="middle"></td>
                    <td width="28%" height="20" align="center" valign="middle"><?php echo $row_group['cName']; ?></td>
                </tr>
                <tr>
                    <td height="20" align="left" colspan="5"="middle">
                        <?php echo nl2br($row_group['gIntroduction']); ?>
                    </td>
                </tr>
            </table>
            <table width="650" border="0" cellpadding="1" cellspacing="5" bordercolor="#A4BAE0">
                <?php if ($totalRows_groupActivity > 0) { // Show if recordset not empty ?>
                    <?php foreach ($row_groupActivity as $row ) { ?>
                        <tr>
                            <td align="left" valign="top" colspan="2">
                                <a href="activitymain.php?gmId=<?php echo $_SESSION['gmId']? $_SESSION['gmId'] : $_GET['gmId']; ?>&amp;gId=<?php echo $row['gId']; ?>&amp;aId=<?php echo $row['aId']; ?>">
                                    <?php echo $row['aTitle']; ?>
                                </a>
                            </td>
                            <td width="141" align="center" valign="middle" rowspan="2">
                                <?php
                                if($row['aImage']=="無圖片"){
                                    echo "<img src='img/goldenbird.png' width='100' />";
                                }else{
                                    echo "<img src='upload/{$row['gId']}/{$row['aImage']}' width='100' />";
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="164" align="left"><span class="style1"><?php echo $row['aTime']; ?></span></td>
                            <td width="319" align="left"><span class="style1"><?php echo $row['aLocation']." （".$row['aAddress']."）"; ?></a></span></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="3">
                                <span class="style1">
	                            <?php echo nl2br(str_replace(" ","&nbsp;",$row['aContent'])); ?>
                                </span>
                                <hr />
                            </td>
                        </tr>
                    <?php }  ?>
                <?php } // Show if recordset not empty ?>
            </table>
            <table width="650" border="0">
                <tr>
                    <td width="216" align="left">共有<?php echo $totalRows_groupActivity ?>活動</td>
                    <td width="424" align="right"><a href="addActivity.php?gmId=<?php echo $_SESSION['gmId']; ?>&amp;gId=<?php echo $row_group['gId']; ?>">＋</a></td>
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
        $().ready(function(){
            $.ajax({
                url: 'demo_ajax_load.php',
                data: [],
                type: "GET",
                async: false,
                success: function (data) {
                    $('#demo').html(data);
                }
            });
            //
            $('#btn_srh').click(function (e) {
                var data = {};
                data.srh = $('#txt_srh').val();
                $.ajax({
                    url: 'demo_ajax_search.php',
                    data: data,
                    type: "GET",
                    async: false,
                    success: function (data) {
                        $('#demo_search').html(data);
                    }
                });
            })
        });
    </script>
<?php require_once ('_footer.php'); ?>