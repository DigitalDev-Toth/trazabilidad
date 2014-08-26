<?
include("libs/db.class.php");
$patient = $_REQUEST['patient'];
$db = new DB();
 /*$sql = "SELECT calendar.*, (patient.name || ' ' || patient.lastname) AS name, patient.rut, room.name AS room_name, exam.name AS exam
 FROM calendar 
 LEFT JOIN patient ON patient.id=calendar.patient 
 LEFT JOIN room ON room.id=calendar.room
 LEFT JOIN exam ON exam.id=calendar.exam
 WHERE calendar.patient=$patient
 ORDER BY date_c DESC";*/

$sql="SELECT calendar.id , calendar.date_c , calendar.hour_c,calendar.exam_state,calendar.patient,calendar.room, (patient.name || ' ' || patient.lastname) AS name, 
patient.rut, room.name AS room_name, calendar_exam.exam as exam_code, exam.name as exam_name
FROM calendar 
LEFT JOIN patient ON patient.id=calendar.patient 
LEFT JOIN room ON room.id=calendar.room
LEFT JOIN calendar_exam on calendar.id=calendar_exam.calendar
LEFT JOIN exam ON exam.id=calendar_exam.exam
WHERE calendar.patient=$patient
ORDER BY date_c DESC";
$data = $db->doSql($sql);
$examString="";
$uniqueId="";
$i=0;

if($data) {
    do{

        $id = $data['id'];
        $hour_c = $data['hour_c'];
        $date_c = $data['date_c'];
        $room = $data['room_name'];
        $name = $data['name'];
        $exam_state = $data['exam_state'];
        $rut = $data['rut'];
        $exam_code=$data['exam_code'];
        $exam_name=$data['exam_name'];
        if($uniqueId==$data['id']){
            $i--;
            $examString=$examString." ; ".$exam_name;
            $calendar[$i] = array(
            'id' => $id,
            'hour_c' => $hour_c,
            'date_c' => $date_c,
            'room' => $room,
            'name' => $name,
            'state' => $exam_state,
            'informe' => $id,
            'estudio' => $id,
            'rut' => $rut,
            'exam_name' => $examString
            );
        }else{
            $examString=$exam_name;
            $calendar[$i] = array(
            'id' => $id,
            'hour_c' => $hour_c,
            'date_c' => $date_c,
            'room' => $room,
            'name' => $name,
            'state' => $exam_state,
            'informe' => $id,
            'estudio' => $id,
            'rut' => $rut,
            'exam_name' =>$exam_name
            );
        }
        $uniqueId=$data['id'];
        $i++;
    }while($data = pg_fetch_assoc($db->actualResults));
    echo json_encode($calendar);
}
?>
