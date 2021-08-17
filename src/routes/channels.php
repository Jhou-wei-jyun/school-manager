<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('message-{message}', function ($user, $message) {
//     return ['id' => $user->id, 'name' => $user->profile->name];
// });
Broadcast::channel('message-{message}', function ($user, $message) {
    if (get_class($user) === 'App\User') {
        // return ['id' => auth()->guard('appUser')->user()->id, 'name' => auth()->guard('appUser')->user()->profile->name];
        return ['id' => $user->id, 'name' => $user->profile->name];
    } elseif (get_class($user) === 'App\Parents') {
        // return ['id' => auth()->guard('appParent')->user()->parent_id, 'name' => auth()->guard('appParent')->user()->name];
        return ['id' => $user->parent_id, 'name' => $user->name];
    } elseif (get_class($user) === 'App\Admin') {
        // return ['id' => auth()->guard('appParent')->user()->parent_id, 'name' => auth()->guard('appParent')->user()->name];
        return ['id' => $user->id, 'name' => $user->name];
    }
});

// Broadcast::channel('message-{message}', function ($user, $message) {
//     return ['id' => $user->id, 'name' => $user->profile];
// }, ['guards' => ['appUser', 'appParent']]);
// Broadcast::channel('message-{message}', function ($user, $message) {
//     return ['id' => auth()->guard('appParent')->user()->id, 'name' => auth()->guard('appParent')->user()->name];
// }, ['guards' => ['appParent']]);
