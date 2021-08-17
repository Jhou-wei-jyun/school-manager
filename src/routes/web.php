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

Route::post('admin/admin-register/shoesconn', 'LoginController@adminRegister');
// Route::post('login', 'HomeController@login');
Route::post('register', 'LoginController@register');
Route::post('forgotPassword', 'LoginController@forgotPassword');
Route::post('resetPassword', 'LoginController@resetPassword');
Route::post('login', 'LoginController@login');
Route::post('logout', 'LoginController@logout');
Route::post('phoneValidity', 'LoginController@setPhoneVerification');
Route::post('checkValidity', 'LoginController@checkValidity');
Route::post('accountValidity', 'LoginController@accountValidity');
Route::get('getAccount', 'LoginController@getAccount');



Route::group(['middleware' => ['web.admin']], function () {

    // Route::get('about','HomeController@about');
    Route::get('recordList', 'HomeController@record');
    Route::get('department', 'HomeController@department');
    Route::get('departmentDetail', 'HomeController@departmentDetail');
    Route::get('newEmployee', 'HomeController@newEmployee');
    Route::get('material', 'HomeController@material');
    Route::get('notify', 'HomeController@notify');
    Route::get('property', 'HomeController@property');
    Route::get('env', 'HomeController@env');
    Route::get('area', 'HomeController@area');
    Route::get('parents', 'HomeController@parents');
    Route::get('mainhome', 'HomeController@mainhome');
    Route::get('teacher', 'HomeController@teacher');
    Route::get('chart', 'HomeController@chart');
    Route::get('announcement', 'HomeController@announce');
    Route::get('account', 'HomeController@account');
    Route::get('becon', 'HomeController@becon');
    Route::get('attendance', 'HomeController@attendance');
    Route::get('contact', 'HomeController@contact');
    Route::get('albumCloud', 'HomeController@album');
    Route::get('albumDetail', 'HomeController@albumDetail');
    Route::get('albumChild', 'HomeController@albumChild');
    Route::get('edit-admin', 'HomeController@right');
    Route::get('async-machines', 'HomeController@asyncMachines');
    Route::get('option-setting', 'HomeController@optionSetting');
    Route::get('medicines', 'HomeController@medicine');
    Route::get('leave', 'HomeController@leave');
    Route::get('edit-question', 'HomeController@question');

    /* Api document */
    Route::get('webDoc', 'HomeController@webDoc');
    Route::get('serveDoc', 'HomeController@serveDoc');
    Route::get('appDoc', 'HomeController@appDoc');
    Route::get('setup', 'HomeController@setup');


    Route::get('demo_area', 'DemoController@index');
    Route::get('areas', 'AreaController@index');
    Route::post('updateArea', 'AreaController@update');
    Route::post('area', 'AreaController@store');


    Route::get('device', 'HomeController@device');
    Route::get('employee', 'HomeController@employee');
    Route::get('devices', 'DeviceController@index');
    Route::post('device', 'DeviceController@store');
    Route::put('device', 'DeviceController@delete');
    Route::post('files', 'DeviceController@uploadFile');
    Route::put('file', 'DeviceController@deleteFile');


    Route::get('scannerRecords', 'DeviceController@scannerRecords');
    Route::post('editDevice', 'DeviceController@editDevice');

    Route::get('scanner', 'DeviceController@scanner');

    Route::get('allFiles', 'DeviceController@allFiles');


    Route::get('areaOptions', 'DeviceController@areas');

    Route::prefix('department')->group(function () {
        Route::get('index', 'DepartmentController@index');
        Route::post('store', 'DepartmentController@store');
        Route::post('update', 'DepartmentController@update');
        Route::put('delete', 'DepartmentController@delete');
        Route::get('detail', 'DepartmentController@detail');
        Route::get('SelectTeacher', 'DepartmentController@SelectTeacher');
        Route::get('SelectDepart', 'DepartmentController@SelectDepart');
        Route::post('addDepart', 'DepartmentController@addDepart');
        Route::prefix('avatar')->group(function () {
            Route::get('index', 'DepartmentController@avatar_index');
            Route::post('store', 'DepartmentController@avatar_store');
            Route::post('change', 'DepartmentController@avatar_change');
            Route::put('delete', 'DepartmentController@avatar_delete');
        });
        Route::prefix('student')->group(function () {
            Route::post('delete', 'FaceMachineController@student_delete');
            Route::prefix('becon')->group(function () {
                Route::get('index', 'DepartmentStudentController@index_becon');
            });
            Route::prefix('face')->group(function () {
                Route::get('index', 'DepartmentStudentController@index_face');
            });
            Route::prefix('becon_face')->group(function () {
                Route::get('index', 'DepartmentStudentController@index_becon_face');
            });
        });
    });




    Route::prefix('pair')->group(function () {
        Route::prefix('department')->group(function () {
            Route::get('all_student_list', 'EmployeeController@department_all_student_list');
            Route::get('pair_student_list', 'EmployeeController@department_pair_student_list');
            Route::post('relationship_change', 'EmployeeController@department_relationship_change');
        });
        Route::prefix('parent')->group(function () {
            Route::get('all_student_list', 'ParentsController@parent_all_student_list');
            Route::get('pair_student_list', 'ParentsController@parent_pair_student_list');
            Route::post('relationship_change', 'ParentsController@parent_relationship_change');
        });
    });

    Route::prefix('parent')->group(function () {
        Route::get('index', 'ParentsController@index');
        Route::post('store', 'ParentsController@store');
        Route::post('update', 'ParentsController@update');
        Route::put('delete', 'ParentsController@delete');
        Route::get('export', 'ExportController@parent_export');
        // Route::get('sample','ExportController@parentsample');
        // Route::post('import','ImportController@parentimport');
    });

    Route::get('recordData', 'RecordController@index');
    Route::get('redis', 'RedisController@index');
    Route::post('searchRecord', 'RecordController@search');

    Route::prefix('student')->group(function () {
        Route::post('store', 'FaceMachineController@student_store');
        Route::post('delete', 'FaceMachineController@student_delete');
        Route::prefix('update')->group(function () {
            Route::post('info', 'EmployeeController@update_info');
            Route::post('avatar', 'FaceMachineController@student_update_avatar');
        });
        Route::prefix('becon')->group(function () {
            Route::get('index', 'EmployeeController@index_becon');
            Route::get('export', 'ExportController@all_student_becon_face_export');
            Route::get('department/export', 'ExportController@depart_student_becon_face_export');
            // Route::get('allEmployees/sample','ExportController@allstudentsample');
            // Route::get('department/detail/sample','ExportController@departstudentsample');
            // Route::post('student/import','ImportController@studentimport');
        });
        Route::prefix('face')->group(function () {
            Route::get('index', 'EmployeeController@index_face');
            Route::get('export', 'ExportController@all_student_face_export');
            Route::get('department/export', 'ExportController@depart_student_face_export');
        });
        Route::prefix('becon_face')->group(function () {
            Route::get('index', 'EmployeeController@index_becon_face');
            Route::get('export', 'ExportController@all_student_becon_face_export');
            Route::get('department/export', 'ExportController@depart_student_becon_face_export');
        });
        Route::get('SelectDepartment', 'DepartmentController@SelectDepartment');
    });

    Route::put('deleteEmployee', 'EmployeeController@delete');

    Route::prefix('teacher')->group(function () {
        Route::post('store', 'FaceMachineController@teacher_store');
        Route::post('delete', 'FaceMachineController@teacher_delete');
        Route::prefix('update')->group(function () {
            Route::post('info', 'TeacherController@update_info');
            Route::post('avatar', 'FaceMachineController@teacher_update_avatar');
        });
        Route::prefix('becon')->group(function () {
            Route::get('index', 'TeacherController@index_becon');
            Route::get('export', 'ExportController@all_teache_becon_facer_export');
            Route::get('export/time', 'ExportController@all_teacher_time_becon_facer_export');
        });
        Route::prefix('face')->group(function () {
            Route::get('index', 'TeacherController@index_face');
            Route::get('export', 'ExportController@all_teache_facer_export');
            Route::get('export/time', 'ExportController@all_teacher_time_facer_export');
        });
        Route::prefix('becon_face')->group(function () {
            Route::get('index', 'TeacherController@index_becon_face');
            Route::get('export', 'ExportController@all_teache_becon_facer_export');
            Route::get('export/time', 'ExportController@all_teacher_time_becon_facer_export');
        });
        Route::get('SelectPosition', 'PositionController@SelectPosition');
        // Route::get('allTeachers/export','ExportController@allteacherexport');
        // Route::get('allTeachers/sample','ExportController@teachersample');
        // Route::post('allTeachers/import','ImportController@teacherimport');
        // Route::get('allTeachersTime/export','ExportController@allteachertimeexport');
    });



    Route::get('materials', 'MaterialController@index');

    Route::prefix('notify')->group(function () {
        Route::get('teacher', 'NotifyController@teacherindex');
        Route::post('teacher', 'NotifyController@teacherpush');
        Route::get('parent', 'NotifyController@parentindex');
        Route::post('parent', 'NotifyController@parentpush');
        Route::post('all', 'NotifyController@allpush');
        Route::get('index', 'NotifyController@index');
    });



    Route::get('records', 'RecordController@show');

    Route::get('itemAreas', 'ItemController@areas');
    Route::get('items', 'ItemController@index');
    Route::post('updateItem', 'ItemController@update');

    Route::post('updateDetail/{id}', 'ItemController@updateDetails');
    Route::post('uploadDetailFile', 'ItemController@uploadFile');

    Route::get('itemCategories', 'ItemController@categories');
    Route::post('item', 'ItemController@store');
    Route::put('deleteItem', 'ItemController@delete');

    Route::get('checkMac', 'ItemController@check');

    Route::prefix('data')->group(function () {
        Route::get('getErrTemp', 'DataController@getErrTemp');
        Route::get('getStudent', 'DataController@getStudent');
        Route::get('getTeacher', 'DataController@getTeacher');
        Route::get('getlateStudent', 'DataController@getlateStudent');
        Route::get('getabsentStudent', 'DataController@getabsentStudent');
        Route::get('getabsentTeacher', 'DataController@getabsentTeacher');
    });
    Route::post('temperature/check', 'DataController@temperature_check');

    Route::post('updateimg', 'AboutController@updateimg');
    Route::post('updateinfo', 'AboutController@updateinfo');
    Route::post('getinfo', 'AboutController@index');
    Route::post('refresh_info', 'AboutController@refresh_info');

    Route::prefix('announcement')->group(function () {
        Route::post('store', 'UpdateController@store');
        Route::get('index', 'UpdateController@index');
        Route::post('info', 'UpdateController@info');
        Route::post('delete', 'UpdateController@delete');
    });


    //Chatroom
    Route::post('chatlist', 'ChatController@index');
    Route::post('sendmessage', 'ChatController@messagesave');
    Route::post('getmessage', 'ChatController@getmessage');
    Route::post('private-event', 'ChatController@private');

    //Account
    Route::prefix('account')->group(function () {
        Route::get('info', 'AccountController@info');
        Route::post('updateInfo', 'AccountController@updateinfo');
        Route::get('user', 'AccountController@getuser');
    });


    Route::prefix('becon')->group(function () {
        Route::get('student', 'EmployeeController@beconstudent');
        Route::get('teacher', 'TeacherController@beconteacher');
    });
    Route::prefix('attendance')->group(function () {
        Route::get('departmentsName', 'AttendanceController@departmentsName');
        Route::post('getAttendance_student', 'AttendanceController@getAttendance_student');
        Route::post('getAttendance_teacher', 'AttendanceController@getAttendance_teacher');
    });
    Route::prefix('right')->group(function () {
        Route::get('page', 'RightController@PageRight');
        Route::get('tab', 'RightController@TabRight');
        Route::get('block', 'RightController@BlockRight');
        Route::get('index', 'RightController@RightRelation');
        Route::post('store', 'RightController@RightStore');
    });
    Route::prefix('group')->group(function () {
        Route::get('index', 'RightController@GroupIndex');
        Route::get('right-index', 'RightController@GroupRightIndex');
        Route::post('edit', 'RightController@RightEdit');
        Route::put('delete', 'RightController@GroupDelete');
    });
    Route::prefix('contact')->group(function () {
        Route::get('index', 'ContactController@index');
        Route::post('updateSync', 'ContactController@updateSync');
        Route::get('getEdit', 'ContactController@getEdit');
        Route::post('edit', 'ContactController@edit');
        Route::post('editFile', 'ContactController@editFile');
        Route::prefix('attendance')->group(function () {
            Route::get('index', 'AttendanceController@index');
            Route::post('edit', 'AttendanceController@edit');
        });
    });
    Route::prefix('medicines')->group(function () {
        Route::get('index', 'MedicineController@index');
        Route::get('check', 'MedicineController@check');
        // Route::post('updateSync', 'ContactController@updateSync');
        // Route::get('getEdit', 'ContactController@getEdit');
        // Route::post('edit', 'ContactController@edit');
        // Route::post('editFile', 'ContactController@editFile');
    });
    Route::prefix('leave')->group(function () {
        Route::get('index', 'LeaveController@index');
        Route::get('orderindex', 'LeaveController@orderindex');
    });
    Route::prefix('album')->group(function () {
        // Route::get('detail', 'HomeController@albumDetail');
        // Route::get('child', 'HomeController@albumChild');
        Route::prefix('child')->group(function () {
            Route::get('index', 'AlbumController@indexPhoto');
            Route::post('deletePhoto', 'AlbumController@deletePhoto');
            Route::post('newPhoto', 'AlbumController@newPhoto');
            Route::get('getAlbumInfo', 'AlbumController@getAlbumInfo');
            Route::get('getPhotoInfo', 'AlbumController@getPhotoInfo');
        });
        Route::get('indexAlbum', 'AlbumController@indexAlbum');
        Route::post('newAlbum', 'AlbumController@newAlbum');
        Route::post('deleteAlbum', 'AlbumController@deleteAlbum');
        Route::post('editAlbum', 'AlbumController@editAlbum');
        Route::get('trashIndex', 'AlbumController@trashIndex');
        Route::post('deletePhotoFromTrash', 'AlbumController@deletePhotoFromTrash');
        Route::post('deleteAlbumFromTrash', 'AlbumController@deleteAlbumFromTrash');
        Route::post('restoreAlbumFromTrash', 'AlbumController@restoreAlbumFromTrash');
        Route::post('restorePhotoFromTrash', 'AlbumController@restorePhotoFromTrash');
        Route::prefix('tag')->group(function () {
            Route::get('Index', 'TagController@tagIndex');
            Route::get('userTagIndex', 'TagController@userTagIndex');
            Route::post('Add', 'TagController@tagAdd');
            Route::post('Delete', 'TagController@tagDelete');
            Route::put('AlbumSync', 'TagController@tagAlbumSync');
            Route::put('tagPhotoSync', 'TagController@tagPhotoSync');
            Route::put('userTagPhotoSync', 'TagController@userTagPhotoSync');
            Route::get('getAllUserAvatar', 'TagController@getAllUserAvatar');
            Route::get('getAlbumTag', 'TagController@getAlbumTag');
            Route::post('fileScanTest', 'AlbumController@fileScanTest');
            Route::get('getPhotoTag', 'TagController@getPhotoTag');
            Route::get('getPhotoUserTag', 'TagController@getPhotoUserTag');
        });
    });
    Route::prefix('machine')->group(function () {
        Route::get('index', 'FaceMachineController@machines_list');
        Route::post('sync', 'FaceMachineController@machines_sync');
    });
    Route::prefix('option')->group(function () {
        Route::get('indexOptionType', 'OptionController@indexOptionType');
        Route::get('indexOption', 'OptionController@indexOption');
        Route::post('editOption', 'OptionController@editOption');
    });

    Route::post('testsend', 'ChatController@testsend');
    Route::post('tool/photo_to_small', 'AlbumController@allPhotoSmall');
    Route::post('tool/avatar_rename', 'AlbumController@avatarRename');


    Route::post('excel/import', 'ImportExcelController@importexcel');
    Route::prefix('question')->group(function () {
        Route::get('index', 'QuestionController@index');
        Route::post('store', 'QuestionController@store');
        Route::post('update', 'QuestionController@update');
        Route::post('delete', 'QuestionController@delete');
    });
});
Route::post('test/store', 'FaceMachineController@TEST');
Route::post('test/testFaceScan', 'FaceMachineController@testFaceScan');

// Route::prefix('contact')->group(function () {
//     Route::prefix('attendance')->group(function () {
//         Route::get('index', 'AttendanceController@index');
//         Route::post('edit', 'AttendanceController@edit');
//     });
// });
