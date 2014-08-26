<?
include("libs/db.class.php");
$patient = $_REQUEST['patient'];
$db = new DB();
$sql = "SELECT calendar.*, (patient.name || ' ' || patient.lastname) AS name, patient.rut, room.name AS room_name, exam.name AS exam
FROM calendar 
LEFT JOIN patient ON patient.id=calendar.patient 
LEFT JOIN room ON room.id=calendar.room
LEFT JOIN exam ON exam.id=calendar.exam
WHERE calendar.patient=$patient
ORDER BY date_c DESC";
$data = $db->doSql($sql);
if($data) {
    do{
            $id = $data['id'];
            $hour_c = $data['hour_c'];
            $date_c = $data['date_c'];
            $room = $data['room_name'];
            $name = $data['name'];
            $exam = $data['exam'];
            $exam_state = $data['exam_state'];
            $rut = $data['rut'];
            
            $calendar[] = array(
                'id' => $id,
                'hour_c' => $hour_c,
                'date_c' => $date_c,
                'room' => $room,
                'name' => $name,
                'exam' => $exam,
                'state' => $exam_state,
                'informe' => $id,
                'estudio' => $id,
                'rut' => $rut
            );
    }while($data = pg_fetch_assoc($db->actualResults));
    echo json_encode($calendar);
}
?>
