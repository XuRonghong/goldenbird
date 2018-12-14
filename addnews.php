<?php include_once('cfg.inc.php'); ?>
<?php include_once('Model/goldenbirdConn1.php'); ?>
<?php require_once ('_imageCompression.php'); ?>
<?php
    $vTitle = "新增主題";

    ob_start();

//    if($_GET['gmId']!=$_SESSION['gmId'] ){header("Location: admin.php");}

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

    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
        //上傳圖片
        if($_FILES['upfile']){
            if($_FILES['upfile']['size']>1000000){
                die( '上傳檔案錯誤，可能太大');
            }else if($_FILES['upfile']['size']>0){
                //echo $_FILES['upfile']['type'];

                //$upload_dir =  "../uploadFiles/";
                $upload_dir =  "upload/". htmlspecialchars($_GET['gId']) ."/";

                $upload_file = $upload_dir . $to = iconv("UTF-8", "big5", $_FILES["upfile"]["name"]);

                if(!is_dir($upload_dir)) mkdir ("upload/". htmlspecialchars($_GET['gId']),0644);

                /*****************使用圖片壓縮lib***************/
                $filename = _UPLOADPIC($_FILES["upfile"], $maxsize=1000000, $upload_file, $newname='date');
                $show_pic_scal = show_pic_scal(230, 230, $filename);
                resize($filename,$show_pic_scal[0],$show_pic_scal[1]);

                //������������������������������������������������������
                //if (!move_uploaded_file($_FILES["upfile"]["tmp_name"], $upload_file))echo 'upload errer';
            }

            //圖片名稱寫入資料庫
            if( !isset($_FILES['upfile']['name']) || $_FILES['upfile']['name']==NULL){
                $f="無圖片";
            }else{
                $f=$_FILES['upfile']['name'];
            }
        }

        // insert SQL
        $arr['gName'] = htmlspecialchars($_POST['gName']);
        $arr['gIntroduction'] = htmlspecialchars($_POST['gIntro']);
        $arr['gImages'] = $f;
        $arr['cId'] = htmlspecialchars($_POST['type']);
        $arr['gmId'] = $_POST['gmId']? htmlspecialchars($_POST['gmId']) : 0;
        $Result1 = $DB->setGroup($arr);

        $insertGoTo = "adminindex.php?gmId=" . $_SESSION['gmId'] . "";
        if (isset($_SERVER['QUERY_STRING'])) {
            $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
            $insertGoTo .= $_SERVER['QUERY_STRING'];
        }
        header(sprintf("Location: %s", $insertGoTo));
    }

    //
    $row_class = $DB->getClass();
    $totalRows_class = $DB->getTotalRows();

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
                        <td align="left" bgcolor="#E1E9F4"><input required type="text" name="gName" id="gName" /></td>
                    </tr>
                    <tr>
                        <td align="right" bgcolor="#19245F"><span class="style2">主題類別</span></td>
                        <td align="left" bgcolor="#E1E9F4">
                            <label>
                                <select required name="type" id="type">
                                    <option value="" <?php if (!(strcmp("", 0))) {echo "selected=\"selected\"";} ?>></option>
                                    <?php
                                    foreach ($row_class as $row) {
                                        ?>
                                        <option value="<?php echo $row['cId']?>" <?php if (!(strcmp($row['cId'], 0))) {echo "selected=\"selected\"";} ?>>
                                            <?php echo $row['cName']?>
                                        </option>
                                        <?php
                                    }
//                                    if($totalRows_class > 0) {
//                                        mysql_data_seek($class, 0);
//                                        $row_class = mysql_fetch_assoc($class);
//                                    }
                                    ?>
                                </select>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" bgcolor="#19245F"><span class="style2">主題圖片</span></td>
                        <td align="left" bgcolor="#E1E9F4"><input type="file" name="upfile" id="upfile"/></td>
                    </tr>
                    <tr>
                        <td align="right" bgcolor="#19245F"><span class="style2">主題簡介</span></td>
                        <td align="left" bgcolor="#E1E9F4">
                            <label>
                                <textarea required name="gIntro" id="gIntro" cols="45" rows="5"></textarea>
                            </label>
                        </td>
                    </tr>
                </table>
                <input type="submit" name="submit" id="submit" value="加入主題" />
                <input type="hidden" name="MM_insert" value="form1" />
                <input name="gmId" type="hidden" id="gmId" value="<?php echo $_SESSION['gmId']; ?>" />
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