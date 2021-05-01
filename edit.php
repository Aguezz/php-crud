<?php

require_once './helper/mysqli.php';
require_once './helper/shorthand.php';
require_once './helper/utils.php';

$id = $_GET['id'];

if ($_POST) {
    $newId = $_POST['id'];
    $name = $_POST['name'];
    $courses = $_POST['courses'];

    $db->StartTrans();

    // Insert student first
    $handle = $db->Prepare("UPDATE `students` SET `id` = ?, `name` = ? WHERE id = ? LIMIT 1");
    $bindVariables = [0 => $newId, 1 => $name, 2 => $id];
    $db->Execute($handle, $bindVariables);

    $handle = $db->Prepare(
        <<<SQL
            UPDATE `student_has_course`
            SET `daily_assignments_grade` = ?, `midterm_exam_grade` = ?, `final_exam_grade` = ?
            WHERE `student_id` = ? AND `course_id` = ?
        SQL
    );

    var_dump($courses);
    die();

    foreach ($courses as $courseId => $course) {
        $dailyAssignmentsGrade = sanitizeNumber($course['daily_assignments_grade']);
        $midtermExamGrade = sanitizeNumber($course['midterm_exam_grade']);
        $finalExamGrade = sanitizeNumber($course['final_exam_grade']);

        $bindVariables = [
            0 => $dailyAssignmentsGrade,
            1 => $midtermExamGrade,
            2 => $finalExamGrade,
            3 => $id,
            4 => $courseId,
        ];
        $db->Execute($handle, $bindVariables);
    }
    $db->Execute("COMMIT");

    header('Location: index.php');
}

$student = findStudent($id);
$courses = getStudentCourses($id);
?>

<?php include('./components/header.php'); ?>

<form action="" method="POST">
    <table cellpadding="10">
        <tbody>
            <tr>
                <td>
                    <label for="id">ID</label>
                </td>
                <td colspan="3">
                    <input type="text" name="id" id="id" placeholder="Student ID" value="<?= htmlspecialchars($student->id) ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="name">Name</label>
                </td>
                <td colspan="3">
                    <input type="text" name="name" id="name" placeholder="Name" value="<?= htmlspecialchars($student->name) ?>">
                </td>
            </tr>

            <tr>
                <td colspan="4"><br></td>
            </tr>

            <tr>
                <td><strong>Grade</strong></td>
                <td><strong>Daily Assignments Grade</strong></td>
                <td><strong>Midterm Exam Grade</strong></td>
                <td><strong>Final Exam Grade</strong></td>
            </tr>

            <?php foreach ($courses as $course) : ?>
                <tr>
                    <td>
                        <label for="course-<?= $course->id ?>"><?= $course->name ?></label>
                    </td>
                    <td>
                        <input type="number" min="0" max="100" name="courses[<?= $course->id ?>][daily_assignments_grade]" placeholder="Daily Assignments Grade" value="<?= $course->daily_assignments_grade ?>">
                    </td>
                    <td>
                        <input type="number" min="0" max="100" name="courses[<?= $course->id ?>][midterm_exam_grade]" placeholder="Midterm Exam Grade" value="<?= $course->midterm_exam_grade ?>">
                    </td>
                    <td>
                        <input type="number" min="0" max="100" name="courses[<?= $course->id ?>][final_exam_grade]" placeholder="Final Exam Grade" value="<?= $course->final_exam_grade ?>">
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <button type="submit">Update</button>
    <a href="index.php">Back</a>
</form>

<?php include('./components/footer.php'); ?>