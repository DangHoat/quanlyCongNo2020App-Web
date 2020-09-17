<?php
/**
* Language file for user error/success messages
*
*/

return [

    // 'user_exists'              => 'User already exists!',
    // 'user_not_found'           => 'User [:id] does not exist.',
    // 'user_login_required'      => 'The login field is required',
    // 'user_password_required'   => 'The password is required.',
    // 'insufficient_permissions' => 'Insufficient Permissions.',
    // 'banned'              => 'banned',
    // 'suspended'             => 'suspended',

    'success' => [
        'create'    => 'Client was successfully created.',
        'update'    => 'Client was successfully updated.',
        'delete'    => 'Client was successfully deleted.',
        'ban'       => 'Client was successfully banned.',
        'unban'     => 'Client was successfully unbanned.',
        'suspend'   => 'Client was successfully suspended.',
        'unsuspend' => 'Client was successfully unsuspended.',
        'restored'  => 'Client was successfully restored.'
    ],

    'error' => [
        'create'    => 'There was an issue creating the client. Please try again.',
        'update'    => 'There was an issue updating the client. Please try again.',
        'delete'    => 'There was an issue deleting the client. Please try again.',
        'unsuspend' => 'There was an issue unsuspending the client. Please try again.',
        'file_type_error'   => 'Only jpg, jpeg, bmp, png extensions are allowed.',
    ],

];

