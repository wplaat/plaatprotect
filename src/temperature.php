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
**  All copyrights reserved (c) 2008-2016 PlaatSoft
*/

/**
 * @file
 * @brief contain temperature report
 */
 
/*
** ---------------------
** PAGES
** ---------------------
*/

function plaatprotect_temperature_page() {

	// input
	global $pid;
	global $date;
	
	list($year, $month, $day) = explode("-", $date);	
	$day = ltrim($day ,'0');
	$month = ltrim($month ,'0');
	$current_date = mktime(0, 0, 0, $month, $day, $year);  
	
	$step = 300;
		
	$data="";
	
	for ($i=0; $i<288; $i++) {
	
		$timestamp1 = date("Y-m-d H:i:s", $current_date+($step*$i));
		$timestamp2 = date("Y-m-d H:i:s", $current_date+($step*($i+1)));
				
		$sql1 = 'select zid  from sensor group by zid';
		$result1 = plaatprotect_db_query($sql1);
		
		$first=true;
		$found=false;
		while ($node = plaatprotect_db_fetch_object($result1)) {
			
			$sql2  = 'select timestamp, zid, temperature from sensor where ';
			$sql2 .= 'timestamp>="'.$timestamp1.'" and timestamp<="'.$timestamp2.'" and zid='.$node->zid.' order by timestamp';
								
			$result2 = plaatprotect_db_query($sql2);
				
			while ($row = plaatprotect_db_fetch_object($result2)) {
					
				$found=true;
					
				$value = 0;
				if (isset($row->zid)) {
					$value = $row->temperature;
				} 
				
				if ($first==true) {
					if (strlen($data)>0) {
						$data .= ',';
					}					
					$data .= "['".substr($timestamp1,11,5)."'";					
					$first=false;
				} 
				$data .= ", ".round($value,2);
			}		
		}
		if ($found==false) {
			if (strlen($data)>0) {
				$data .= ',';
			}	
			$data .= "['".substr($timestamp1,11,5)."',0,0";
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
				
				$sql3 = 'select zid from sensor group by zid';
				$result3 = plaatprotect_db_query($sql3);	
				while ($node = plaatprotect_db_fetch_object($result3)) {
					$page .= 'data.addColumn("number", "'.$node->zid.'");'."\r\n";
				};
							
				$sql1 = 'select zid, type from zwave where type="Sensor" order by zid';
				$result1 = plaatprotect_db_query($sql1);
				while ($node = plaatprotect_db_fetch_object($result1)) {
				
					$page .= 'data.addColumn("number",  "'.plaatprotect_db_zwave($node->zid)->location.'"); ';
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
	
	$page .= '<h1>Temperature '.plaatprotect_dayofweek($date).' '.$day.'-'.$month.'-'.$year.'</h1>';

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

function plaatprotect_temperature() {

  /* input */
  global $pid;  
	
	/* Page handler */
	switch ($pid) {

		case PAGE_TEMPERATURE:
			return plaatprotect_temperature_page();
			break;
	}
}

/*
** ---------------------
** THE END
** ---------------------
*/

?>