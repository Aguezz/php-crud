<?php

require_once './helper/mysqli.php';
require_once './helper/shorthand.php';

$id = $_GET['id'];

if ($_POST) {
    $name = $_POST['name'];
    $courses = $_POST['courses'];

    $mysqli->query("START TRANSACTION");

    $query = "UPDATE `students` SET `name` = ? WHERE id = ? LIMIT 1";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", $name, $id);
    $stmt->execute();
    $stmt->close();

    $query = <<<SQL
        UPDATE `student_has_course`
        SET `daily_assignments_grade` = ?, `midterm_exam_grade` = ?, `final_exam_grade` = ?
        WHERE `student_id` = ? AND `course_id` = ?
    SQL;
    $stmt = $mysqli->prepare($query);

    foreach ($courses as $courseId => $course) {
        $dailyAssignmentsGrade = $course['daily_assignments_grade'];
        $midtermExamGrade = $course['midterm_exam_grade'];
        $finalExamGrade = $course['final_exam_grade'];

        $stmt->bind_param("iiisi", $dailyAssignmentsGrade, $midtermExamGrade, $finalExamGrade, $id, $courseId);
        $stmt->execute();
    }

    $stmt->close();
    $mysqli->query("COMMIT");
}

$student = findStudent($id);
$courses = getStudentCourses($id);
?>

<?php include('header.php'); ?>

<form action="" method="POST">
    <table cellpadding="10">
        <tbody>
            <tr>
                <td>
                    <label for="id">ID</label>
                </td>
                <td colspan="3">
                    <input type="text" name="id" id="id" placeholder="Student ID" value="<?= $student->id ?>" readonly>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="name">Name</label>
                </td>
                <td colspan="3">
                    <input type="text" name="name" id="name" placeholder="Name" value="<?= $student->name ?>">
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

            <?php while ($course = $courses->fetch_object()) : ?>
                <tr>
                    <td>
                        <label for="course-<?= $course->id ?>"><?= $course->name ?></label>
                    </td>
                    <td>
                        <input type="number" name="courses[<?= $course->id ?>][daily_assignments_grade]" placeholder="Daily Assignments Grade" value="<?= $course->daily_assignments_grade ?>">
                    </td>
                    <td>
                        <input type="number" name="courses[<?= $course->id ?>][midterm_exam_grade]" placeholder="Midterm Exam Grade" value="<?= $course->midterm_exam_grade ?>">
                    </td>
                    <td>
                        <input type="number" name="courses[<?= $course->id ?>][final_exam_grade]" placeholder="Final Exam Grade" value="<?= $course->final_exam_grade ?>">
                    </td>
                </tr>
            <?php endwhile ?>
        </tbody>
    </table>

    <button type="submit">
        Update
    </button>
    <a href="index.php">Back</a>
</form>

<?php include('footer.php'); ?>