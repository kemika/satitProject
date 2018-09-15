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

    // Constant for grade_level_group
    const G1_6 = 0;
    const G7_8 = 1;
    const G9_12 = 2;

    // 2 quaters plus 1 final
    const TOTAL_QUARTERS = 3;
}