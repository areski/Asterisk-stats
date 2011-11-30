<?php
/***************************************************************************
 *            Misc.php
 *
 ****************************************************************************/


/*
 *	Function to send an email as an alarm when something goes wrong and we need to notify the admin
 *
 *  @param object 	$agi
 *  @param string 	$subject
 *  @param string 	$messagetext
 *  @return void 
 */
function send_alarm ($subject, $messagetext){
	include_once (COMMON_LIBRARY. '/phpmailer/class.phpmailer.php');
	
	$subject = 'C4Chat ALARM : '.$subject;
	if (strlen($messagetext)==0) $messagetext=$subject;
	
	$mail = new phpmailer();
	$mail -> From     = EMAIL_ADMIN;
	$mail -> FromName = EMAIL_ADMIN;
	//$mail -> IsSendmail();
	$mail -> IsSMTP();
	$mail -> Subject  = $subject;
	$mail -> Body    = $messagetext; 
	$mail->AddAddress('areski@commercetel.com');
	$mail->AddAddress('areski@gmail.com');
	
	$mail->Send();
}


/* 
 * Write log into file 
 */
function write_log($logfile, $output){
	if (strlen($logfile) > 1){
		$string_log = "[".date("d/m/Y H:i:s")."]:[$output]\n";
		error_log ($string_log."\n", 3, $logfile);
	}
}


/*
 * function sanitize_data
 */
function sanitize_data($data){
	$lowerdata = strtolower ($data);
	//echo "----> $data ";
	$data = str_replace('--', '', $data);	
	$data = str_replace("'", '', $data);
	$data = str_replace('=', '', $data);
	$data = str_replace(';', '', $data);
	//$lowerdata = str_replace('table', '', $lowerdata);
	//$lowerdata = str_replace(' or ', '', $data);
	if (!(strpos($lowerdata, ' or ')===FALSE)){ return false;}
	if (!(strpos($lowerdata, 'table')===FALSE)){ return false;}
	//echo "----> $data<br>";
	return $data;
}


/*
 * function getpost_ifset
 */
function getpost_ifset($test_vars)
{
	if (!is_array($test_vars)) {
		$test_vars = array($test_vars);
	}
	foreach($test_vars as $test_var) { 
		if (isset($_POST[$test_var])) { 
			global $$test_var;
			$$test_var = $_POST[$test_var];
			$$test_var = sanitize_data($$test_var);
		} elseif (isset($_GET[$test_var])) {
			global $$test_var; 
			$$test_var = $_GET[$test_var];
			$$test_var = sanitize_data($$test_var);
		}
	}
} 


/*
 * function display_money
 */
function display_money($value, $currency = BASE_CURRENCY){			
	echo $value.' '.$currency;			
}


/*
 * function display_dateformat
 */
function display_dateformat($mydate){
	if (DB_TYPE == "mysql"){			
		if (strlen($mydate)==14){
			// YYYY-MM-DD HH:MM:SS 20300331225242
			echo substr($mydate,0,4).'-'.substr($mydate,4,2).'-'.substr($mydate,6,2);
			echo ' '.substr($mydate,8,2).':'.substr($mydate,10,2).':'.substr($mydate,12,2);				
			return;
		}
	}	
	echo $mydate;			
}

/*
 * function display_dateonly
 */
function display_dateonly($mydate)
{
	if (strlen($mydate) > 0 && $mydate != '0000-00-00'){
		echo date("m/d/Y", strtotime($mydate));
	}
}

/*
 * function res_display_dateformat
 */
function res_display_dateformat($mydate){
	if (DB_TYPE == "mysql"){
		if (strlen($mydate)==14){
			// YYYY-MM-DD HH:MM:SS 20300331225242
			$res= substr($mydate,0,4).'-'.substr($mydate,4,2).'-'.substr($mydate,6,2);
			$res.= ' '.substr($mydate,8,2).':'.substr($mydate,10,2).':'.substr($mydate,12,2);				
			return $res;
		}
	}
	return $mydate;			
}

/*
 * function display_minute
 */
function display_minute($sessiontime){
	global $resulttype;
	if ((!isset($resulttype)) || ($resulttype=="min")){  
			$minutes = sprintf("%02d",intval($sessiontime/60)).":".sprintf("%02d",intval($sessiontime%60));
	}else{
			$minutes = $sessiontime;
	}
	echo $minutes;
}

function display_2dec($var){		
	echo number_format($var,2);
}

function display_2dec_percentage($var){	
	if (isset($var))
	{	
		echo number_format($var,2)."%";
	}else
	{
		echo "n/a";
	}
}

function display_2bill($var, $currency = BASE_CURRENCY){	
	global $currencies_list, $choose_currency;
	if (isset($choose_currency) && strlen($choose_currency)==3) $currency=$choose_currency;
	if ( (!isset($currencies_list)) || (!is_array($currencies_list)) ) $currencies_list = get_currencies();
	$var = $var / $currencies_list[strtoupper($currency)][2];
	echo number_format($var,3).' '.$currency;
}

function remove_prefix($phonenumber){
	
	if (substr($phonenumber,0,3) == "011"){
		echo substr($phonenumber,3);
		return 1;
	}
	echo $phonenumber;
}



/*
 * function MDP_STRING
 */
function MDP_STRING($chrs = LEN_CARDNUMBER){
	$pwd = ""  ;
	mt_srand ((double) microtime() * 1000000);
	while (strlen($pwd)<$chrs)
	{
		$chr = chr(mt_rand (0,255));
		if (eregi("^[0-9a-z]$", $chr))
		$pwd = $pwd.$chr;
	};
	return strtolower($pwd);
}

function MDP_NUMERIC($chrs = LEN_CARDNUMBER){
	$pwd = ""  ;
	mt_srand ((double) microtime() * 1000000);
	while (strlen($pwd)<$chrs)
	{
		$chr = mt_rand (0,9);
		if (eregi("^[0-9]$", $chr))
		$pwd = $pwd.$chr;
	};
	return strtolower($pwd);
}


function MDP($chrs = LEN_CARDNUMBER){
	$pwd = ""  ;
	mt_srand ((double) microtime() * 1000000);
	while (strlen($pwd)<$chrs)
	{
		$chr = chr(mt_rand (0,255));
		if (eregi("^[0-9]$", $chr))
		$pwd = $pwd.$chr;
	};
	return $pwd;
}




// *********************************
//  ONLY USER BY THE OLD FRAME WORK 
// *********************************

$lang['strfirst']='&lt;&lt; First';
$lang['strprev']='&lt; Prev';
$lang['strnext']='Next &gt;';
$lang['strlast']='Last &gt;&gt;';

/**
* Do multi-page navigation.  Displays the prev, next and page options.
* @param $page the page currently viewed
* @param $pages the maximum number of pages
* @param $url the url to refer to with the page number inserted
* @param $max_width the number of pages to make available at any one time (default = 20)
*/
function printPages($page, $pages, $url, $max_width = 20) {
	global $lang;
	
	$window = 8;
	
	if ($page < 0 || $page > $pages) return;
	if ($pages < 0) return;
	if ($max_width <= 0) return;
	
	if ($pages > 1) {
		//echo "<center><p>\n";
		if ($page != 1) {
			$temp = str_replace('%s', 1-1, $url);
			echo "<a class=\"pagenav\" href=\"{$temp}\">{$lang['strfirst']}</a>\n";
			$temp = str_replace('%s', $page - 1-1, $url);
			echo "<a class=\"pagenav\" href=\"{$temp}\">{$lang['strprev']}</a>\n";
		}
	
		if ($page <= $window) {
			$min_page = 1;
			$max_page = min(2 * $window, $pages);
		}
		elseif ($page > $window && $pages >= $page + $window) {
			$min_page = ($page - $window) + 1;
			$max_page = $page + $window;
		}
		else {
			$min_page = ($page - (2 * $window - ($pages - $page))) + 1;
			$max_page = $pages;
		}

		// Make sure min_page is always at least 1
		// and max_page is never greater than $pages
		$min_page = max($min_page, 1);
		$max_page = min($max_page, $pages);
		
		for ($i = $min_page; $i <= $max_page; $i++) {
			$temp = str_replace('%s', $i-1, $url);
			if ($i != $page) echo "<a class=\"pagenav\" href=\"{$temp}\">$i</a>\n";
			else echo "$i\n";
		}
		if ($page != $pages) {
			$temp = str_replace('%s', $page + 1-1, $url);
			echo "<a class=\"pagenav\" href=\"{$temp}\">{$lang['strnext']}</a>\n";
			$temp = str_replace('%s', $pages-1, $url);
			echo "<a class=\"pagenav\" href=\"{$temp}\">{$lang['strlast']}</a>\n";
		}
	}
}

/**
* Validate the Uploaded Files.  Return the error string if any.
* @param $the_file the file to validate
* @param $the_file_type the file type
*/
function validate_upload($the_file, $the_file_type) {

	$registered_types = array(
						"application/x-gzip-compressed"         => ".tar.gz, .tgz",
						"application/x-zip-compressed"          => ".zip",
						"application/x-tar"                     => ".tar",
						"text/plain"                            => ".html, .php, .txt, .inc (etc)",
						"image/bmp"                             => ".bmp, .ico",
						"image/gif"                             => ".gif",
						"image/pjpeg"                           => ".jpg, .jpeg",
						"image/jpeg"                            => ".jpg, .jpeg",
						"image/png"                             => ".png",
						"application/x-shockwave-flash"         => ".swf",
						"application/msword"                    => ".doc",
						"application/vnd.ms-excel"              => ".xls",
						"application/octet-stream"              => ".exe, .fla (etc)",
						"text/x-comma-separated-values"			=> ".csv",
						"text/csv"								=> ".csv"
						); # these are only a few examples, you can find many more!

	$allowed_types = array("text/plain", "text/x-comma-separated-values", "text/csv", "application/vnd.ms-excel");


	$start_error = "\n<b>ERROR:</b>\n<ul>";
	$error = "";
	if ($the_file=="")
	{		
		$error .= "\n<li>".gettext("File size is greater than allowed limit.")."\n<ul>";
	}else
	{
        if ($the_file == "none") { 
                $error .= "\n<li>".gettext("You did not upload anything!")."</li>";
        }
        elseif ($_FILES['the_file']['size'] == 0)
        {
        	$error .= "\n<li>".gettext("Failed to upload the file, The file you uploaded may not exist on disk.")."!</li>";
        } 
        else        
        {
 			if (!in_array($the_file_type,$allowed_types))
 			{
 				$error .= "\n<li>".gettext("file type is not allowed").': '.$the_file_type."\n<ul>";
                while ($type = current($allowed_types))
                {
                    $error .= "\n<li>" . $registered_types[$type] . " (" . $type . ")</li>";
                	next($allowed_types);
                }
                $error .= "\n</ul>";
            }                
        }
	}
	if ($error)
	{
		$error = $start_error . $error . "\n</ul>";
        return $error;
    }
    else 
    {
    	return false;
    }

} # END validate_upload
