<?php include_once('cfg.inc.php'); ?>
<?php include_once('Model/goldenbirdConn1.php'); ?>
<?php
    $vTitle = "Goldenbird活動首頁";

/*mysql_select_db($database_goldenbirdConn1, $goldenbirdConn1);
$query_Recordset1 = "SELECT * FROM `group` JOIN `class` ON class.cId=group.cId";
$Recordset1 = mysql_query($query_Recordset1, $goldenbirdConn1) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_goldenbirdConn1, $goldenbirdConn1);
$query_class_all = "SELECT * FROM `class`";
$class_all = mysql_query($query_class_all, $goldenbirdConn1) or die(mysql_error());
$row_class_all = mysql_fetch_assoc($class_all);
$totalRows_class_all = mysql_num_rows($class_all);*/

/*do{
	$matrix[ $row_Recordset1['class.cId'] ]['cName'][]= $row_Recordset1['class.cName'];
			$matrix[ $row_Recordset1['class.cId'] ]['gId'][]= $row_Recordset1['group.gId'];
		$matrix[ $row_Recordset1['class.cId'] ]['gName'][]= $row_Recordset1['group.gName'];
}while($row_Recordset1 = mysql_fetch_assoc($Recordset1));*/

?>

<?php require_once ('_header.php'); ?>
    <link href="js/jsindex.js" />
    <style type="text/css">
        #content{
            margin: 10px;
        }
        #content h3{
            margin-top: 20px;
            margin-left: 45%;
            margin-bottom: 30px;
            color:#770000;
        }

        #content .menu li{
            margin-top: 30px;
        }
        #content .menu > li{
            /*	border-bottom: 1px #333 solid;
                background-color:#FFefdf;*/
        }
        #content a{
            color:#990000;
            text-decoration:none;
            font-size:medium;
            font-family:"微軟正黑體";
        }
        #content a:hover{
            text-decoration:none;
            color:#FF3333;
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
        }
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
            <td background="img/he3_4.gif">&nbsp;</td>
            <td align="center"><img src="img/title.gif" alt="goldenbird" width="358" height="48" /></td>
            <td background="img/he3_5.gif">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" bgcolor="#FFB366" >
                <table id="block1" width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >
                    <tr >
                        <td height="20" align="center" valign="middle"><a href="index.php"><div>首頁</div></a></td>
                        <td height="20" align="center" valign="middle">所有主題</td>
                        <td align="center" valign="middle">&nbsp;</td>
                        <td height="20" align="center" valign="middle">&nbsp;</td>
                        <td height="20" align="center" valign="middle"><span class="style1"></td>
                        <td height="20" align="center" valign="middle"><a href="admin.php">登入系統</a></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td></td>
            <td width="640" valign="top">
                <div id="content">
                    <h3>主題類型種類</h3>
                    <!--jquery效果函式-->
                    <?php
                    $class_all = $DB->getClass();
                    $totalRows_class_all = $DB->getTotalRows();
                    ?>
                    <ul class="menu">
                    <?php foreach($class_all as $row_class_all){ ?>
                        <li>
                            <a href="">►<?php echo $row_class_all['cName'];?></a>
                            <ul class="submenu">
                                <?php
                                //抓某個類別的所有主題
                                $Recordset1 = $DB->getGroupByClass($row_class_all['cId']);
                                $totalRows_Recordset1 = $DB->getTotalRows();
                                ?>
                                <?php foreach($Recordset1 as $row_Recordset1){ ?>
                                    <li>
                                        <a href="act_group.php?gId=<?php echo $row_Recordset1['gId']; ?>" target="_blank">
                                            <?php echo $row_Recordset1['gName'];?>
                                        </a>
                                    </li>
                                <?php }?>
                            </ul>
                        </li>
                    <?php }?>
                    </ul>
                </div>
            </td>
            <td width="320" height='640' align="left" valign="top" bgcolor="#ddd" >
                <div id="demo_aid" >
                    <br /><br /><br /><br />
                    &nbsp;&nbsp;&nbsp;&nbsp;最新活動消息
                </div>
                <div id="demo" ><br /></div>
            </td>
            <td height="30">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" align="center">Copyright © 2013 Goldenbird All rights reserved</td>
        </tr>
    </table>


    <!--參考於http://www.minwt.com/js/1202.html梅問題網站  做出收合選單-->
    <script type="text/javascript">
        $(function(){
            $(".menu li a").click(function(){
                var _this = $(this);
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