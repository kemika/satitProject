<?php

use Illuminate\Database\Seeder;

class GradeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      //////////////////////////////////////////////// ART 1
      $admin = new App\Grade;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '1';
      $admin->quater = '1';
      $admin->semester = '1';
      $admin->academic_year = '18';
      $admin->grade = '1.11';
      $admin->grade_status = '5';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-01';
      $admin->save();



      $admin = new App\Grade;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '1';
      $admin->quater = '2';
      $admin->semester = '1';
      $admin->academic_year = '18';
      $admin->grade = '2.22';
      $admin->grade_status = '5';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-01';
      $admin->save();


      $admin = new App\Grade;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '1';
      $admin->quater = '3';
      $admin->semester = '1';
      $admin->academic_year = '18';
      $admin->grade = '3.33';
      $admin->grade_status = '5';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-02';
      $admin->save();


      //////////////////////////////////////////////// กรณีเกรดไม่ aprroved music 1

      $admin = new App\Grade;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '2';
      $admin->quater = '1';
      $admin->semester = '1';
      $admin->academic_year = '18';
      $admin->grade = '1.11';
      $admin->grade_status = '5';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-01';
      $admin->save();



      $admin = new App\Grade;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '2';
      $admin->quater = '2';
      $admin->semester = '1';
      $admin->academic_year = '18';
      $admin->grade = '2.22';
      $admin->grade_status = '5';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-01';
      $admin->save();


      $admin = new App\Grade;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '2';
      $admin->quater = '3';
      $admin->semester = '1';
      $admin->academic_year = '18';
      $admin->grade = '3.33';
      $admin->grade_status = '5';
      $admin->data_status = '0';
      $admin->datetime = '2017-01-02';
      $admin->save();


      ////////////////////////////////////////////////






      //////////////////////////////////////////////// ปีไม่ตรง  dev มั้ง
      $admin = new App\Grade;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '3';
      $admin->quater = '1';
      $admin->semester = '1';
      $admin->academic_year = '118';
      $admin->grade = '1';
      $admin->grade_status = '5';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-01';
      $admin->save();



      $admin = new App\Grade;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '3';
      $admin->quater = '2';
      $admin->semester = '1';
      $admin->academic_year = '18';
      $admin->grade = '3';
      $admin->grade_status = '5';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-01';
      $admin->save();


      $admin = new App\Grade;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '3';
      $admin->quater = '3';
      $admin->semester = '1';
      $admin->academic_year = '18';
      $admin->grade = '6.21';
      $admin->grade_status = '5';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-02';
      $admin->save();


      ////////////////////////////////////////////////






      //////////////////////////////////////////////// semester 2
      $admin = new App\Grade;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '4';
      $admin->quater = '1';
      $admin->semester = '2';
      $admin->academic_year = '18';
      $admin->grade = '1.11';
      $admin->grade_status = '5';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-01';
      $admin->save();



      $admin = new App\Grade;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '4';
      $admin->quater = '2';
      $admin->semester = '1';
      $admin->academic_year = '18';
      $admin->grade = '2.22';
      $admin->grade_status = '5';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-01';
      $admin->save();


      $admin = new App\Grade;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '4';
      $admin->quater = '3';
      $admin->semester = '2';
      $admin->academic_year = '18';
      $admin->grade = '3.33';
      $admin->grade_status = '5';
      $admin->data_status = '0';
      $admin->datetime = '2017-01-02';
      $admin->save();


      ////////////////////////////////////////////////





      ////////////////////////////////////////////////

      $admin = new App\Grade;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '5';
      $admin->quater = '1';
      $admin->semester = '1';
      $admin->academic_year = '18';
      $admin->grade = '1.11';
      $admin->grade_status = '5';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-01';
      $admin->save();



      $admin = new App\Grade;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '5';
      $admin->quater = '2';
      $admin->semester = '1';
      $admin->academic_year = '18';
      $admin->grade = '2.22';
      $admin->grade_status = '5';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-01';
      $admin->save();


      $admin = new App\Grade;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '5';
      $admin->quater = '3';
      $admin->semester = '1';
      $admin->academic_year = '18';
      $admin->grade = '3.33';
      $admin->grade_status = '5';
      $admin->data_status = '0';
      $admin->datetime = '2017-01-02';
      $admin->save();


      ////////////////////////////////////////////////



    



    }
}
