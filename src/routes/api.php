<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::namespace('Api')->group(function () {
    Route::post('/auth/getByPass', 'AuthController@getByPass');
    Route::post('/auth/register', 'AuthController@register');
    Route::post('/auth/device_token_online_check', 'AuthController@device_token_online_check');
    Route::post('/auth/login', 'AuthController@login');
    Route::post('/auth/beforeLogin_log', 'AuthController@beforeLogin_log');
    Route::post('/auth/login_log', 'AuthController@login_log');
    Route::post('/auth/signout', 'AuthController@SignOut');
    Route::get('areaList', 'AreaController@list');

    Route::get('business', 'SettingController@business');

    Route::get('area', 'AreaController@device');
    Route::get('network', 'DeviceController@network');
    Route::post('IPupdate', 'DeviceController@IPupdate');
    Route::get('files/{device_mac}', 'DeviceController@raspberrypiFile');

    Route::get('status', 'StatuController@index');

    Route::resource('positions', 'PositionController')->except([
        'create', 'edit'
    ]);

    Route::post('userInfo', 'UserController@index');
    Route::post('userInfoUpdate', 'UserController@update');
    Route::prefix('department')->group(function () {
        Route::post('index', 'ReportController@departmentIndex');
        Route::post('info', 'ReportController@departmentInfo');
        // Route::post('getAttendance', 'ReportController@getAttendance');
        // Route::post('getErrTemp', 'ReportController@getErrTemp');
    });


    Route::post('temperature', 'TemperatureController@temper');

    // Route::resource('records', 'RecordController')->except([
    //     'create', 'edit','show','index'
    // ]);
    /* for scanner */
    Route::post('records', 'RecordController@store');

    Route::resource('doyouknow', 'DoyouknowController')->except([
        'create', 'edit'
    ]);

    Route::get('categories', 'CategoryController@index');

    Route::get('categoryItems', 'CategoryController@show');

    Route::put('updateTeacherDeviceToken', 'UserController@updateTeacherDeviceToken');
    Route::put('updateUserAvatar', 'UserController@updateUserAvatar');
    Route::post('getAvatar', 'UserController@getAvatar');
    Route::post('editInfo', 'UserController@editInfo');

    Route::get('parents', 'ParentsController@index');
    Route::post('checkparents', 'ParentsController@checkparents');
    Route::post('checkparents2', 'ParentsController@checkparents2');
    Route::put('UpdataParentDeviceToken', 'ParentsController@UpdataParentDeviceToken');



    // Route::post('day_late', 'ReportController@day_late');
    Route::post('day_work', 'ReportController@day_work');
    Route::post('week_work', 'ReportController@week_work');
    Route::post('month_work', 'ReportController@month_work');

    Route::post('getTodayTemperatures', 'ReportController@today_temperatures');
    Route::post('week_Temperatures', 'ReportController@week_temperatures');
    Route::post('month_Temperatures', 'ReportController@month_temperatures');
    Route::post('getTodayMorningTemperatures', 'ReportController@morning_temperatures');
    Route::post('getTodayAfternoonTemperatures', 'ReportController@afternoon_temperatures');

    Route::post('parents_relations', 'ReportController@parents_relations');
    Route::post('parents_relations2', 'ReportController@parents_relations2');
    Route::post('teacher_relations', 'ReportController@teacher_relations');
    Route::post('refresh_parent', 'ReportController@refresh_parent_student');
    Route::post('refresh_teacher', 'ReportController@refresh_teacher_student');

    Route::prefix('face')->group(function () {
        // Route::post('store','FaceMachineController@store');
        Route::post('temper', 'FaceMachineController@temper');
        Route::post('updataMachineDeviceToken', 'FaceMachineController@updataMachineDeviceToken');
        Route::post('indexMachineSerialNumber', 'FaceMachineController@indexMachineSerialNumber');
    });



    //Message
    Route::prefix('message')->group(function () {
        Route::post('readMessage', 'ChatController@readMessage');
        Route::post('getMessage', 'ChatController@getMessage');
        Route::post('newMessage', 'ChatController@send');
        Route::post('unread_check', 'ChatController@unreadCheck');
    });
    //
    // Route::post('messagesave','ChatmessageController@messagesave');
    // Route::post('getmessage','ChatmessageController@getmessage');
    // Route::post('messageupdate','ChatmessageController@messageupdate');
    // Route::post('message/unread_check','ChatmessageController@unread_check');
    //

    Route::post('setVerification', 'VerificationController@setVerification');
    Route::post('VerificationLoginCheck', 'VerificationController@VerificationLoginCheck');
    Route::post('getApiToken', 'VerificationController@getApiToken');

    Route::post('/departmentName', 'DepartmentController@departmentName');
    // Route::post('section/store','SectionController@store');
    Route::prefix('contact')->group(function () {
        Route::post('contactNotify', 'ContactController@contactNotify');
        Route::post('index', 'ContactController@index');
        // Route::post('update', 'ContactController@update');
        Route::post('updateNew', 'ContactController@updateNew');
        Route::post('contactInfo', 'ContactController@contactInfo');
        Route::post('history', 'ContactController@history');
        Route::post('setting', 'ContactController@get_setting');
        Route::post('selectContact', 'ContactController@selectContact');
        Route::post('getMood', 'ContactController@getMood');
        Route::post('updateSync', 'ContactController@updateSync');
        Route::post('notImageUplode', 'ContactController@notImageUplode');
        Route::post('imageUplode', 'ContactController@imageUplode');
        Route::post('getContactAttachment', 'ContactController@getContactAttachment');
        Route::post('deleteImage', 'ContactController@deleteImage');
        Route::post('deleteNotImage', 'ContactController@deleteNotImage');
        Route::prefix('new')->group(function () {
            Route::post('index', 'ContactController@new_index');
            Route::post('updateNew', 'ContactController@new_updateNew');
            Route::post('updateSync', 'ContactController@new_updateSync');
            Route::post('history', 'ContactController@new_history');
            Route::post('selectContact', 'ContactController@new_selectContact');
            Route::post('contactInfo', 'ContactController@new_contactInfo');
            Route::post('contactNotify', 'ContactController@new_contactNotify');
        });
    });
    Route::prefix('medicine')->group(function () {
        Route::post('medicineNotify', 'MedicineController@medicineNotify');
        Route::post('index', 'MedicineController@index');
        Route::post('listIndex', 'MedicineController@listIndex');
        Route::post('listInfo', 'MedicineController@listInfo');
        Route::post('teacherCheck', 'MedicineController@teacherCheck');
        Route::post('update', 'MedicineController@update');
        Route::post('history', 'MedicineController@history');
        Route::post('photo', 'MedicineController@photo');
        Route::post('photoDelete', 'MedicineController@photoDelete');
        Route::prefix('new')->group(function () {
            Route::post('index', 'MedicineController@new_index');
            Route::post('update', 'MedicineController@new_update');
            Route::post('listIndex', 'MedicineController@new_listIndex');
            Route::post('listInfo', 'MedicineController@new_listInfo');
            Route::post('teacherCheck', 'MedicineController@new_teacherCheck');
            Route::post('history', 'MedicineController@new_history');
            Route::post('medicineNotify', 'MedicineController@new_medicineNotify');
        });
    });
    Route::post('announce', 'AnnounceController@index');

    Route::post('notifies', 'NotifyController@index');
    Route::prefix('notifies')->group(function () {
        Route::post('info', 'NotifyController@info');
        Route::post('selectNotify', 'NotifyController@OriginSelectNotify');
        Route::prefix('new')->group(function () {
            Route::post('selectNotify', 'NotifyController@selectNotify');
        });
    });

    Route::prefix('achievement')->group(function () {
        Route::post('index', 'AchievementController@AchievementIndex');
        // Route::post('temperature', 'AchievementController@temperature');
        // Route::post('onTime', 'AchievementController@onTime');
        // Route::post('returnContact', 'AchievementController@returnContact');
    });
    Route::prefix('album')->group(function () {
        Route::prefix('child')->group(function () {
            Route::post('index', 'AlbumController@indexPhoto');
            Route::post('deletePhoto', 'AlbumController@deletePhoto');
            Route::post('newPhoto', 'AlbumController@newPhoto');
        });
        Route::post('indexAlbum', 'AlbumController@indexAlbum');
        Route::post('selectAlbum', 'AlbumController@selectAlbum');
        Route::post('newAlbum', 'AlbumController@newAlbum');
        Route::post('deleteAlbum', 'AlbumController@deleteAlbum');
        Route::post('editAlbum', 'AlbumController@editalbum');
        Route::prefix('tag')->group(function () {
            Route::post('tagIndex', 'TagController@tagIndex');
            Route::post('tagAdd', 'TagController@tagAdd');
            Route::post('tagDelete', 'TagController@tagDelete');
            Route::post('tagAlbumAdd', 'TagController@tagAlbumAdd');
            Route::post('tagAlbumRemove', 'TagController@tagAlbumRemove');
            Route::post('tagPhotoAdd', 'TagController@tagPhotoAdd');
            Route::post('tagPhotoRemove', 'TagController@tagPhotoRemove');
            Route::post('userTagPhotoAdd', 'TagController@userTagPhotoAdd');
            Route::post('userTagPhotoRemove', 'TagController@userTagPhotoRemove');
            Route::post('albumTagIndex', 'TagController@albumTagIndex');
            Route::post('userTagIndex', 'TagController@userTagIndex');
            Route::post('userTagSelectAlbum', 'TagController@userTagSelectAlbum');
            Route::post('userTagSelectPhoto', 'TagController@userTagSelectPhoto');
            Route::post('photoHasTagIndex', 'TagController@photoHasTagIndex');
        });
        // Route::post('trashIndex', 'AlbumController@trashIndex');
        // Route::post('deletePhotoFromTrash', 'AlbumController@deletePhotoFromTrash');
        // Route::post('deleteAlbumFromTrash', 'AlbumController@deleteAlbumFromTrash');
        // Route::post('restoreAlbumFromTrash', 'AlbumController@restoreAlbumFromTrash');
        // Route::post('restorePhotoFromTrash', 'AlbumController@restorePhotoFromTrash');
    });
    Route::prefix('option')->group(function () {
        Route::post('indexOption', 'OptionController@indexOption');
    });
    Route::prefix('qrcode')->group(function () {
        Route::post('inSchoolCheck', 'QrcodeController@inSchoolCheck');
        Route::post('create', 'QrcodeController@createURL');
        Route::post('verify/{token}', 'QrcodeController@Verify');
        Route::get('verify/{token}', function () {
            abort(403, '請求方式錯誤');
        })->where('any', '.*');
        Route::post('teacherCheck', 'QrcodeController@teacherCheck');
        Route::post('delete', 'QrcodeController@deleteURL');
    });
    Route::prefix('question')->group(function () {
        Route::post('index', 'QuestionController@index');
    });
    Route::prefix('feedback')->group(function () {
        Route::post('selectType', 'FeedbackController@selectType');
        Route::post('store', 'FeedbackController@store');
    });
});

Route::namespace('Api')->middleware('auth:api')->group(function () {

    Route::resource('areas', 'AreaController')->except([
        'create', 'edit'
    ]);

    Route::resource('records', 'RecordController')->except([
        'create', 'edit', 'store'
    ]);



    Route::resource('self', 'UserController')->except([
        'create', 'edit'
    ]);

    Route::put('deviceToken/{dt}', 'UserController@updateDeviceToken');

    // Route::get('employees','SupervisorController@employees');
    // Route::get('notifies','NotifyController@index');
    Route::get('employeeDetails/{employee_id}/{timestamp}', 'SupervisorController@employeeDetails');
    Route::get('temper', 'TemperatureController@index');
});
