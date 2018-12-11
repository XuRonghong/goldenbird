<?php require_once('Connections/goldenbirdConn1.php'); ?>
<?php 
	
	mysql_select_db("goldenbird",$goldenbirdConn1);
	$sql="select * from `group` left join `activity` on group.gId=activity.gId  
			order by `aPoTime` desc  limit 0,8";
	$result = mysql_query($sql) or die(mysql_error());
	while($rows = mysql_fetch_array($result)){
		echo $rows['gName']." <br>&nbsp;&nbsp;&nbsp;&nbsp;發佈了 ". "<a href='seenews.php?gId=".$rows['gId']."&aId=".$rows['aId']."'>".
		$rows['aTitle'] ."</a> 新活動";
		
		echo " <br><br>";
	}
	
	mysql_free_result($result);


?>