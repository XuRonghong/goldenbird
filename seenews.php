<?php include_once('cfg.inc.php'); ?>
<?php include_once('Model/goldenbirdConn1.php'); ?>
<?php
    $vTitle = "Goldenbird活動首頁";

    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }


    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
        $mess = htmlspecialchars($_POST['mess']);
        $uPhone = htmlspecialchars($_POST['uPhone']);
        $aId = htmlspecialchars($_POST['aId']);
        // SQL query
        $insertSQLId = $DB->setMessageWithId($aId, $mess, $uPhone);

        //$insertGoTo = "seenews.php?gId=" . $row_activityGid['gId'] . "&aId=" . $_POST['aId'] . "";
        /*if (isset($_SERVER['QUERY_STRING'])) {
          $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
          $insertGoTo .= $_SERVER['QUERY_STRING'];
        }*/
    //  header(sprintf("Location: %s", $insertGoTo));
        header('refresh: 0');
    }


    $colname_activityMain = "-1";
    if (isset($_GET['aId'])) {
        $colname_activityMain = htmlspecialchars($_GET['aId']);
    }
    // SQL query
    $row_activityMain = $DB->getActivityById($colname_activityMain);
    $totalRows_activityMain = $DB->getTotalRows();


    $colname_activityGid = "-1";
    if (isset($_GET['gId'])) {
        $colname_activityGid = htmlspecialchars($_GET['gId']);
    }
    // SQL query
    $row_activityGid = $DB->getGroupById($colname_activityGid);
    $totalRows_activityGid = $DB->getTotalRows();


    //點閱率設計
    $aid = htmlspecialchars($_GET['aId']);
    if(!isset($_COOKIE[ 'WebClick'.$aid ]) ){
        //$_SESSION['WebClick'][ $_GET['aId'] ]= "1";
        setcookie('WebClick'. $aid,"true",time()+60*60*24);    //24小時
        // SQL query
        $activityClick = $DB->putActivityWithClick($colname_activityMain);
    }

    $vTitle = $row_activityMain['aTitle'];
?>


<?php require_once ('_header.php'); ?>
    <link href="css/header2.css" rel="stylesheet" type="text/css"/>
    <style type="text/css">
        <!--
        .style2 {color: #FFFFFF}
        .style3 {color: #333333}
        .style4 {color: #666666}
        -->
    </style>
</head>
<body>
    <table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td width="18" height="30">&nbsp;</td>
            <td style='background: url("img/he3_2.gif")' >&nbsp;</td>
            <td width="18" height="30">&nbsp;</td>
        </tr>
        <tr>
            <td style='background: url("img/he3_4.gif")' >&nbsp;</td>
            <td>
                <table width="100%" border="0">
                    <tr>
                        <td>&nbsp;</td>
                        <td align="right" valign="bottom"></td>
                    </tr>
                </table>
            </td>
            <td style='background: url("img/he3_5.gif")' >&nbsp;</td>
        </tr>
        <tr>
            <td style='background: url("img/he3_6.gif")' >&nbsp;</td>
            <td style='background: url("img/he3_7.gif")' >
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#CC6600" id='menu1'>
                    <tr>
                        <td width="14%" height="20" align="center" valign="middle"><a href="index.php">首頁</a></td>
                        <td width="15%" height="20" align="center" valign="middle"><a href="classify.php">所有主題</a></td>
                        <td width="27%" align="center" valign="middle"><a href="act_group.php?gId=<?php echo $row_activityGid['gId']; ?>"><?php echo $row_activityGid['gName']; ?></a></td>
                        <td width="13%" height="20" align="center" valign="middle">活動專欄</td>
                        <td width="9%" height="20" align="center" valign="middle"></td>
                        <td width="22%" height="20" align="center" valign="middle"><a href="admin.php">登入系統</a></td>
                    </tr>
                </table>
            </td>
            <td style='background: url("img/he3_8.gif")' >&nbsp;</td>
        </tr>
        <tr>
            <td style='background: url("img/he3_4.gif")' >&nbsp;</td>
            <td align="center"><br />
                <table border="0">
                    <tr>
                        <td width="88" align="right" bgcolor="#660000"><span class="style2">活動標題</span></td>
                        <td width="414" align="left" bgcolor="#ffdfdf">
                            <span class="style3">
                                <label></label><?php echo $row_activityMain[0]['aTitle']; ?>
                            </span>
                        </td>
                        <td rowspan="5">
                            <?php
                            if($row_activityMain[0]['aImage']=="無圖片"){
                                echo "<img src='img/goldenbird.png' style='width:100px' />";}
                            else{
                                echo "<img src='upload/{$row_activityMain[0]['gId']}/{$row_activityMain[0]['aImage']}' style='width:100px'></img>";
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" bgcolor="#660000"><span class="style2">活動時間</span></td>
                        <td align="left" bgcolor="#ffdfdf"><span class="style3"><?php echo $row_activityMain[0]['aTime']; ?></span></td>
                    </tr>
                    <tr>
                        <td align="right" bgcolor="#660000"><span class="style2">發佈者</span></td>
                        <td align="left" bgcolor="#ffdfdf"><span class="style3"><label></label></span>
                            <form id="form1" name="form1" method="post" action="">
                                <?php echo $row_activityGid[0]['gName']; ?>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" bgcolor="#660000"><span class="style2">活動地點</span></td>
                        <td align="left" bgcolor="#ffdfdf">
                            <span class="style3">
                                <label></label>
                                <?php echo $row_activityMain[0]['aLocation']." （".$row_activityMain[0]['aAddress']."）"; ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" bgcolor="#660000"><span class="style2">活動內容</span></td>
                        <td align="left" bgcolor="#ffdfdf">
                            <span class="style3">
                                <label></label>
                                <?php echo nl2br(str_replace(" ","&nbsp;",$row_activityMain[0]['aContent'])); ?>
                            </span>
                        </td>
                    </tr>
                </table>
                <div align="right">點閱率:<?php echo $row_activityMain[0]['aClick']; ?></div>
                <br/><br/>
                <h3>留言板</h3>
                <form id="form2" name="form2" method="POST" action="<?php echo $editFormAction; ?>">
                    <table width="650" border="0" cellpadding="1" cellspacing="0" style="border-color:#A4BAE0">
                    <?php foreach ($row_activityMain as $activityMain) { ?>
                        <tr>
                            <td width="141" align="left" valign="middle" rowspan="2">
                                <?php
                                if($activityMain['uPhone']!="Guest")
                                    echo substr($activityMain['uPhone'],strlen($row_mess['uPhone'])-9);
                                else
                                    echo "Guest";
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="319" rowspan="2" align="left" valign="top"><span class="style1"><?php echo nl2br( str_replace(' ','&nbsp;',$activityMain['mContent'])); ?></a></span></td>
                            <td></td>
                        </tr>
                        <?php if ($totalRows_activityMain > 0) { // Show if recordset not empty ?>
                            <tr>
                                <td align="left" colspan="1">&nbsp;</td>
                                <td><h5><?php echo $activityMain['mTime']; ?></h5></td>
                            </tr>
                        <?php } // Show if recordset not empty ?>
                        <tr><td colspan="3"><hr/></td></tr>
                    <?php } ?>
                        <tr>
                            <td align="left" colspan="1"><div>Guest</div>&nbsp;</td>
                            <td>
                                <div>
                                    <label>
                                        <textarea name="mess" cols="45" rows="3"></textarea>&nbsp;
                                    </label>
                                </div>
                            </td>
                            <td valign="bottom">
                                <input name="" type="submit" value="留言" />
                                <input name="aId" type="hidden" id="aId" value="<?php echo $_GET['aId']; ?>" />
                                <input name="uPhone" type="hidden" id="uPhone" value="Guest" />
                                <input type="hidden" name="MM_insert" value="form2" />
                            </td>
                        </tr>
                    </table>
                </form>
            </td>
            <td style='background: url("img/he3_5.gif")' >&nbsp;</td>
        </tr>
        <tr>
            <td style='background: url("img/he3_6.gif")' >&nbsp;</td>
            <td style='background: url("img/he3_7.gif")' >&nbsp;</td>
            <td style='background: url("img/he3_8.gif")' >&nbsp;</td>
        </tr>
<?php require_once ('_footer.php'); ?>

