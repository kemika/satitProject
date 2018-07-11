<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/main', 'MainController@index');





Route::get('/manageStudents', 'ManageStudentsController@index');
Route::put('/manageStudents/update', 'ManageStudentsController@update');

Route::get('/manageCurriculum', 'ManageCurriculumController@index');
Route::post('/manageCurriculum/editSubject', 'ManageCurriculumController@editSubject');
Route::post('/manageCurriculum/createNewYear', 'ManageCurriculumController@createNewYear');
Route::post('/manageCurriculum/importFromPrevious', 'ManageCurriculumController@importFromPrevious');
Route::post('/manageCurriculum/createNewSubject', 'ManageCurriculumController@createNewSubject');

//Route::post('/manageCurriculum/edit', 'ManageCurriculumController@edit');
Route::get('/manageCurriculum/{year}', 'ManageCurriculumController@editWithYear');

Route::get('/manageTeachers', 'ManageTeachersController@index');
Route::put('/manageTeachers/update', 'ManageTeachersController@update');


Route::get('/manageAcademic', 'ManageAcademicController@index');

Auth::routes();

Route::get('/uploadGrade', 'UploadGradeController@index');
Route::get('/uploadGrade/{subject}', 'UploadGradeController@showClass');
Route::get('export-file/{type}', 'UploadGradeController@exportExcel')->name('export.file');
Route::post('/uploadGrade/import', 'UploadGradeController@import');
Route::get('/upload', 'UploadGradeController@upload');
Route::post('/getUpload', 'UploadGradeController@getUpload');

Route::get('/viewGrade', 'ViewGradeController@index');
Route::put('/viewGrade/result', 'ViewGradeController@result');


Route::get('/reportCard', 'ReportCardController@index');

Route::get('/transcript', 'TranscriptController@index');





Route::get('/bbb','GradeController@boom');
