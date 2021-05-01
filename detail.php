<?php

require_once './helper/mysqli.php';
require_once './helper/shorthand.php';
require_once './helper/utils.php';

$id = $_GET['id'];

$student = findStudent($id);
$courses = getCoursesGrade($id);
$studentAverage = findStudentWithAverage($id);
?>

<?php include('components/header.php'); ?>

<table border="1" cellpadding="10" cellspacing="0">
    <tbody>
        <tr>
            <td>ID</td>
            <td colspan="3"><?= htmlspecialchars($student->id) ?></td>
        </tr>
        <tr>
            <td>Name</td>
            <td colspan="3"><?= htmlspecialchars($student->name) ?></td>
        </tr>
        <tr>
            <th></th>
            <th>Daily Assignments Grade Average</th>
            <th>Midtern Exam Grade Average</th>
            <th>Final Exam Grade Average</th>
        </tr>
        <?php foreach ($courses as $course) : ?>
            <tr>
                <td><?= $course->name ?></td>
                <td><?= $course->daily_assignments_grade ?></td>
                <td><?= $course->midterm_exam_grade ?></td>
                <td><?= $course->final_exam_grade ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
    <tfoot>
        <tr>
            <td><strong>Average</strong></td>
            <td><strong><?= $studentAverage->daily_assignments_grade ?></strong></td>
            <td><strong><?= $studentAverage->midterm_exam_grade ?></strong></td>
            <td><strong><?= $studentAverage->final_exam_grade ?></strong></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: right;"><strong>Grade</strong></td>
            <td>
                <strong>
                    <?=
                    convertGrade(
                        ($studentAverage->daily_assignments_grade * 30 / 100) +
                            ($studentAverage->midterm_exam_grade * 30 / 100) +
                            ($studentAverage->final_exam_grade * 40 / 100)
                    )
                    ?>
                </strong>
            </td>
        </tr>
    </tfoot>
</table>

<?php include('components/footer.php'); ?>