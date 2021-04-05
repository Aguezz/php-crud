<?php

require_once './helper/mysqli.php';

function getCourses()
{
    global $mysqli;

    $query = "SELECT * FROM `courses` ORDER BY `name` ASC";
    $result = $mysqli->query($query);

    return $result;
}

function getStudentsWithAverage()
{
    global $mysqli;

    $query = <<<SQL
        SELECT `s`.`id`,
            `s`.`name`,
            ROUND(AVG(`shc`.`daily_assignments_grade`), 1) AS `daily_assignments_grade`,
            ROUND(AVG(`shc`.`midterm_exam_grade`), 1) AS `midterm_exam_grade`,
            ROUND(AVG(`shc`.`final_exam_grade`), 1) AS `final_exam_grade`
        FROM `students` AS `s`
        INNER JOIN `student_has_course` AS `shc`
            ON `shc`.`student_id` = `s`.`id`
        GROUP BY `shc`.`student_id`
    SQL;
    $result = $mysqli->query($query);

    return $result;
}

function findStudent($id)
{
    global $mysqli;

    $query = "SELECT * FROM `students` WHERE `id` = ? LIMIT 1";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $stmt->close();

    return $result->fetch_object();
}

function findStudentsWithAverage($id) {
    global $mysqli;

    $query = <<<SQL
        SELECT `s`.`id`,
            `s`.`name`,
            ROUND(AVG(`shc`.`daily_assignments_grade`), 1) AS `daily_assignments_grade`,
            ROUND(AVG(`shc`.`midterm_exam_grade`), 1) AS `midterm_exam_grade`,
            ROUND(AVG(`shc`.`final_exam_grade`), 1) AS `final_exam_grade`
        FROM `students` AS `s`
        INNER JOIN `student_has_course` AS `shc`
            ON `shc`.`student_id` = `s`.`id`
        WHERE `s`.`id` = ?
        GROUP BY `shc`.`student_id`
        LIMIT 1
    SQL;
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $stmt->close();

    return $result->fetch_object();
}

function getCoursesGrade($id)
{
    global $mysqli;

    $query = <<<SQL
        SELECT `c`.`id`,
            `c`.`name`,
            `shc`.`daily_assignments_grade`,
            `shc`.`midterm_exam_grade`,
            `shc`.`final_exam_grade`
        FROM `courses` AS `c`
        INNER JOIN `student_has_course` AS `shc`
            ON `shc`.`course_id` = `c`.`id`
        WHERE `shc`.`student_id` = ?
        ORDER BY `c`.`name` ASC
    SQL;
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $stmt->close();

    return $result;
}

function getStudentCourses($id) {
    global $mysqli;

    $query = <<<SQL
        SELECT `c`.`id`,
            `c`.`name`,
            `shc`.`daily_assignments_grade`,
            `shc`.`midterm_exam_grade`,
            `shc`.`final_exam_grade`
        FROM `courses` AS `c`
        INNER JOIN `student_has_course` AS `shc`
            ON `shc`.`course_id` = `c`.`id`
        WHERE `shc`.`student_id` = ?
        ORDER BY `c`.`name` ASC
    SQL;
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $stmt->close();

    return $result;
}