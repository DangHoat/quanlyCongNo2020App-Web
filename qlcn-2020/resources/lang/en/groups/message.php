<?php
/**
* Language file for group error/success messages
*
*/

return array(

    'group_exists'        => 'Role already exists!',
    'group_not_found'     => 'Role [:id] does not exist.',
    'group_name_required' => 'The name field is required',
    'users_exists'        => 'Role contains users, role can not be deleted',

    'success' => array(
        'create' => 'Role was successfully created.',
        'update' => 'Role was successfully updated.',
        'delete' => 'Role was successfully deleted.',
    ),

    'delete' => array(
        'create' => 'There was an issue creating the role. Please try again.',
        'update' => 'There was an issue updating the role. Please try again.',
        'delete' => 'There was an issue deleting the role. Please try again.',
    ),

    'error' => array(
        'group_exists' => 'A role already exists with that name, names must be unique for roles.',
        'group_role_exists' => 'Another role with same slug exists, please choose another name',
        'no_role_exists' => 'No role exists with that id.',

    ),

);
