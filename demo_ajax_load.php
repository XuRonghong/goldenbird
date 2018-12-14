<?php include_once('cfg.inc.php'); ?>
<?php include_once('Model/goldenbirdConn1.php'); ?>
<?php
    //最新活動消息
    $row_activityNew = $DB->getActivityWithNew(false, 0, 5);
    $totalRows_activityNew = $DB->getTotalRows();
	foreach($row_activityNew as $rows){
		echo $rows['gName'].
            "<br>&nbsp;&nbsp;&nbsp;&nbsp;發佈了 ".
            "<a href='seenews.php?gId=".$rows['gId']."&aId=".$rows['aId']."'>".
                $rows['aTitle'] .
            "</a> 新活動";
		echo " <br><br>";
	}
?>