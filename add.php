<?php

require_once './helper/mysqli.php';
require_once './helper/shorthand.php';

if ($_POST) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $courses = $_POST['courses'];

    $mysqli->query("START TRANSACTION");

    // Insert student first
    $query = "INSERT INTO `students` (`id`, `name`) VALUES (?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", $id, $name);
    $stmt->execute();
    $stmt->close();

    // Insert course grade of student
    $query = "INSERT INTO `student_has_course` (`student_id`, `course_id`, `daily_assignments_grade`, `midterm_exam_grade`, `final_exam_grade`) VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);

    foreach ($courses as $courseId => $course) {
        $dailyAssignmentsGrade = $course['daily_assignments_grade'];
        $midtermExamGrade = $course['midterm_exam_grade'];
        $finalExamGrade = $course['final_exam_grade'];

        $stmt->bind_param("siiii", $id, $courseId, $dailyAssignmentsGrade, $midtermExamGrade, $finalExamGrade);
        $stmt->execute();
    }

    $stmt->close();
    $mysqli->query("COMMIT");
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

            <?php while ($course = $courses->fetch_object()) : ?>
                <tr>
                    <td>
                        <label><?= $course->name ?></label>
                    </td>
                    <td>
                        <input type="number" name="courses[<?= $course->id ?>][daily_assignments_grade]" placeholder="Daily Assignments Grade">
                    </td>
                    <td>
                        <input type="number" name="courses[<?= $course->id ?>][midterm_exam_grade]" placeholder="Midterm Exam Grade">
                    </td>
                    <td>
                        <input type="number" name="courses[<?= $course->id ?>][final_exam_grade]" placeholder="Final Exam Grade">
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