<?php /* file module.access.php
	
	Module access - an access control module for back office areas


If you're using $_SESSION , make sure you aren't using session_register() too.
From the manual.
If you are using $_SESSION (or $HTTP_SESSION_VARS), do not use session_register(), session_is_registered() and session_unregister().


*/
$FG_DEBUG = 0;
error_reporting(E_ALL & ~E_NOTICE);

// Zone strings
define ("MODULE_ACCESS_DOMAIN",		"C4Chat System");
define ("MODULE_ACCESS_DENIED",		"./Access_denied.htm");


define ("ACX_ACCESS",					1);
define ("ACX_BILLING",					2);			// 1 << 1



header("Expires: Sat, Jan 01 2000 01:01:01 GMT");
//echo "PHP_AUTH_USER : $PHP_AUTH_USER";
session_name("UIADMINSESSION");
session_start();


if (isset($_GET["logout"]) && $_GET["logout"]=="true")
{
	session_destroy();
	$rights=0;
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: index.php");	   
	die();	   
}


function access_sanitize_data($data){
	$lowerdata = strtolower ($data);
	$data = str_replace('--', '', $data);	
	$data = str_replace("'", '', $data);
	$data = str_replace('=', '', $data);
	$data = str_replace(';', '', $data);
	if (!(strpos($lowerdata, ' or ')===FALSE)){ return false;}
	if (!(strpos($lowerdata, 'table')===FALSE)){ return false;}
	
	return $data;
}

if ((!session_is_registered('pr_login') || !session_is_registered('pr_password') || !session_is_registered('rights') || (isset($_POST["done"]) && $_POST["done"]=="submit_log") )){

	if ($FG_DEBUG == 1) echo "<br>0. HERE WE ARE";
	
	if ($_POST["done"]=="submit_log"){
	
		$DBHandle  = DbConnect();
		
		if ($FG_DEBUG == 1) echo "<br>1. ".$_POST["pr_login"].$_POST["pr_password"];
		$_POST["pr_login"] = access_sanitize_data($_POST["pr_login"]);
		$_POST["pr_password"] = access_sanitize_data($_POST["pr_password"]);
		
		$return = login ($_POST["pr_login"], $_POST["pr_password"]);
		
		if ($FG_DEBUG == 1) print_r($return);
		if ($FG_DEBUG == 1) echo "==>".$return[1];
		if (!is_array($return) || $return[1]==0 ) {
			header ("HTTP/1.0 401 Unauthorized");
			Header ("Location: index.php?error=1");	   
			die();
		}	
		// if groupID egal 1, this user is a root
		
		if ($return[3]==0){
			$admin_id = $return[0];
			$return = true;
			$rights = 32767;	
			
			$is_admin = 1;
			$pr_groupID = $return[3];
		}else{				
			$pr_reseller_ID = $return[0];
			$admin_id = $return[0];
			$rights = $return[1];
			if ($return[3]==1) $is_admin=1;
			else $is_admin=0;
			
			if ($return[3] == 3) $pr_reseller_ID = $return[4];
			
			$pr_groupID = $return[3];			
		}
				
		if ($_POST["pr_login"]) {
			
			$pr_login = $_POST["pr_login"];
			$pr_password = $_POST["pr_password"];
			
			if ($FG_DEBUG == 1) echo "<br>3. $pr_login-$pr_password-$rights-$conf_addcust";			
			$_SESSION["pr_login"]=$pr_login;
			$_SESSION["pr_password"]=$pr_password;
			$_SESSION["rights"]=$rights;
			$_SESSION["is_admin"]=$is_admin;	
			$_SESSION["pr_reseller_ID"]=$pr_reseller_ID;
			$_SESSION["pr_groupID"]=$pr_groupID;
			$_SESSION["admin_id"] = $admin_id;
			$log = new Logger();			
			$log -> insertLog($admin_id, 1, "User Logged In", "User Logged in to website", '', $_SERVER['REMOTE_ADDR'], 'PP_Intro.php','');
			$log = null;
		}	
		
	} else {
		$rights=0;	
		
	}	
	
}


// 					FUNCTIONS
//////////////////////////////////////////////////////////////////////////////

function login ($user, $pass) { 
	global $DBHandle;
	
	$user = trim($user);
	$pass = trim($pass);
	if (strlen($user)==0 || strlen($user)>=50 || strlen($pass)==0 || strlen($pass)>=50) return false;
	$QUERY = "SELECT userid, perms, confaddcust, groupid FROM cc_ui_authen WHERE login = '".$user."' AND password = '".$pass."'";

	$res = $DBHandle -> Execute($QUERY);

	if (!$res) {
		$errstr = $DBHandle->ErrorMsg();
		return (false);
	}

	$row [] =$res -> fetchRow();
	return ($row[0]);
}


function has_rights ($condition) {	
	return ($_SESSION["rights"] & $condition);
}


$ACX_ACCESS 	= has_rights (ACX_ACCESS);
$ACXBILLING 	= has_rights (ACX_BILLING);
