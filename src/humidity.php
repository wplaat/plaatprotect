<?php

/* 
**  ===========
**  PlaatProtect
**  ===========
**
**  Created by wplaat
**
**  For more information visit the following website.
**  Website : www.plaatsoft.nl 
**
**  Or send an email to the following address.
**  Email   : info@plaatsoft.nl
**
**  All copyrights reserved (c) 1996-2019 PlaatSoft
*/

/**
 * @file
 * @brief contain humidity report
 */
 
/*
** ---------------------
** PAGES
** ---------------------
*/

function plaatprotect_humidity_page() {

	// input
	global $pid;
	global $date;
	
	list($year, $month, $day) = explode("-", $date);	
	$day = ltrim($day ,'0');
	$month = ltrim($month ,'0');
	$current_date = mktime(0, 0, 0, $month, $day, $year);  
	
	$step = 60*30;
	$data = "";
	$type = ZIGBEE_TYPE_HUMIDITY;
	
	for ($i=0; $i<((60*60*24/$step)); $i++) {
	
		$timestamp1 = date("Y-m-d H:i:s", $current_date+($step*$i));
		$timestamp2 = date("Y-m-d H:i:s", $current_date+($step*($i+1)));
		
		// break if date is in future
		if ($timestamp1>date("Y-m-d H:i:s")) {
			break;
		}

		$sql1 = 'select zid from zigbee where type='.$type.' order by zid';
		$result1 = plaatprotect_db_query($sql1);
		
		$first=true;
		while ($node = plaatprotect_db_fetch_object($result1)) {
			
			$sql2  = 'select avg(value) as value from sensor where timestamp>="'.$timestamp1.'" and timestamp<"'.$timestamp2.'" and zid='.$node->zid;
			$result2 = plaatprotect_db_query($sql2);
			$row2 = plaatprotect_db_fetch_object($result2);
					
			if ($first==true) {
				if (strlen($data)>0) {
					$data .= ',';
				}		
				$data .= "['".substr($timestamp1,11,5)."'";					
				$first=false;
			} 
			
			$value = "null";
			if (isset($row2->value)) {
				$value = round($row2->value,2);
			} 
			$data .= ",".$value;
		}	
		if ($first==false) {
			$data .= ']';	
		}
	}
	
	if (strlen($data)==0) {
		$data .= '["00:00"';
		
		$sql1 = 'select zid from zigbee where type='.$type.' order by zid';
		$result1 = plaatprotect_db_query($sql1);
		while ($node = plaatprotect_db_fetch_object($result1)) {
			$data .= ',null';
		}
		$data .= ']';
	}	
	
	$json2 = "[".$data."]";

	$page = '
		   <script type="text/javascript" src="https://www.google.com/jsapi"></script>
			<script type="text/javascript">
			google.load("visualization", "1", {packages:["line"]});
			google.setOnLoadCallback(drawChart);

			function drawChart() {

				var data = new google.visualization.DataTable();
				data.addColumn("string", "Time");';
				
				$sql3 = 'select zid from zigbee where type='.$type.' order by zid';
				$result3 = plaatprotect_db_query($sql3);	
				while ($node = plaatprotect_db_fetch_object($result3)) {
					$page .= 'data.addColumn("number", "'.plaatprotect_db_zigbee($node->zid)->location.' [%]");'."\r\n";
				};
	
				$page .= 'data.addRows('.$json2.');

				var options = {
					legend: { position: "top", textStyle: {fontSize: 10} },
					vAxis: {format: "decimal", title: ""},
					hAxis: {title: ""},
					backgroundColor: "transparent",
					chartArea: {
						backgroundColor: "transparent"
					}
				};

				var chart = new google.charts.Line(document.getElementById("chart_div"));
				chart.draw(data, google.charts.Line.convertOptions(options));
		}
		</script>';
	
	$page .= '<h1>'.t('SENSOR_TYPE_5').' '.plaatprotect_dayofweek($date).' '.$day.'-'.$month.'-'.$year.'</h1>';

	$page .= '<div id="chart_div" style="width:950px; height:350px"></div>';
	
	$page .= '<div class="nav">';
	$page .= plaatprotect_link('pid='.$pid.'&date='.plaatprotect_prev_day($date), t('LINK_PREV'));
	$page .= plaatprotect_link('pid='.PAGE_HOME, t('LINK_HOME'));
	$page .= plaatprotect_link('pid='.$pid.'&date='.plaatprotect_next_day($date), t('LINK_NEXT'));
	$page .=  '</div>';

	return $page;
}

/*
** ---------------------
** HANDLER
** ---------------------
*/

function plaatprotect_humidity() {

  /* input */
  global $pid;  
	
	/* Page handler */
	switch ($pid) {

		case PAGE_HUMIDITY:
			return plaatprotect_humidity_page();
			break;
	}
}

/*
** ---------------------
** THE END
** ---------------------
*/

?>
