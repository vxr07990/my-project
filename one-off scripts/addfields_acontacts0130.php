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



// Make sure to give your file a descriptive name and place in the root of your installation.  Then access the appropriate URL in a browser.

// Turn on debugging level
$Vtiger_Utils_Log = true;
// Need these files
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');

$module = Vtiger_Module::getInstance('AgentContacts'); // The module your blocks and fields will be in.
$block3 = new Vtiger_Block();
$block3->label = 'LBL_AGENTCONTACTS_ADDRESS';
$module->addBlock($block3);

$module = Vtiger_Module::getInstance('AgentContacts'); // The module your blocks and fields will be in.
$block4 = new Vtiger_Block();
$block4->label = 'LBL_AGENTCONTACTS_DESCRIPTION';
$module->addBlock($block4);

// To use a pre-existing block
 $module = Vtiger_Module::getInstance('AgentContacts'); // The module your blocks and fields will be in.
 $block1 = Vtiger_Block::getInstance('LBL_AGENTCONTACTS_INFORMATION', $module);  // Must be the actual instance name, not just what appears in the browser.

$field2 = new Vtiger_Field();
$field2->label = 'LBL_AGENTCONTACTS_P1';
$field2->name = 'acontacts_p3';
$field2->table = 'vtiger_agentcontacts';  // This is the tablename from your database that the new field will be added to.
$field2->column = 'acontacts_p3';   //  This will be the columnname in your database for the new field.
$field2->columntype = 'INT(20)';
$field2->uitype = 7; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
$field2->typeofdata = 'I~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData

$block1->addField($field2);

$field3 = new Vtiger_Field();
$field3->label = 'LBL_AGENTCONTACTS_P2';
$field3->name = 'acontacts_p2';
$field3->table = 'vtiger_agentcontacts';  // This is the tablename from your database that the new field will be added to.
$field3->column = 'acontacts_p2';   //  This will be the columnname in your database for the new field.
$field3->columntype = 'INT(20)';
$field3->uitype = 7; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
$field3->typeofdata = 'I~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData

$block1->addField($field3);


$field5 = new Vtiger_Field();
$field5->label = 'LBL_AGENTCONTACTS_EMAIL1';
$field5->name = 'acontacts_email1';
$field5->table = 'vtiger_agentcontacts';  // This is the tablename from your database that the new field will be added to.
$field5->column = 'acontacts_email1';   //  This will be the columnname in your database for the new field.
$field5->columntype = 'VARCHAR(100)';
$field5->uitype = 13; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
$field5->typeofdata = 'V~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData

$block1->addField($field5);

$field6 = new Vtiger_Field();
$field6->label = 'LBL_AGENTCONTACTS_FAX';
$field6->name = 'acontacts_fax';
$field6->table = 'vtiger_agentcontacts';  // This is the tablename from your database that the new field will be added to.
$field6->column = 'acontacts_fax';   //  This will be the columnname in your database for the new field.
$field6->columntype = 'INT(20)';
$field6->uitype = 7; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
$field6->typeofdata = 'I~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData

$block1->addField($field6);


$field10 = new Vtiger_Field();
$field10->label = 'LBL_AGENTCONTACTS_SEMAIL';
$field10->name = 'acontacts_semail';
$field10->table = 'vtiger_agentcontacts';  // This is the tablename from your database that the new field will be added to.
$field10->column = 'acontacts_semail';   //  This will be the columnname in your database for the new field.
$field10->columntype = 'VARCHAR(100)';
$field10->uitype = 13; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
$field10->typeofdata = 'V~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData

$block1->addField($field10);

$field11 = new Vtiger_Field();
$field11->label = 'LBL_AGENTCONTACTS_TITLE';
$field11->name = 'acontacts_title';
$field11->table = 'vtiger_agentcontacts';  // This is the tablename from your database that the new field will be added to.
$field11->column = 'acontacts_title';   //  This will be the columnname in your database for the new field.
$field11->columntype = 'VARCHAR(100)';
$field11->uitype = 1; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
$field11->typeofdata = 'V~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData

$block1->addField($field11);

$field12 = new Vtiger_Field();
$field12->label = 'LBL_AGENTCONTACTS_DEPARTMENT';
$field12->name = 'acontacts_dept';
$field12->table = 'vtiger_agentcontacts';  // This is the tablename from your database that the new field will be added to.
$field12->column = 'acontacts_dept';   //  This will be the columnname in your database for the new field.
$field12->columntype = 'VARCHAR(100)';
$field12->uitype = 1; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
$field12->typeofdata = 'V~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData

$block1->addField($field12);

$block1->save($module);

$field16 = new Vtiger_Field();
$field16->label = 'LBL_AGENTCONTACTS_ADDRESS2';
$field16->name = 'acontacts_address2';
$field16->table = 'vtiger_agentcontacts';  // This is the tablename from your database that the new field will be added to.
$field16->column = 'acontacts_address2';   //  This will be the columnname in your database for the new field.
$field16->columntype = 'VARCHAR(100)';
$field16->uitype = 1; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
$field16->typeofdata = 'V~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData

$block3->addField($field16);
 
$field17 = new Vtiger_Field();
$field17->label = 'LBL_AGENTCONTACTS_CITY';
$field17->name = 'acontacts_city';
$field17->table = 'vtiger_agentcontacts';  // This is the tablename from your database that the new field will be added to.
$field17->column = 'acontacts_city';   //  This will be the columnname in your database for the new field.
$field17->columntype = 'VARCHAR(100)';
$field17->uitype = 1; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
$field17->typeofdata = 'V~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData

$block3->addField($field17);

$field18 = new Vtiger_Field();
$field18->label = 'LBL_AGENTCONTACTS_STATE';
$field18->name = 'acontacts_state';
$field18->table = 'vtiger_agentcontacts';  // This is the tablename from your database that the new field will be added to.
$field18->column = 'acontacts_state';   //  This will be the columnname in your database for the new field.
$field18->columntype = 'VARCHAR(100)';
$field18->uitype = 1; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
$field18->typeofdata = 'V~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData

$block3->addField($field18);

$field19 = new Vtiger_Field();
$field19->label = 'LBL_AGENTCONTACTS_ZIP';
$field19->name = 'acontacts_zip';
$field19->table = 'vtiger_agentcontacts';  // This is the tablename from your database that the new field will be added to.
$field19->column = 'acontacts_zip';   //  This will be the columnname in your database for the new field.
$field19->columntype = 'INT(20)';
$field19->uitype = 1; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
$field19->typeofdata = 'V~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData

$block3->addField($field19);

$field20 = new Vtiger_Field();
$field20->label = 'LBL_AGENTCONTACTS_COUNTRY';
$field20->name = 'acontacts_country';
$field20->table = 'vtiger_agentcontacts';  // This is the tablename from your database that the new field will be added to.
$field20->column = 'acontacts_country';   //  This will be the columnname in your database for the new field.
$field20->columntype = 'VARCHAR(100)';
$field20->uitype = 1; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
$field20->typeofdata = 'V~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData

$block3->addField($field20);

$field21 = new Vtiger_Field();
$field21->label = 'LBL_AGENTCONTACTS_SADDRESS1';
$field21->name = 'acontacts_saddress1';
$field21->table = 'vtiger_agentcontacts';  // This is the tablename from your database that the new field will be added to.
$field21->column = 'acontacts_saddress1';   //  This will be the columnname in your database for the new field.
$field21->columntype = 'VARCHAR(100)';
$field21->uitype = 1; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
$field21->typeofdata = 'V~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData

$block3->addField($field21);

$field22 = new Vtiger_Field();
$field22->label = 'LBL_AGENTCONTACTS_SADDRESS2';
$field22->name = 'acontacts_saddress2';
$field22->table = 'vtiger_agentcontacts';  // This is the tablename from your database that the new field will be added to.
$field22->column = 'acontacts_saddress2';   //  This will be the columnname in your database for the new field.
$field22->columntype = 'VARCHAR(100)';
$field22->uitype = 1; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
$field22->typeofdata = 'V~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData

$block3->addField($field22);
 
$field23 = new Vtiger_Field();
$field23->label = 'LBL_AGENTCONTACTS_SCITY';
$field23->name = 'acontacts_scity';
$field23->table = 'vtiger_agentcontacts';  // This is the tablename from your database that the new field will be added to.
$field23->column = 'acontacts_scity';   //  This will be the columnname in your database for the new field.
$field23->columntype = 'VARCHAR(100)';
$field23->uitype = 1; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
$field23->typeofdata = 'V~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData

$block3->addField($field23);

$field24 = new Vtiger_Field();
$field24->label = 'LBL_AGENTCONTACTS_SSTATE';
$field24->name = 'acontacts_sstate';
$field24->table = 'vtiger_agentcontacts';  // This is the tablename from your database that the new field will be added to.
$field24->column = 'acontacts_sstate';   //  This will be the columnname in your database for the new field.
$field24->columntype = 'VARCHAR(100)';
$field24->uitype = 1; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
$field24->typeofdata = 'V~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData

$block3->addField($field24);

$field25 = new Vtiger_Field();
$field25->label = 'LBL_AGENTCONTACTS_SZIP';
$field25->name = 'acontacts_szip';
$field25->table = 'vtiger_agentcontacts';  // This is the tablename from your database that the new field will be added to.
$field25->column = 'acontacts_szip';   //  This will be the columnname in your database for the new field.
$field25->columntype = 'VARCHAR(100)';
$field25->uitype = 1; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
$field25->typeofdata = 'V~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData

$block3->addField($field25);

$field26 = new Vtiger_Field();
$field26->label = 'LBL_AGENTCONTACTS_SCOUNTRY';
$field26->name = 'acontacts_scountry';
$field26->table = 'vtiger_agentcontacts';  // This is the tablename from your database that the new field will be added to.
$field26->column = 'acontacts_scountry';   //  This will be the columnname in your database for the new field.
$field26->columntype = 'VARCHAR(100)';
$field26->uitype = 1; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
$field26->typeofdata = 'V~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData

$block3->addField($field26);

$block3->save($module);

$field27 = new Vtiger_Field(); // needs to bechanged not saving data
$field27->label = 'LBL_AGENTCONTACTS_DESCRIPTION';
$field27->name = 'description';
$field27->table = 'vtiger_crmentity';  // This is the tablename from your database that the new field will be added to.
$field27->column = 'description';   //  This will be the columnname in your database for the new field.
$field27->columntype = 'VARCHAR(100)';
$field27->uitype = 19; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
$field27->typeofdata = 'V~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData

$block4->addField($field27);

$block4->save($module);
