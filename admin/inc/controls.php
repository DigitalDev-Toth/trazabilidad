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
}
?>
