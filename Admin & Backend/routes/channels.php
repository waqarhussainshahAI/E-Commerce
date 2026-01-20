<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;


Broadcast::routes([
    'middleware' => ['auth:sanctum'],
]);

//this is auth just for checking is sending notification to correct user or not,
//in api cause set middlwate to sanctum and create channel but in other 
// not necessary to create becuase channels are auto created in notification method
// and channel for notificaion which is auto created  is 
//  --- private-App.modelname.id

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
Log::info('CHANNEL AUTH CHECK', [
        'auth_user_id' => $user->id,
        'channel_id'   => $id,
    ]);      return (int) $user->id === (int) $id;

});
