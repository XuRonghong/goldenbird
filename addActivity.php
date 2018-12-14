<?php include_once('cfg.inc.php'); ?>
<?php include_once('Model/goldenbirdConn1.php'); ?>
<?php require_once ('_imageCompression.php'); ?>
<?php
    $vTitle = "新聞消息版面範例";

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

        if( $_POST['aAddress'] != "" ) $addr = $_POST['aAddress'];
        else $addr="無地址";

        // insert SQL
        $arr['aTitle'] = htmlspecialchars($_POST['aTitle']);
        $arr['aTime'] = htmlspecialchars($_POST['aTime']);
        $arr['aImage'] = $f;
        $arr['aLocation'] = htmlspecialchars($_POST['aLocation']);
        $arr['aAddress'] = $addr;
        $arr['aContent'] = htmlspecialchars($_POST['aCont']);
        $arr['gId'] = htmlspecialchars($_POST['gId']);
        $Result1 = $DB->setActivity($arr);

        $insertGoTo = "activityindex.php?gmId=" . $_SESSION['gmId'] . "&gId=" . $_GET['gId'] ."";
        if (isset($_SERVER['QUERY_STRING'])) {
            $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
            $insertGoTo .= $_SERVER['QUERY_STRING'];
        }
        header(sprintf("Location: %s", $insertGoTo));
    }

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
            <td align="center" background="img/he3_7.gif">
                <hr />
                <a href="activityindex.php?gmId= <?php echo $_SESSION['gmId'] . "&gId=" . $_GET['gId']?>">上一頁<br /><br /></a>
            </td>
            <td background="img/he3_8.gif">&nbsp;</td>
        </tr>
        <tr>
            <td background="img/he3_4.gif">&nbsp;</td>
            <td align="center" bgcolor="#A4BAE0"><span class="style2">新增活動</span></td>
            <td background="img/he3_5.gif">&nbsp;</td>
        </tr>
        <tr>
            <td background="img/he3_4.gif">&nbsp;</td>
            <td align="center">
                <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1" id="form1">
                    <table border="0">
                        <tr>
                            <td align="right" bgcolor="#19245F"><span class="style2">活動名稱</span></td>
                            <td align="left" bgcolor="#E1E9F4"><input required type="text" name="aTitle" id="aTitle" /></td>
                        </tr>
                        <tr>
                            <td align="right" bgcolor="#19245F"><span class="style2">活動時間</span></td>
                            <td align="left" bgcolor="#E1E9F4"><input required type="datetime-local" name="aTime" id="aTime" />
                                yyyy/mm/dd hh:mm
                            </td>
                        </tr>
                        <tr>
                            <td align="right" bgcolor="#19245F"><span class="style2">活動地點</span></td>
                            <td align="left" bgcolor="#E1E9F4">
                                <label>
                                    <input required type="text" name="aLocation" id="aLocation" />
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" bgcolor="#19245F" rowspan="2"><span class="style2">Guide</span></td>
                            <td align="left" bgcolor="#E1E9F4">
                                <label><input required type="radio" name="aSwitch" id="aSwitch1" class="sw">啟動On</label>
                                <label><input required type="radio" name="aSwitch" id="aSwitch2" class="sw" checked="checked">關閉Off</label>
                                &nbsp;&nbsp;&nbsp;&nbsp;(指引地址必須正確地址位置，以便指引功能正確)
                            </td>
                        </tr>
                        <tr>
                            <!--<td align="right" bgcolor="#19245F"><span class="style2"></span></td>-->
                            <td align="left" bgcolor="#E1E9F4"  id="guide_addr">
                                <label>
                                    指引地址<input type="text" name="aAddress" id="aAddress" style="width:400px;'" value="" />
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" bgcolor="#19245F"><span class="style2">活動內容</span></td>
                            <td align="left" bgcolor="#E1E9F4">
                                <label>
                                    <textarea required name="aCont" id="aCont" cols="45" rows="5"></textarea>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" bgcolor="#19245F"><span class="style2">活動圖片</span></td>
                            <td align="left" bgcolor="#E1E9F4"><label for="aImage"></label>
                                <input type="file" name="upfile" id="upfile" />
                            </td>
                        </tr>
                    </table>
                    <input type="submit" name="submit" id="submit" value="加入主題" />
                    <input name="gId" type="hidden" id="gId" value="<?php echo ($_GET['gId']); ?>" />
                    <input type="hidden" name="MM_insert" value="form1" />
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
            $("#guide_addr").hide();
            $(".sw").click(function(){
                if($("#aSwitch1").attr('checked')=="checked"){
                    $("#guide_addr").show();
                }else{
                    $("#guide_addr").hide();
                }
            });
        });
    </script>
<?php require_once ('_footer.php'); ?>