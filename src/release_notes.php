<?php

/* 
**  ============
**  PlaatProtect
**  ============
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
 * @brief contain release notes
 */
 
/*
** ---------------------
** NOTES
** ---------------------
*/

$note[3] = '<div class="subparagraph">Version 0.3 (25-10-2016)</div>
<div class="large_text">
<ul>
<li>Improve new version detection script.</li>
<li>Improve php cron job. Now no output is created anymore.</li>
<li>Bugfix: Temperature above 25.5c is now correctly measured.</li>
</ul>
</div>';


$note[2] = '<div class="subparagraph">Version 0.2 (27-08-2016)</div>
<div class="large_text">
<ul>
<li>General: Protect application with password and username combination.</li>
<li>General: Web session expire after 15 minutes.</li>
<li>General: Added email notification.</li>
<li>General: Added event process manager which control all zwave and zigbee events.</li>
<li>General: Location can be changed on zwave page now.</li>
<li>General: Zwave device state is now showed correctly.</li>
</ul>
</div>';

$note[1] = '<div class="subparagraph">Version 0.1 (03-07-2016)</div>
<div class="large_text">
<ul>
<li>General: Added notification page.</li>
<li>General: Added logging page.</li>
<li>General: Added movement chart page.</li>
<li>General: Added Z-Wave vibration alarm detection.</li>
<li>General: Added Z-Wave node status on home page.</li>
<li>General: Added Notify My Android (NMA) webservice integration for push messages to andriod phone.</li>
<li>General: Added basic support for two webcams.</li>
<li>General: Added option to make picture from webcam view.</li>
<li>General: Added motion detection with automatic recording to webcam sensor script.</li>
<li>General: Added option to navigate through webcam recordings.</li>
<li>General: Added basic support for Philips HUE lighting system.</li>
</ul>
</div>';

/*
** ---------------------
** PAGES
** ---------------------
*/

function plaatprotect_release_notes_page(){

  global $pid;
  global $id;
  global $note;
  
  $page  = '<h1>Release Notes</h1>';
  $page .= '<br/>';
  
  $page .= $note[$id];
  
  $page .= '<div class="nav">';
  $page .= plaatprotect_link('pid='.$pid.'&eid='.EVENT_PREV.'&id='.$id, t('LINK_PREV'));
  $page .= plaatprotect_link('pid='.PAGE_HOME, t('LINK_HOME'), 'home');
  $page .= plaatprotect_link('pid='.$pid.'&eid='.EVENT_NEXT.'&id='.$id, t('LINK_NEXT'));
  $page .= '</div>';

  return $page;
}

/*
** ---------------------
** HANDLER
** ---------------------
*/

/**
 * Help handler
 */
function plaatprotect_release_notes() {

	/* input */
	global $max;
	global $pid;
	global $eid;
	global $id;
	global $note;

	if($id==0) {
		$id = sizeof($note);
	}
	
	/* Event handler */
	switch ($eid) {
      
		case EVENT_NEXT:
			if ($id<sizeof($note)) {
				$id++;
			}
			break;

		case EVENT_PREV:
			if ($id>1) {
				$id--;
			}
			break;
   }

	/* Page handler */
	switch ($pid) {

		case PAGE_RELEASE_NOTES:
			return plaatprotect_release_notes_page();
			break;
	}
}

/*
** ---------------------
** THE END
** ---------------------
*/

?>
