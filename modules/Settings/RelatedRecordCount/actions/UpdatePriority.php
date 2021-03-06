<?php
/* ********************************************************************************
 * The content of this file is subject to the Related Record Count ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */
 
class Settings_RelatedRecordCount_UpdatePriority_Action extends Vtiger_Action_Controller {

	public function checkPermission(Vtiger_Request $request) {
		return true;
	}

	public function process(Vtiger_Request $request) {
        $settingModel = new Settings_RelatedRecordCount_Settings_Model();
        $result = $settingModel->updatePriority($request);

        $response = new Vtiger_Response();
        $response->setResult($result);
        $response->emit();
	}	
}
