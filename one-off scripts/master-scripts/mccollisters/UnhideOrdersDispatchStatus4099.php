<?php
/**
 * Created by PhpStorm.
 * User: dbolin
 * Date: 2/3/2017
 * Time: 9:01 AM
 */
if (function_exists("call_ms_function_ver")) {
    $version = 1;
    if (call_ms_function_ver(__FILE__, $version)) {
        //already ran
        print "\e[33mSKIPPING: " . __FILE__ . "<br />\n\e[0m";
        return;
    }
}
print "\e[32mRUNNING: " . __FILE__ . "<br />\n\e[0m";

include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');

$module = Vtiger_Module::getInstance('Orders');

if(!$module)
{
    return;
}

$field = Vtiger_Field::getInstance('orders_otherstatus', $module);

if(!$field)
{
    return;
}

$db = &PearDatabase::getInstance();
$db->pquery('UPDATE vtiger_field SET presence=? WHERE fieldid=?',
            [2, $field->id]);



print "\e[94mFINISHED: " . __FILE__ . "<br />\n\e[0m";