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


/**
 * Created by PhpStorm.
 * User: dbolin
 * Date: 9/6/2016
 * Time: 8:35 AM
 */

$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');

$module = Vtiger_Module::getInstance('Actuals');
$moduleVehicles = Vtiger_Module::getInstance('VehicleTransportation');
$moduleUFF = Vtiger_Module::getInstance('UpholsteryFineFinish');
$nmoduleEB = Vtiger_Module::getInstance('ExtraStops');

if ($module) {
    if ($moduleVehicles) {
        $field_vehicletrans_relcrmid = Vtiger_Field::getInstance('vehicletrans_relcrmid', $moduleVehicles);
        $field_vehicletrans_relcrmid->setRelatedModules(['Actuals']);
    }
    if ($moduleUFF) {
        $field_uff_relcrmid = Vtiger_Field::getInstance('uff_relcrmid', $moduleUFF);
        $field_uff_relcrmid->setRelatedModules(['Actuals']);
    }
    if ($nmoduleEB) {
        $field_es_relcrmid = Vtiger_Field::getInstance('extrastops_relcrmid', $nmoduleEB);
        $field_es_relcrmid->setRelatedModules(['Actuals']);
    }

    if ($moduleVehicles && $moduleUFF && $field_uff_relcrmid && $field_vehicletrans_relcrmid) {
        $db     = PearDatabase::getInstance();
        $module->setGuestBlocks('UpholsteryFineFinish', ['LBL_UPHOLSTERYFINEFINISH_INFORMATION']);
        $module->setGuestBlocks('VehicleTransportation', ['LBL_VEHICLETRANSPORTATION_INFORMATION']);
        //This probably shouldn't be here.
        //$db->pquery('UPDATE vtiger_field SET presence=? WHERE tabid=? AND fieldname=?', [1, Vtiger_Functions::getModuleId('UpholsteryFineFinish'), 'UpholsteryFineFinish_Actuals_autogeneratedlink']);
        //$db->pquery('UPDATE vtiger_field SET presence=? WHERE tabid=? AND fieldname=?', [1, Vtiger_Functions::getModuleId('VehicleTransportation'), 'VehicleTransportation_Actuals_autogeneratedlink']);
    }

    //Only runs on the $module
    $block1 = Vtiger_Block::getInstance('LBL_QUOTES_VALUATION', $module);
    if ($block1) {
        $field1 = Vtiger_Field::getInstance('additional_valuation', $module);
        if ($field1) {
            echo "<li>The additional_valuation field already exists</li><br>";
        } else {
            $field1             = new Vtiger_Field();
            $field1->label      = 'LBL_QUOTES_ADDITIONAL_VALUATION_AMT';
            $field1->name       = 'additional_valuation';
            $field1->table      = 'vtiger_quotes';  // This is the tablename from your database that the new field will be added to.
            $field1->column     = 'additional_valuation';   //  This will be the columnname in your database for the new field.
            $field1->columntype = 'decimal(22,2)';
            $field1->uitype     = 71; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
            $field1->typeofdata = 'N~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData
            $block1->addField($field1);
            /*
            $seq      = 3;
            $fieldSDO = Vtiger_Field::getInstance('storage_dateout', $moduleStorage);
            if ($fieldSDO) {
                $seq = $fieldSDO->sequence + 1;
            }
            setFieldSequenceASIBD($moduleStorage, $field1, $seq);
            */
        }
        $field1 = Vtiger_Field::getInstance('total_valuation', $module);
        if ($field1) {
            echo "<li>The total_valuation field already exists</li><br>";
        } else {
            $field1             = new Vtiger_Field();
            $field1->label      = 'LBL_QUOTES_TOTALVALUATION';
            $field1->name       = 'total_valuation';
            $field1->table      = 'vtiger_quotes';  // This is the tablename from your database that the new field will be added to.
            $field1->column     = 'total_valuation';   //  This will be the columnname in your database for the new field.
            $field1->columntype = 'decimal(22,2)';
            $field1->uitype     = 71; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
            $field1->typeofdata = 'N~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData
            $block1->addField($field1);
            /*
            $seq      = 3;
            $fieldSDO = Vtiger_Field::getInstance('storage_dateout', $moduleStorage);
            if ($fieldSDO) {
                $seq = $fieldSDO->sequence + 1;
            }
            setFieldSequenceASIBD($moduleStorage, $field1, $seq);
            */
        }
        $field1 = Vtiger_Field::getInstance('valuation_deductible_amount', $module);
        if ($field1) {
            echo "<li>The valuation_deductible_amount field already exists</li><br>";
        } else {
            $field1             = new Vtiger_Field();
            $field1->label      = 'LBL_QUOTES_VALUATIONDEDUCTIBLEAMOUNT';
            $field1->name       = 'valuation_deductible_amount';
            $field1->table      = 'vtiger_quotes';  // This is the tablename from your database that the new field will be added to.
            $field1->column     = 'valuation_deductible_amount';   //  This will be the columnname in your database for the new field.
            $field1->columntype = 'VARCHAR(100)';
            $field1->uitype     = 16; // FIND uitype here: https://wiki.vtiger.com/index.php/UI_Types
            $field1->typeofdata = 'V~O'; // Find Type of data here: https://wiki.vtiger.com/index.php/TypeOfData
            $block1->addField($field1);
            /*
            $seq      = 3;
            $fieldSDO = Vtiger_Field::getInstance('storage_dateout', $moduleStorage);
            if ($fieldSDO) {
                $seq = $fieldSDO->sequence + 1;
            }
            setFieldSequenceASIBD($moduleStorage, $field1, $seq);
            */
        }
    }
}


print "\e[94mFINISHED: " . __FILE__ . "<br />\n\e[0m";