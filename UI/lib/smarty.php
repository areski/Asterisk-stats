<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

define( 'FULL_PATH', dirname(__FILE__) . '/' );
// define( 'SMARTY_DIR', FULL_PATH . '/Smarty/' );
define( 'SMARTY_DIR', LIBDIR . '/Smarty-2.6.18/libs/' );

define( 'TEMPLATE_DIR',  '../Public/templates/' );
define( 'TEMPLATE_C_DIR', '../templates_c/' );


require_once LIBDIR . '/Smarty-2.6.18/libs/Smarty.class.php';


$smarty = new Smarty;


$skin_name = $_SESSION["stylefile"];


//$smarty->template_dir = TEMPLATE_DIR . $skin_name.'/';
$smarty->template_dir = TEMPLATE_DIR . $skin_name.'/';

$smarty->compile_dir = TEMPLATE_C_DIR;
$smarty->plugins_dir= "./plugins/";

$smarty->assign("TEXTCONTACT", TEXTCONTACT);
$smarty->assign("EMAILCONTACT", EMAILCONTACT);
$smarty->assign("COPYRIGHT", COPYRIGHT);
$smarty->assign("CCMAINTITLE", CCMAINTITLE);
$smarty->assign("WEBUI_VERSION", WEBUI_VERSION);
$smarty->assign("WEBUI_DATE", WEBUI_DATE);

$smarty->assign("SKIN_NAME", $skin_name);
// if it is a pop window
if (!is_numeric($popup_select))
{
	$popup_select=0;
}
$smarty->assign("popupwindow", $popup_select);
// for menu

$smarty->assign("ACX_ACCESS", $ACX_ACCESS);

if($_GET["section"]!="")
{
	$section = $_GET["section"];
	$_SESSION["menu_section"] = $section;
}
else
{
	$section = $_SESSION["menu_section"];
}
$smarty->assign("section", $section);
$smarty->assign("adminname", $_SESSION["pr_login"]);

// OPTION FOR THE MENU
$smarty->assign("A2Bconfig", $A2B->config);

$smarty->assign("PAGE_SELF", $PHP_SELF);

?>
