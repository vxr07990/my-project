<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

/*********************************************************************************
 * $Header$
 * Description:  Contains a variety of utility functions used to display UI
 * components such as top level menus,more menus,header links,crm logo,global search
 * and quick links of header part
 * footer is also loaded
 * function that connect to db connector to get data
 ********************************************************************************/
abstract class Vtiger_Basic_View extends Vtiger_Footer_View
{
    public function __construct()
    {
        parent::__construct();
    }

    public function preProcess(Vtiger_Request $request, $display=true)
    {
        parent::preProcess($request, false);

        $viewer = $this->getViewer($request);

        $menuModelsList = Vtiger_Menu_Model::getAll(true);
        $selectedModule = $request->getModule();
        $menuStructure = Vtiger_MenuStructure_Model::getInstanceFromMenuList($menuModelsList, $selectedModule);

        $companyDetails = Vtiger_CompanyDetails_Model::getInstanceById();
        $companyLogo = $companyDetails->getLogo();
        $currentDate  = Vtiger_Date_UIType::getDisplayDateValue(date('Y-n-j'));
        $viewer->assign('CURRENTDATE', $currentDate);
        $viewer->assign('MODULE', $selectedModule);
        $viewer->assign('MODULE_NAME', $selectedModule);
        $viewer->assign('QUALIFIED_MODULE', $selectedModule);
        $viewer->assign('PARENT_MODULE', $request->get('parent'));
        $viewer->assign('VIEW', $request->get('view'));

        // Order by pre-defined automation process for QuickCreate.
        uksort($menuModelsList, array('Vtiger_MenuStructure_Model', 'sortMenuItemsByProcess'));

        //check if menu cleaner is on, and if it is, then use it, otherwise ignore
        $cleanerEnabled = false;
        $menuCleanerModel    = Vtiger_Module_Model::getInstance('MenuCleaner');
        if ($menuCleanerModel && $menuCleanerModel->isActive()) {
          $cleanerEnabled = true;
        }

        $viewer->assign('MENUS', $menuModelsList);
        $viewer->assign('MENU_STRUCTURE', $menuStructure);
        $viewer->assign('MENU_SELECTED_MODULENAME', $selectedModule);
        $viewer->assign('MENU_TOPITEMS_LIMIT', $menuStructure->getLimit());
        $viewer->assign('MENU_CLEANER_TOGGLE',$cleanerEnabled);
        $viewer->assign('COMPANY_LOGO', $companyLogo);
        $viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());

        $homeModuleModel = Vtiger_Module_Model::getInstance('Home');
        $viewer->assign('HOME_MODULE_MODEL', $homeModuleModel);
        $viewer->assign('HEADER_LINKS', $this->getHeaderLinks());
        $viewer->assign('ANNOUNCEMENT', $this->getAnnouncement());
        $viewer->assign('SEARCHABLE_MODULES', Vtiger_Module_Model::getSearchableModules());

        if ($display) {
            $this->preProcessDisplay($request);
        }
    }

    protected function preProcessTplName(Vtiger_Request $request)
    {
        return 'BasicHeader.tpl';
    }

    //Note: To get the right hook for immediate parent in PHP,
    // specially in case of deep hierarchy
    /*function preProcessParentTplName(Vtiger_Request $request) {
        return parent::preProcessTplName($request);
    }*/
    public function postProcess(Vtiger_Request $request)
    {
        $viewer = $this->getViewer($request);
        //$viewer->assign('GUIDERSJSON', Vtiger_Guider_Model::toJsonList($this->getGuiderModels($request)));
        parent::postProcess($request);
    }

    /**
     * Function to get the list of Script models to be included
     *
     * @param Vtiger_Request $request
     *
     * @return <Array> - List of Vtiger_JsScript_Model instances
     */
    public function getHeaderScripts(Vtiger_Request $request)
    {
        $headerScriptInstances = parent::getHeaderScripts($request);
        $moduleName            = $request->getModule();
        $jsFileNames           = [
            'libraries.bootstrap.js.eternicode-bootstrap-datepicker.js.bootstrap-datepicker',
            '~libraries/bootstrap/js/eternicode-bootstrap-datepicker/js/locales/bootstrap-datepicker.'.Vtiger_Language_Handler::getShortLanguageName().'.js',
            '~libraries/jquery/timepicker/jquery.timepicker.min.js',
            '~libraries/jquery/datetimepicker/bootstrap-datetimepicker.min.js',
            'modules.Vtiger.resources.Header',
            'modules.Vtiger.resources.Edit',
            'modules.Vtiger.resources.ZipAutoFill',
            'modules.Vtiger.resources.EditBlock',
            'modules.Vtiger.resources.Popup',
            "modules.$moduleName.resources.Popup",
            'modules.Vtiger.resources.Field',
            "modules.$moduleName.resources.Field",
            'modules.Vtiger.resources.validator.BaseValidator',
            'modules.Vtiger.resources.validator.FieldValidator',
            "modules.$moduleName.resources.validator.FieldValidator",
            'libraries.jquery.jquery_windowmsg',
            'modules.Vtiger.resources.BasicSearch',
            "modules.$moduleName.resources.BasicSearch",
            'modules.Vtiger.resources.AdvanceFilter',
            "modules.$moduleName.resources.AdvanceFilter",
            'modules.Vtiger.resources.SearchAdvanceFilter',
            "modules.$moduleName.resources.SearchAdvanceFilter",
            'modules.Vtiger.resources.AdvanceSearch',
            "modules.$moduleName.resources.AdvanceSearch",
        ];

        $moduleLeadCompanyLookupInstance = Vtiger_Module_Model::getInstance('LeadCompanyLookup');
        if ($moduleLeadCompanyLookupInstance && $moduleLeadCompanyLookupInstance->isActive()) {
            $jsFileNames[] = "modules.LeadCompanyLookup.resources.LeadCompanyLookup";
	    }
        $RelatedRecordCountModel    = Vtiger_Module_Model::getInstance('RelatedRecordCount');
        if ($RelatedRecordCountModel && $RelatedRecordCountModel->isActive()) {
            $jsFileNames[] =  "modules.RelatedRecordCount.resources.RelatedRecordCount";
	    }
        $ListviewColorsModel    = Vtiger_Module_Model::getInstance('ListviewColors');
        if ($ListviewColorsModel && $ListviewColorsModel->isActive()) {
            $jsFileNames[] =  "modules.ListviewColors.resources.ListviewColors";
        }

        $moduleTooltipManagerInstance = Vtiger_Module_Model::getInstance('TooltipManager');
        if ($moduleTooltipManagerInstance && $moduleTooltipManagerInstance->isActive()) {
            $jsFileNames[] = "~/layouts/vlayout/modules/TooltipManager/resources/TooltipManager.js";
            $jsFileNames[] = "~/layouts/vlayout/modules/TooltipManager/resources/jquery.url.js";
            $jsFileNames[] = "~/layouts/vlayout/modules/TooltipManager/resources/jquery.qtip-1.0.0-rc3.min.js";
        }

        $DataExportTrackingModel    = Vtiger_Module_Model::getInstance('DataExportTracking');
        if ($DataExportTrackingModel && $DataExportTrackingModel->isActive()) {
            $jsFileNames[] =  "modules.DataExportTracking.resources.DataExportTracking";
        }

        $moduleNotifications = Vtiger_Module_Model::getInstance('Notifications');
        if ($moduleNotifications && $moduleNotifications->isActive()) {
            $jsFileNames[] = "modules.Notifications.resources.NotificationsJS";
        }

        $jsScriptInstances     = $this->checkAndConvertJsScripts($jsFileNames);
        $headerScriptInstances = array_merge($jsScriptInstances, $headerScriptInstances);

        return $headerScriptInstances;
    }

    public function getHeaderCss(Vtiger_Request $request)
    {
        $headerCssInstances = parent::getHeaderCss($request);
        $cssFileNames       = [
            '~/libraries/jquery/timepicker/jquery.timepicker.css',
            '~/libraries/jquery/datetimepicker/bootstrap-datetimepicker.min.css',
        ];

        $VTEFavoriteModel    = Vtiger_Module_Model::getInstance('VTEFavorite');
        if ($VTEFavoriteModel && $VTEFavoriteModel->isActive()) {
            $cssFileNames[] =  '~layouts/vlayout/modules/VTEFavorite/resources/rating.css';
        }

        $NotificationsModel    = Vtiger_Module_Model::getInstance('Notifications');
        if ($NotificationsModel && $NotificationsModel->isActive()) {
            $cssFileNames[] =  '~layouts/vlayout/modules/Notifications/resources/NotificationsCSS.css';
        }

        $cssInstances       = $this->checkAndConvertCssStyles($cssFileNames);
        $headerCssInstances = array_merge($headerCssInstances, $cssInstances);

        return $headerCssInstances;
    }

    public function getGuiderModels(Vtiger_Request $request)
    {
        return [];
    }
}
