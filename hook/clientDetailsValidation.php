<?php

if (!defined("WHMCS")) die("This file cannot be accessed directly");

use WHMCS\Database\Capsule;

add_hook('ClientDetailsValidation', 1, function($vars) {
    
    $fieldid = 1; //by default if no other custom fields are set the id is 1.

    //This select will update the fieldid if other custom fields are set.
    try {
        $data = Capsule::table('tblcustomfields')
            ->where("fieldname", "username")   
            ->orWhere("fieldname", "Username")
            ->first();
        
        if (isset($data->id)) {
            $fieldid = $data->id;
        }         
    }

    catch (\Exception $e) {
        error_log($e->getMessage());
    }


    try {
        $data = Capsule::table('tblcustomfieldsvalues')
            ->where("value", $vars['customfield'][$fieldid])
            ->first();
        
        if (isset($data->value)) {
            return ['Username is taken.'];
        }         
    }

    catch (\Exception $e) {
        error_log($e->getMessage());
    }

});
