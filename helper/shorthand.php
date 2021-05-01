<?php

require_once __DIR__ . '/../vendor/adodb/adodb-php/adodb-lib.inc.php';
require_once './helper/mysqli.php';
require_once './helper/utils.php';

function query($query, ...$arguments)
{
    global $db;

    if (count($arguments) === 0) {
        $result = $db->Execute($query);
    } else {
        $handle = $db->Prepare($query);
        $bindVariables = [];
        foreach ($arguments as $key => $value) {
            $bindVariables = array_merge($bindVariables, [$key => $value]);
        }
        $result = $db->Execute($handle, $bindVariables);
    }

    return $result;
}

function getCourses()
{
    $query = "SELECT * FROM `courses` ORDER BY `name` ASC";
    return query($query);
}

function getStudentsWithAverage()
{
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

    return convertList(query($query));
}

function findStudent($id)
{
    $query = "SELECT * FROM `students` WHERE `id` = ? LIMIT 1";
    return query($query, $id)->FetchObj();
}

function findStudentWithAverage($id)
{
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

    return query($query, $id)->FetchObj();
}

function getCoursesGrade($id)
{
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

    return convertList(query($query, $id));
}

function getStudentCourses($id)
{
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

    return convertList(query($query, $id));
}
    