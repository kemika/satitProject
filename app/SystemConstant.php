<?php
/**
 * Created by PhpStorm.
 * User: ruj
 * Date: 13/9/2018 AD
 * Time: 21:24
 */

namespace App;


class SystemConstant
{
    // These 2 values should be set following value in Grade Status table in database
    const NO_GRADE = 0;
    const HAS_GRADE = 5;

    // 2 quaters exclude final  This is total quarter for each semester.
    const TOTAL_QUARTERS = 2;

    // Quarter signifying final score
    const FINAL_Q = 3;

    // Constant for N/A grade to be used with all cumulative grade data
    const NA_GRADE = "-";

    const TOTAL_SEMESTERS = 2;
}