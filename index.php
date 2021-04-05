<?php

require_once './helper/mysqli.php';
require_once './helper/shorthand.php';
require_once './helper/utils.php';

$students = getStudentsWithAverage();
?>

<?php include('components/header.php'); ?>

<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Daily Assignments Grade Average</th>
            <th>Midtern Exam Grade Average</th>
            <th>Final Exam Grade Average</th>
            <th>Grade</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php while ($student = $students->fetch_object()) : ?>
            <tr>
                <td><?= $student->id ?></td>
                <td><?= $student->name ?></td>
                <td><?= $student->daily_assignments_grade ?></td>
                <td><?= $student->midterm_exam_grade ?></td>
                <td><?= $student->final_exam_grade ?></td>
                <td>
                    <?=
                    convertGrade(
                        ($student->daily_assignments_grade * 30 / 100) +
                            ($student->midterm_exam_grade * 30 / 100) +
                            ($student->final_exam_grade * 40 / 100)
                    )
                    ?>
                </td>
                <td>
                    <a href="detail.php?id=<?= $student->id ?>">More Information</a>
                    <a href="edit.php?id=<?= $student->id ?>">Edit</a>
                    <a href="delete.php?id=<?= $student->id ?>" onclick="return confirm('Are you sure want to delete this data?')">Delete</a>
                </td>
            </tr>
        <?php endwhile ?>
    </tbody>
</table>

<?php include('components/footer.php'); ?>