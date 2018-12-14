<?php include_once('cfg.inc.php'); ?>
<?php include_once('Model/goldenbirdConn1.php'); ?>
<?php
    $vTitle = "團體管理登入";

    $loginFormAction = $_SERVER['PHP_SELF'];
    if (isset($_GET['accesscheck'])) {
        $_SESSION['PrevUrl'] = htmlspecialchars($_GET['accesscheck']);
    }

    if(isset($_SESSION['MM_Username']))
        header("location: adminindex.php?gmId=".$_SESSION['gmId']);

    $row_manag_login = $DB->getGroupManage();
    $totalRows_manag_login = $DB->getTotalRows();


    if (isset($_POST['username'])) {
        $loginUsername = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['userpsw']);
        $MM_fldUserAuthorization = "";
        $MM_redirectLoginSuccess = "adminindex.php";
        $MM_redirectLoginFailed = "admin.php?err='y'";
        $MM_redirecttoReferrer = false;

        $LoginRS = $DB->getGroupManageByUSPW($loginUsername, $password);
        $loginFoundUser = $DB->getTotalRows();
        if ($loginFoundUser) {
            $loginStrGroup = "";
            //
            if (PHP_VERSION >= 5.1) {
                session_regenerate_id(true);
            } else {
                session_regenerate_id();
            }
            //declare two session variables and assign them
            $_SESSION['MM_Username'] = $loginUsername;
            $_SESSION['MM_UserGroup'] = $loginStrGroup;

            if (isset($_SESSION['PrevUrl']) && false) {
                $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];
            }

            $row = ($LoginRS);
            $_SESSION['gmId'] = $row['gmId'];
            /*$sql="SELECT gmId FROM `group_manage` WHERE gUs=".$loginUsername;
            $result = mysql_query($sql, $goldenbirdConn1) or die(mysql_error())*/

            header("Location: " . $MM_redirectLoginSuccess."?gmId=".$row['gmId'] );
            exit();
        }
        else {
            header("Location: ". $MM_redirectLoginFailed );
            exit();
        }
    }

?>

<?php require_once ('_header.php'); ?>
    <link href="css/header2.css" rel="stylesheet" type="text/css"/>
    <style type="text/css">
        <!--
        .style1 {color: #666666}
        #form1 p .err {
            color: #F00;
        }
        #form1 .err {
            color: #F00;
        }
        -->
    </style>
</head>
<body onLoad="document.forms.form1.username.focus()" >
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
                    <td align="right" valign="bottom">&nbsp;</td>
                </tr>
            </table>
        </td>
        <td background="img/he3_5.gif">&nbsp;</td>
    </tr>
    <tr>
        <td background="img/he3_6.gif">&nbsp;</td>
        <td background="img/he3_7.gif">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#CC6600" id='menu1'>
                <tr>
                    <td width="16%" height="20" align="center" valign="middle"><a href="index.php">首頁</a></td>
                    <td width="24%" height="20" align="center" valign="middle"><a href="classify.php">所有主題</a></td>
                    <td width="15%" align="center" valign="middle">&nbsp;</td>
                    <td width="6%" height="20" align="center" valign="middle">&nbsp;</td>
                    <td width="14%" height="20" align="center" valign="middle"><a href="addmem.php">建立團體</a><span class="style1"></td>
                    <td width="25%" height="20" align="center" valign="middle">登入系統</td>
                </tr>
            </table></td>
        <td background="img/he3_8.gif">&nbsp;</td>
    </tr>
    <tr>
        <td background="img/he3_4.gif">&nbsp;</td>
        <td align="center">
            <form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
                <br />
                <table border="0">
                    <tr>
                        <td>
                            <table border="0">
                                <tr>
                                    <td>管理帳號</td>
                                    <td><input type="text" name="username" id="username" /></td>
                                </tr>
                                <tr>
                                    <td>管理密碼</td>
                                    <td><input type="password" name="userpsw" id="userpsw" /></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <p>
                    <input type="submit" name="submit" id="submit" value="登入管理系統" />
                    <!-- &nbsp;&nbsp;&nbsp;&nbsp;<a href="forget_pass.php">忘記密碼?</a>-->
                </p>
                <?php if(isset($_GET['err'])){ ?>
                    <p class="err" >帳號或密碼錯誤</p>
                <?php } ?>
            </form>
        </td>
        <td background="img/he3_5.gif">&nbsp;</td>
    </tr>
    <tr>
        <td background="img/he3_6.gif">&nbsp;</td>
        <td align="center" background="img/he3_7.gif">&nbsp;</td>
        <td background="img/he3_8.gif">&nbsp;</td>
    </tr>
<?php require_once ('_footer.php'); ?>