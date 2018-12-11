<?php include_once('cfg.inc.php'); ?>
<?php include_once('Model/goldenbirdConn1.php'); ?>
<?php
    $vTitle = "Goldenbird活動首頁";
    $show_num = 20;     //show 15 筆資料

    $currentPage = $_SERVER["PHP_SELF"];
    //搜尋字串
    $search = (isset($_POST['search']))? htmlspecialchars($_POST['search']) : null;


    $maxRows_activityClick = 10;
    $pageNum_activityClick = 0;
    if (isset($_GET['pageNum_activityClick'])) {
        $pageNum_activityClick = htmlspecialchars($_GET['pageNum_activityClick']);
    }
    $startRow_activityClick = $pageNum_activityClick * $maxRows_activityClick;
    // SQL query
    $row_activityClick = $DB->getActivityWithClick($search, $startRow_activityClick,$maxRows_activityClick);


    if (isset($_GET['totalRows_activityClick'])) {
        $totalRows_activityClick = htmlspecialchars($_GET['totalRows_activityClick']);
    } else {
        // SQL query total row
        $totalRows_activityClick = $DB->getTotalRows();
    }
    $totalPages_activityClick = ceil($totalRows_activityClick/$maxRows_activityClick)-1;


    //
    $queryString_activityClick = "";
    if (!empty($_SERVER['QUERY_STRING'])) {
        $params = explode("&", $_SERVER['QUERY_STRING']);
        $newParams = array();
        foreach ($params as $param) {
            if (stristr($param, "pageNum_activityClick") == false && stristr($param, "totalRows_activityClick") == false) {
                array_push($newParams, $param);
            }
        }
        if (count($newParams) != 0) {
            $queryString_activityClick = "&" . htmlentities(implode("&", $newParams));
        }
    }
    $queryString_activityClick = sprintf("&totalRows_activityClick=%d%s", $totalRows_activityClick, $queryString_activityClick);

    //最多點擊活動消息
    $row_activityClick = $DB->getActivityWithClick($search);
    $totalRows_activityClick = $DB->getTotalRows();

    //最新活動消息
    $row_activityNew = $DB->getActivityWithNew(false, 0, 5);
    $totalRows_activityNew = $DB->getTotalRows();

?>

<?php require_once ('_header.php'); ?>
    <link href="css/header.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td height="30">&nbsp;</td>
            <td align="center" style='background: url("img/he3_2.gif")' >&nbsp;</td>
            <td height="30">&nbsp;</td>
            <td height="30">&nbsp;</td>
        </tr>
        <tr>
            <td style='background: url("img/he3_4.gif")' >&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td style='background: url("img/he3_5.gif")' >&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" bgcolor="#FFB366" >
                <table id="block1" width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >
                    <tr >
                        <td height="20" align="center" valign="middle"><a href="index.php"><div>首頁</div></a></td>
                        <td height="20" align="center" valign="middle"><a href="classify.php">所有主題</a></td>
                        <td align="center" valign="middle">&nbsp;</td>
                        <td height="20" align="center" valign="middle">&nbsp;</td>
                        <td height="20" align="center" valign="middle"></td>
                        <td height="20" align="center" valign="middle"><a href="admin.php"><?php if(!isset($_SESSION['MM_Username'])){echo "登入系統";} else{ echo "您還未登出!";}?></a></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <div >
                    <form action="<?php echo $currentPage; ?>" method="post">
                        搜尋活動:<input type="text" id='txt_srh' name="search" />
                        <input type="button" id='btn_srh' value="搜尋" onclick="this.form.submit()" />
                    </form>
                </div>
            </td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td width="640" valign="top">
                <div id="contain" ><br/></div>
                <br/>
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >
                    <tr>
                        <td style='background: url("img/he3_4.gif")' >&nbsp;</td>
                        <td>
                            <table width="100%" border="0">
                                <tr>
                                    <td align="center"><span class="style2">熱閱活動(top 15)</span></td>
                                </tr>
                            </table>
                            <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#A4BAE0">
                                <tr>
                                    <td width="20" align="center" valign="middle"></td>
                                    <td width="110" align="center" valign="middle">發布時間</td>
                                    <td width="90" align="center" valign="middle">發佈者</td>
                                    <td width="332" align="center" valign="middle">消息主題</td>
                                    <td width="80" align="center" valign="middle"><p>點閱率</p>          </td>
                                </tr>
                                <?php $i=0; ?>
                                <?php while ($row = $row_activityClick[$i]) {   if($i>$show_num)break;  ?>
                                    <tr>
                                        <td align="center"><?php echo $i++; ?></td>
                                        <td align="center"><?php echo $row['aPoTime']; ?></td>
                                        <td align="center">
                                            <form id="form1" name="form1" method="post" action="">
                                                <?php echo $row['gName']; ?>
                                            </form>
                                        </td>
                                        <td align="left">
                                            <a href="seenews.php?gId=<?php echo $row['gId']; ?>&amp;aId=<?php echo $row['aId']; ?>"><?php echo $row['aTitle']; ?></a>
                                        </td>
                                        <td align="center"><?php echo $row['aClick']; ?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                            <table width="650" border="0" align="center" cellpadding="0" cellspacing="2">
                                <tr>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                        <td></td>
                        <td height="30">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style='background: url("img/he3_6.gif")' >&nbsp;</td>
                        <td align="center" style='background: url("img/he3_7.gif")'  colspan="2"></td>
                        <td style='background: url("img/he3_8.gif")' >&nbsp;</td>
                    </tr>
                </table>
            </td>
            <td width="320" align="left" valign="top" bgcolor="#ddd" >
                <div id="demo_aid" ><br />
                    &nbsp;&nbsp;&nbsp;&nbsp;最新活動消息
                </div>
                <div id="demo" >
                    <?php foreach ($row_activityNew as $row){ ?>
                        <a href="seenews.php?gId=<?php echo $row['gId']; ?>&amp;aId=<?php echo $row['aId']; ?>"><?php echo $row['aTitle']; ?></a>
                        <div style="text-align: right"><?php echo $row['aPoTime']; ?></div>
                        <br>
                    <?php } ?>
                </div>
                <br />
                Facebook<br/>
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
            <td height="30">&nbsp;</td>
        </tr>
<?php require_once ('_footer.php'); ?>