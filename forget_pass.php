<?php
if( ($_POST['gUs']!='') && ($_POST['gPhone']!='') && ($_POST['toEmail']!='') ){
	mail($_POST['toEmail'],'123456','') or die("寄信功能可能有問題");
	header("location: admin.php");
}
?>
<!DOCTYPE html >
<head>
<meta charset="utf-8" />
<title>求密碼</title>
<style type="text/css">
<!--
#contain{
	width:300px;
}
#gUs,#gPhone,#toEmail{
	margin-left:130px;
	margin-top:-15px;
}
#btn_fi{
	margin-left:230px;	
}
-->
</style>
</head>

<body>
<div id='contain'>
<form action="" method="post" id="forget_pw">
帳號:<input name="gUs" id="gUs" type="text" /><br/>
團體電話:<input name="gPhone" id="gPhone" type="text" /><br/>
<br/>
取得密碼信箱:<input name="toEmail" id="toEmail" type="text" /><br/>
<input id="btn_fi" type="submit" value="find" />
</form>
</div>
</body>
</html>