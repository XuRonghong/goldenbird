<?php require_once('Connections/goldenbirdConn1.php'); ?>
<?php 
	
	$search = $_GET['srh'];
	echo "搜尋 `".$search."` 關鍵字";
	
	
	mysql_select_db("goldenbird",$goldenbirdConn1);
	$sql="select * 
			from `activity` join `group` on group.gId=activity.gId 
			where aTitle LIKE '%". $search . "%' OR 
				aContent LIKE '%". $search . "%' OR
				gName LIKE '%". $search . "%' 
			order by `aClick` desc  limit 0,99";
	$result = mysql_query($sql) or die(mysql_error());
	
	$i=0;
	echo "<ol>";
	while($rows = mysql_fetch_array($result)){
		echo "<li><a href='seenews.php?gId=".$rows['gId']."&aId=".$rows['aId']."'>".$rows['aTitle']."</a></li>";
		$i++;
	}
	echo "</ol><br>";	
	echo "搜尋到有 ". $i ." 個相關活動";
	echo "<hr/>";
	
	mysql_free_result($result);


?>