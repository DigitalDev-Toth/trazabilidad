<?php

include ('../libs/db.class.php');
$module = $_REQUEST['module'];
$db = NEW DB();
$sql = "SELECT zone.name AS z from zone left join module on module.zone =zone.id left join submodule on submodule.module=module.id where subModule.id=$module";
$zone = $db->doSql($sql);
echo $zone['z'];

?>

