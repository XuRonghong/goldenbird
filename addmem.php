<?php include_once('cfg.inc.php'); ?>
<?php include_once('Model/goldenbirdConn1.php'); ?>
<?php
    $vTitle = "會員專區-加入會員";

    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }

    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
        $mUs = htmlspecialchars($_POST['mUs']);
        $result = $DB->getGroupManageByUs($mUs);
        $total = $DB->getTotalRows();
        if($total) die('帳號重複');

        $arr['gUs']=htmlspecialchars($_POST['mUs']);
        $arr['mPw']=htmlspecialchars($_POST['mPw']);
        $arr['gName']=htmlspecialchars($_POST['mName']);
        $arr['gPhone']=htmlspecialchars($_POST['mPhone']);
        $Result1 = $DB->setGroupManage($arr);

        $insertGoTo = "admin.php";
        if (isset($_SERVER['QUERY_STRING'])) {
            $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
            $insertGoTo .= $_SERVER['QUERY_STRING'];
        }
        header(sprintf("Location: %s", $insertGoTo));
    }

    $row_mUs = $DB->getGroupManage();
    $totalRows_mUs = $DB->getTotalRows();

?>


<?php require_once ('_header.php'); ?>
    <style type="text/css">
    <!--
    .style2 {color: #FFFFFF}
    .style4 {font-size: 10pt}
    .style6 {color: #FF0000}
    -->
    </style>
    <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

    <script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
    <script src="SpryAssets/SpryData.js" type="text/javascript"></script>
    <script src="SpryAssets/xpath.js" type="text/javascript"></script>
    <script language="javascript">
    function chkpas(pas_2){
        if(document.getElementById("pass1").value==pas_2){
            return true;
        }else{
            return false;
        }
    }
    </script>
    <script language="javascript">
    function chkUserID(idObj){
        if(0/*idObj.value.length < 4*/){
            /*當使用者輸入的帳號長度小於4個字元
            則顯示錯誤提示*/
            document.getElementById("idErrMsg").innerHTML = "<img src='img/info.gif' /><font color='red'> 帳號長度不得小於4個字元</font>";
        }else{
            /*否則,以GET方式呼叫執行chkID.phpt程式
            並傳遞使用者所輸入的帳號進行驗證*/
            Spry.Utils.loadURL("GET","chkID.php?newID=" + idObj.value,false,chkIdRes);
        }

        function chkIdRes(IDreq){
            var IDresult = IDreq.xhRequest.responseText;
            if(IDresult!=0){
            //已有相同的帳號存在
                document.getElementById("idErrMsg").innerHTML = "<img src='img/block1.gif' /><font color='red'> 此帳號已被使用!!</font>";
            }else{
            //未發現相同的帳號
                document.getElementById("idErrMsg").innerHTML = "<img src='img/apply2.gif' /><font color='green'> 恭喜,此帳號可註冊使用!</font>";
            }
        }
    }
    </script>
</head>
<body>
    <table width="750" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td width="18">&nbsp;</td>
            <td width="714" background="img/he3_7.gif">&nbsp;</td>
            <td width="18">&nbsp;</td>
        </tr>
        <tr>
            <td background="img/he3_4.gif">&nbsp;</td>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td><a href="index.php"><a href="login.php"></a></td>
                    </tr>
                </table>
            </td>
            <td background="img/he3_5.gif">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="center" background="img/he3_2.gif">團 體 新 建<br /><hr /></td>
            <td background="img/he3_3.gif">&nbsp;</td>
        </tr>
        <tr>
            <td background="img/he3_4.gif">&nbsp;</td>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center"><br />
                            <form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
                                <table width="650" border="0" cellspacing="0" cellpadding="5">
                                    <tr class="m08">
                                        <td width="150" align="right"><span class="style4">帳　　號：</span></td>
                                        <td align="left" class="style4"><input name="mUs" type="text" id="mUs" onBlur="chkUserID(this);" size="25" maxlength="30" ><span id="idErrMsg"> </span></td>
                                    </tr>
                                    <tr class="m08">
                                        <td align="right"><span class="style4">密　　碼：</span></td>
                                        <td align="left" class="style4">
                                            <span id="sprytextfield8">
                                                <input name="mPw" id="mPw" type="password" size="25" maxlength="30" />
                                                <span class="textfieldRequiredMsg">請輸入密碼。</span>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="m08"></tr>
                                    <tr class="m08">
                                        <td width="150" align="right"><span class="style4">團　　名：</span></td>
                                        <td align="left" class="style4">
                                            <span class="style4">
                                                <span id="sprytextfield4">
                                                    <input name="mName" type="text" size="25" maxlength="40" id="mName" />
                                                    <span class="textfieldRequiredMsg">請輸入團體名稱。</span>
                                                </span>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="m08"></tr>
                                    <tr class="m08">
                                        <td align="right"><span class="style4">聯絡電話：</span></td>
                                        <td align="left" class="style4">
                                            <span id="sprytextfield6">
                                                <input name="mPhone" type="tel" id="mPhone" size="25" maxlength="50" />
                                                <span class="textfieldRequiredMsg">請輸入團體連絡電話。</span>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                                <input type="submit" name="submit" id="submit" value="加入" />
                                <input type="hidden" name="MM_insert" value="form1" />
                            </form>
                        </td>
                    </tr>
                </table>
            </td>
            <td background="img/he3_5.gif">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td background="img/he3_2.gif">&nbsp;</td>
            <td background="img/he3_3.gif">&nbsp;</td>
        </tr>
    </table>
    <table width="750" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr align="center">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>


<script type="text/javascript">
<!--
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "none");

var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "phone_number");

var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8", "none", {validateOn:["blur"]});
//-->
</script>
<?php require_once ('_footer.php'); ?>