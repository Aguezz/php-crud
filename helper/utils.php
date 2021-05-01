<?php

function convertGrade($grade)
{
    if ($grade > 90) {
        return 'A';
    } else if ($grade > 80) {
        return 'B';
    } else if ($grade > 70) {
        return 'C';
    } else if ($grade > 60) {
        return 'D';
    } else {
        return 'E';
    }
}

function convertList($result) {
    $data = [];

    while ($item = $result->FetchNextObj()) {
        $data[] = $item;
    }

    return $data;
}

function sanitizeNumber($number) {
    $number = (int) join(array_slice(str_split((string) $number), 0, 3));
    if ($number >= 0 && $number <= 100) {
        return $number;
    } else {
        return (int) join(array_slice(str_split((string) $number), 0, 2));
    }

    return null;
}
