<?php
include("libs/db.class.php");
$room = new DB("room", "id", "sm");
$db = new DB("calendar", "id", "sm");
$myRoom = $room->doSql("SELECT room.id, room.name, modality.name AS mod, modality.id AS mod_id FROM room LEFT JOIN modality ON modality.id=room.modality WHERE web='Si'");
$i=0;
do{
        $modality = $myRoom['mod'];
        $idMod = $myRoom['mod_id'];
        if($modality == 'Radiografias') $sql = "SELECT exam.duration, exam.id FROM exam_mod LEFT JOIN exam ON exam.id=exam_mod.exam WHERE exam_mod.modality=$idMod AND exam.code='web_cr'";
        elseif($modality == 'Mamografia') $sql = "SELECT exam.duration, exam.id FROM exam_mod LEFT JOIN exam ON exam.id=exam_mod.exam WHERE exam_mod.modality=$idMod AND exam.code='web_mg'";
        elseif($modality == 'Resonancia') $sql = "SELECT exam.duration, exam.id FROM exam_mod LEFT JOIN exam ON exam.id=exam_mod.exam WHERE exam_mod.modality=$idMod AND exam.code='web_mr'";
        elseif($modality == 'Scanner') $sql = "SELECT exam.duration, exam.id FROM exam_mod LEFT JOIN exam ON exam.id=exam_mod.exam WHERE exam_mod.modality=$idMod AND exam.code='web_ct'";
        elseif($modality == 'Ecotomografia ') $sql = "SELECT exam.duration, exam.id FROM exam_mod LEFT JOIN exam ON exam.id=exam_mod.exam WHERE exam_mod.modality=$id_mod AND exam.code='web_us'";
        $data = $db->doSql($sql);
        $duration = $data['duration'];
        $idExam = $data['id'];
        $row[$i]['id'] = $myRoom['id']."-$idExam($duration";
        $row[$i]['name'] = $myRoom['name'];
        $i++;			
}while($myRoom = pg_fetch_assoc($room->actualResults));
echo json_encode($row);  
?> 

