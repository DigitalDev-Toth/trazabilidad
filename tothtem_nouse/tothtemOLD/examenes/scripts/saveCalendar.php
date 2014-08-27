<?
include("libs/db.class.php");
$hour = $_REQUEST['hour'];
$patient = $_REQUEST['patient'];
$room = $_REQUEST['room'];
$exam = $_REQUEST['exam'];
$date = $_REQUEST['date'];
$c = new DB();
$userData = $c->doSql("SELECT users FROM schedule WHERE date_s='$date' AND room=$room LIMIT 1");
$users = $userData['users'];
$c->doSql("INSERT INTO calendar (hour_c, date_c, patient, room, exam, exam_state, users, priority) VALUES ('$hour', '$date', $patient, $room, $exam, 'agendado', $users, 6)");
//echo "INSERT INTO calendar (hour_c, date_c, patient, room, exam, exam_state, users) VALUES ('$hour', '$date', $patient, $room, $exam, 'agendado', $users)";

?>
