<?php
if (function_exists("call_ms_function_ver")) {
    $version = 1;
    if (call_ms_function_ver(__FILE__, $version)) {
        //already ran
        print "\e[33mSKIPPING: " . __FILE__ . "<br />\n\e[0m";
        return;
    }
}
print "\e[32mRUNNING: " . __FILE__ . "<br />\n\e[0m";

 

// Turn on debugging level
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');


$module = Vtiger_Module::getInstance('Orders');

$block1 = new Vtiger_Block();
$block1 = $block1->getInstance('LBL_ORDERS_INFORMATION', $module);

$field1 = new Vtiger_Field();
$field1->label = 'LBL_ORDERS_CONTACTS';
$field1->name = 'orders_contacts';
$field1->table = 'vtiger_orders';
$field1->column = 'orders_contacts';
$field1->columntype = 'INT(19)';
$field1->uitype = 10;
$field1->typeofdata = 'V~O';

$block1->addField($field1);
$field1->setRelatedModules(array('Contacts'));
 

$block1->save($module);
