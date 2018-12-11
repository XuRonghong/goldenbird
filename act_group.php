<?php include_once('cfg.inc.php'); ?>
<?php include_once('Model/goldenbirdConn1.php'); ?>
<?php
    $vTitle = "Goldenbird活動首頁";

    $colname_Group_activity = isset($_GET['gId'])? htmlspecialchars($_GET['gId']) : '-1';

    //SQL query 
    $row_Group_activity = $DB->getGroupById($colname_Group_activity)[0];
    $totalRows_Group_activity = $DB->getTotalRows();


    //SQL query
    $row_groupActivity = $DB->getActivityByGroup($colname_Group_activity);
    $totalRows_groupActivity = $DB->getTotalRows();
    
/*mysql_select_db($database_goldenbirdConn1, $goldenbirdConn1);
$query_class_all = "SELECT * FROM `class`";
$class_all = mysql_query($query_class_all, $goldenbirdConn1) or die(mysql_error());
$row_class_all = mysql_fetch_assoc($class_all);
$totalRows_class_all = mysql_num_rows($class_all);*/

/*do{
	$matrix[ $row_Group_activity['class.cId'] ]['cName'][]= $row_Group_activity['class.cName'];
			$matrix[ $row_Group_activity['class.cId'] ]['gId'][]= $row_Group_activity['group.gId'];
		$matrix[ $row_Group_activity['class.cId'] ]['gName'][]= $row_Group_activity['group.gName'];
}while($row_Group_activity = mysql_fetch_assoc($Group_activity));*/


    $vTitle = $row_Group_activity['gName'];
?>

<?php require_once ('_header.php'); ?>
    <link href="js/jsindex.js" />
    <style type="text/css">
    #Group{
        box-shadow: 2px 2px 10px #909090;
    }
    #Group tr td{
        font-family:"微軟正黑體";
        font-size:medium;
        color:#FF6666;
    }
    table#cont {
        /*left:50%;
        margin-left:325px;	*/
    }
    /*#content{
        margin: 10px;
    }
    #content h3{
        margin-top: 20px;
        margin-left: 45%;
        margin-bottom: 30px;
    }

    #content .menu li{
        margin-top: 30px;
    }
    #content .menu > li{
        border-bottom: 1px #333 solid;
        background-color:#FFefdf;
    }
    #content a{
        color:#222;
        text-decoration:none;
        font-size:medium;
        font-family:"微軟正黑體";
    }
    #content a:hover{
        text-decoration:none;
        color:#777;
    }
    #content ul{
        margin:10px;
        padding:0px;
        list-style-type: none;
    }
    #content ul.submenu{
        display:none;
        margin-top: 10px;
        margin-left:45px;
        list-style-type:nono;
        color:#555;
    }*/
    </style>
    <link href="css/header.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td height="30">&nbsp;</td>
            <td align="center" background="img/he3_2.gif">&nbsp;</td>
            <td height="30">&nbsp;</td>
            <td height="30">&nbsp;</td>
        </tr>
        <tr>
            <td height="30">&nbsp;</td>
            <td align="center" background="img/he3_2.gif">&nbsp;</td>
            <td height="30">&nbsp;</td>
            <td height="30">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" bgcolor="#FFB366" >
                <table id="block1" width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >
                    <tr >
                        <td width="12%" height="20" align="center" valign="middle"><a href="index.php"><div>首頁</div></a></td>
                        <td width="17%" height="20" align="center" valign="middle"><a href="classify.php">所有主題</a></td>
                        <td width="33%" align="center" valign="middle"><?php echo $row_Group_activity['gName']; ?></td>
                        <td width="17%" height="20" align="center" valign="middle">&nbsp;</td>
                        <td width="3%" height="20" align="center" valign="middle"><span class="style1"></td>
                        <td width="18%" height="20" align="center" valign="middle"><a href="admin.php">登入系統</a></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td></td>
            <td width="640" colspan="2" valign="top" align="center">
                <table width="100%" border="0">
                    <tr>
                        <td align="center">&nbsp;</td>
                    </tr>
                </table>
                <table width="70%" border="0" align="center" cellpadding="0" cellspacing="2">
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                    </tr>
                </table>
                <table width="650px" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#880000" id="Group">
                    <tr>
                        <td width="21%" align="left" valign="middle">
                            <?php
                            if($row_Group_activity['gImages']=="無圖片"){
                                echo "<img src='img/goldenbird.png' width='80' />";
                            } else {
                                echo "<img src='upload/{$_GET['gmId']}_{$row_Group_activity['gImages']}' width='80' />";
                            }
                            ?>
                        </td>
                        <td width="34%" height="20" align="center" valign="middle"><?php echo $row_Group_activity['gName']; ?></td>
                        <td width="5%" height="20" align="center" valign="middle">&nbsp;</td>
                        <td width="7%" height="20" align="center" valign="middle"><span class="style1"></td>
                        <td width="28%" height="20" align="center" valign="middle"><?php echo $row_Group_activity['cName']; ?></td>
                    </tr>
                    <tr>
                        <td height="20" align="left" colspan="5"="middle"><?php echo nl2br($row_Group_activity['gIntroduction']); ?></td>
                    </tr>
                </table>
                <br />
                <table width="650px" border="0" cellpadding="0" cellspacing="1" bordercolor="#A4BAE0" id='cont'>
                    <?php if ($totalRows_groupActivity > 0) { // Show if recordset not empty ?>
                        <?php
                        $j=1;
                        while ($row = $row_groupActivity[$j]){ ?>
                            <tr><td><br/>～<?php echo $j++;?></td></tr>
                            <tr>
                                <td align="left" valign="top" colspan="2">
                                    <a href="seenews.php?gId=<?php echo $row_Group_activity['gId']; ?>&amp;aId=<?php echo  $row['aId']; ?>">
                                        <h3><?php echo $row['aTitle']; ?></h3>
                                    </a>
                                </td>
                                <td width="141" align="center" valign="middle" rowspan="2">
                                    <?php
                                    if($row['aImage']=="無圖片"){
                                        echo "<img src='img/goldenbird.png' width='100'></img>";
                                    }else{
                                        echo "<a href='upload/{$row['gId']}/{$row['aImage']}'><img src='upload/{$row['gId']}/{$row['aImage']}' width='100'></img></a>";
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
                                    <span class="style1"><?php echo nl2br(str_replace(" ","&nbsp;",$row['aContent'])); ?></span>
                                    <hr />
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    <?php } // Show if recordset not empty ?>
                </table>
                <table width="650" border="0">
                    <tr>
                        <td width="80" align="left">共有<?php echo $totalRows_groupActivity ?> 活動</td>
                        <td width="560" align="right">&nbsp;</td>
                    </tr>
                </table>
            </td>
            <td >&nbsp;</td>
        </tr>


    <!--參考於http://www.minwt.com/js/1202.html梅問題網站  做出收合選單-->
    <script type="text/javascript">
        $(function(){
            $(".menu li a").click(function(){
                var _this=$(this);
                if(_this.next("ul").length>0){
                    if(_this.next().is(":visible")){
                        //隱藏子選單並替換符號
                        _this.html(_this.html().replace("▼","►")).next().hide();
                    }else{
                        //開啟子選單並替換符號
                        _this.html(_this.html().replace("►","▼")).next().show();
                    }
                    //關閉連結
                    return false;
                }
            });
            //消除連結虛線框
            $("a").focus( function(){
                $(this).blur();
            });
        });
    </script>
<?php require_once ('_footer.php'); ?>