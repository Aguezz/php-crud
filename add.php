<?php

require_once './helper/mysqli.php';
require_once './helper/shorthand.php';
require_once './helper/utils.php';

if ($_POST) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $courses = $_POST['courses'];

    $db->StartTrans();

    // Insert student first
    $handle = $db->Prepare("INSERT INTO `students` (`id`, `name`) VALUES (?, ?)");
    $bindVariables = [0 => $id, 1 => $name];
    $db->Execute($handle, $bindVariables);

    // Insert course grade of student
    $handle = $db->Prepare("INSERT INTO `student_has_course` (`student_id`, `course_id`, `daily_assignments_grade`, `midterm_exam_grade`, `final_exam_grade`) VALUES (?, ?, ?, ?, ?)");

    foreach ($courses as $courseId => $course) {
        $dailyAssignmentsGrade = sanitizeNumber($course['daily_assignments_grade']);
        $midtermExamGrade = sanitizeNumber($course['midterm_exam_grade']);
        $finalExamGrade = sanitizeNumber($course['final_exam_grade']);

        $bindVariables = [
            0 => $id,
            1 => $courseId,
            2 => $dailyAssignmentsGrade,
            3 => $midtermExamGrade,
            4 => $finalExamGrade
        ];
        $db->Execute($handle, $bindVariables);
    }

    $db->Execute("COMMIT");

    header('Location: index.php');
}

$courses = getCourses();
?>

<?php include('components/header.php'); ?>

<form action="" method="POST">
    <table cellpadding="10">
        <tbody>
            <tr>
                <td>
                    <label for="id">ID</label>
                </td>
                <td colspan="3">
                    <input type="text" name="id" id="id" placeholder="Student ID" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="name">Name</label>
                </td>
                <td colspan="3">
                    <input type="text" name="name" id="name" placeholder="Name">
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

            <?php while (!$courses->EOF) : ?>
                <?php $course = $courses->FetchNextObj() ?>
                <tr>
                    <td>
                        <label><?= $course->name ?></label>
                    </td>
                    <td>
                        <input type="number" min="0" max="100" name="courses[<?= $course->id ?>][daily_assignments_grade]" placeholder="Daily Assignments Grade">
                    </td>
                    <td>
                        <input type="number" min="0" max="100" name="courses[<?= $course->id ?>][midterm_exam_grade]" placeholder="Midterm Exam Grade">
                    </td>
                    <td>
                        <input type="number" min="0" max="100" name="courses[<?= $course->id ?>][final_exam_grade]" placeholder="Final Exam Grade">
                    </td>
                </tr>
            <?php endwhile ?>
        </tbody>
    </table>

    <button type="submit">
        Save
    </button>
    <a href="index.php">Back</a>
</form>

<?php include('components/footer.php'); ?>