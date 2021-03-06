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
 * Date: 9/28/2016
 * Time: 9:48 AM
 */

echo __FILE__.PHP_EOL;

include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');

$db = PearDatabase::getInstance();

$stmt = 'SELECT * FROM information_schema.columns WHERE table_schema = "'.getenv('DB_NAME').'" 
            AND table_name = "dli_service_providers" AND column_name = "split_miles" LIMIT 1';
$res = $db->pquery($stmt);
if ($db->num_rows($res) > 0) {
    echo 'dli_service_providers split_miles column already exists'.PHP_EOL;
} else {
    $stmt = 'ALTER TABLE `dli_service_providers` ADD COLUMN `split_miles` INT(10) DEFAULT NULL';
    $db->pquery($stmt);
}

$stmt = 'SELECT * FROM information_schema.columns WHERE table_schema = "'.getenv('DB_NAME').'" 
            AND table_name = "dli_service_providers" AND column_name = "split_weight" LIMIT 1';
$res = $db->pquery($stmt);
if ($db->num_rows($res) > 0) {
    echo 'dli_service_providers split_weight column already exists'.PHP_EOL;
} else {
    $stmt = 'ALTER TABLE `dli_service_providers` ADD COLUMN `split_weight` INT(10) DEFAULT NULL';
    $db->pquery($stmt);
}

$stmt = 'SELECT * FROM information_schema.columns WHERE table_schema = "'.getenv('DB_NAME').'" 
            AND table_name = "dli_service_providers" AND column_name = "split_percent" LIMIT 1';
$res = $db->pquery($stmt);
if ($db->num_rows($res) > 0) {
    echo 'dli_service_providers split_percent column already exists'.PHP_EOL;
} else {
    $stmt = 'ALTER TABLE `dli_service_providers` ADD COLUMN `split_percent` DECIMAL(9,2) DEFAULT NULL';
    $db->pquery($stmt);
}


print "\e[94mFINISHED: " . __FILE__ . "<br />\n\e[0m";