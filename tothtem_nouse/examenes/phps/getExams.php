<?
include ('../scripts/libs/db.class.php');

$calendar = $_REQUEST['calendar'];
$db = new DB();
$sql="SELECT history_calendar.id, exam.name as exam, report_history.date, (employee.name || ' ' || employee.lastname) AS doctor 
FROM history_calendar 
LEFT JOIN report_history ON report_history.id=history_calendar.history
LEFT JOIN calendar_exam ON calendar_exam.id=history_calendar.calendar_exam
LEFT JOIN exam ON exam.id=calendar_exam.exam
LEFT JOIN users ON users.id=report_history.users
LEFT JOIN employee ON employee.id=users.employee
WHERE calendar_exam.id IN (SELECT id FROM calendar_exam WHERE calendar=$calendar)";
$data = $db->doSql($sql);

if($data){
    do{
        $id = $data['id'];
        $date = $data['date'];
        $exam = $data['exam'];
        $doctor=$data['doctor'];

        $exams[] = array(
            'id' => $id,
            'date' => $date,
            'exam' => $exam,
            'doctor' => $doctor
        );
    }while($data = pg_fetch_assoc($db->actualResults));
    echo json_encode($exams);
}
?>
