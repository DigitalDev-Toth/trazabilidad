<?
function makeControls($obj, $insertLink, $deleteLink, $updateLink, $backLink)
{
	$obj->control("refresh");
	$obj->control("separator");
	$obj->control("selectAll");
	$obj->control("selectNone");
	$obj->control("selectInvert");
	$obj->control("separator");
	if($insertLink) $obj->control("insert", $insertLink);
	if($deleteLink)$obj->control("delete", $deleteLink);
	if($updateLink)$obj->control("update", $updateLink);

    if(!$_GET['modulo'])
    {
        $module = $_GET['content']; 
    }else
        $module = $_GET['modulo'];

    if($module=="users_roles")
    {
        $url = 'main.php?modulo=roles&roles='.$_GET['roles'].'&pwd='.$module;
        $obj->control("insertMany", "$url&selectMany=".str_replace("&", "*", $_SERVER['REQUEST_URI'])); 
        $obj->control("separator");
    }
    if($module=="users_modules")
    {
        $url = 'main.php?modulo=modules&modules='.$_GET['module'].'&pwd='.$module;
        $obj->control("insertMany", "$url&selectMany=".str_replace("&", "*", $_SERVER['REQUEST_URI'])); 
        $obj->control("separator");
    }
}
?>
