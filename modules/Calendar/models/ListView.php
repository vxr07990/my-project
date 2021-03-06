<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

/**
 * Vtiger ListView Model Class
 */
class Calendar_ListView_Model extends Vtiger_ListView_Model
{
    public function getBasicLinks()
    {
        $basicLinks = array();
        $moduleModel = $this->getModule();
        $createPermission = Users_Privileges_Model::isPermitted($moduleModel->getName(), 'EditView');
        if ($createPermission) {
            $basicLinks[] = array(
                    'linktype' => 'LISTVIEWBASIC',
                    'linklabel' => 'LBL_ADD_TASK',
                    'linkurl' => $this->getModule()->getCreateTaskRecordUrl(),
                    'linkicon' => ''
            );

            $basicLinks[] = array(
                    'linktype' => 'LISTVIEWBASIC',
                    'linklabel' => 'LBL_ADD_EVENT',
                    'linkurl' => $this->getModule()->getCreateEventRecordUrl(),
                    'linkicon' => ''
            );
        }
        return $basicLinks;
    }


    /*
     * Function to give advance links of a module
     *	@RETURN array of advanced links
     */
    public function getAdvancedLinks()
    {
        $moduleModel = $this->getModule();
        $createPermission = Users_Privileges_Model::isPermitted($moduleModel->getName(), 'EditView');
        $advancedLinks = array();
        $importPermission = Users_Privileges_Model::isPermitted($moduleModel->getName(), 'Import');
        if ($importPermission && $createPermission) {
            $advancedLinks[] = array(
                            'linktype' => 'LISTVIEW',
                            'linklabel' => 'LBL_IMPORT',
                            'linkurl' => 'javascript:Calendar_List_Js.triggerImportAction("'.$moduleModel->getImportUrl().'")',
                            'linkicon' => ''
            );
        }

        $exportPermission = Users_Privileges_Model::isPermitted($moduleModel->getName(), 'Export');
        if ($exportPermission) {
            $advancedLinks[] = array(
                    'linktype' => 'LISTVIEW',
                    'linklabel' => 'LBL_EXPORT',
                    'linkurl' => 'javascript:Calendar_List_Js.triggerExportAction("'.$this->getModule()->getExportUrl().'")',
                    'linkicon' => ''
                );
        }
        return $advancedLinks;
    }

    /**
     * Function to get query to get List of records in the current page
     * @return <String> query
     */
    public function getQuery()
    {
        global $current_user;
        $queryGenerator = $this->get('query_generator');
        // Added to remove emails from the calendar list
        $queryGenerator->addCondition('activitytype', 'Emails', 'n', 'AND');
        $listQuery = $queryGenerator->getQuery();
        $userRecordModel = Users_Record_Model::getCurrentUserModel();

        //OT4601 - Make To Do accessible to lower levels as readonly 
        $accesibleAgents =  $userRecordModel->getAccessibleOwnersForUser('Calendar', true, true); 
        unset($accesibleAgents['agents']);
        unset($accesibleAgents['vanlines']);
        $members = getMembersByRecord(array_keys($accesibleAgents));

        $groups = fetchUserGroupids($current_user->id);
        if ($members) {
            $ownerList = '('.implode(',', $members).')';
            $listQuery .= " AND (vtiger_crmentity.smownerid IN $ownerList";
            if($groups)
            {
                $listQuery .= " OR vtiger_crmentity.smownerid IN ($groups)";
            }
            $listQuery .= ')';
        }
        //file_put_contents('logs/devLog.log', "\n CAL LISTVIEW LIST QUERY $listQuery", FILE_APPEND);
        return $listQuery;
    }


    /**
     * Function to get the list of Mass actions for the module
     * @param <Array> $linkParams
     * @return <Array> - Associative array of Link type to List of  Vtiger_Link_Model instances for Mass Actions
     */
    public function getListViewMassActions($linkParams)
    {
        $currentUserModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
        $moduleModel = $this->getModule();

        $linkTypes = array('LISTVIEWMASSACTION');
        $links = Vtiger_Link_Model::getAllByType($moduleModel->getId(), $linkTypes, $linkParams);


        $massActionLinks = array();
        if ($currentUserModel->hasModuleActionPermission($moduleModel->getId(), 'EditView')) {
            $massActionLinks[] = array(
                'linktype' => 'LISTVIEWMASSACTION',
                'linklabel' => 'LBL_CHANGE_OWNER',
                'linkurl' => 'javascript:Calendar_List_Js.triggerMassEdit("index.php?module='.$moduleModel->get('name').'&view=MassActionAjax&mode=showMassEditForm");',
                'linkicon' => ''
            );
        }
        if ($currentUserModel->hasModuleActionPermission($moduleModel->getId(), 'Delete')) {
            $massActionLinks[] = array(
                'linktype' => 'LISTVIEWMASSACTION',
                'linklabel' => 'LBL_DELETE',
                'linkurl' => 'javascript:Vtiger_List_Js.massDeleteRecords("index.php?module='.$moduleModel->get('name').'&action=MassDelete");',
                'linkicon' => ''
            );
        }

        $massActionLinks[] = array(
            'linktype' => 'LISTVIEWMASSACTION',
            'linklabel' => 'LBL_EDITFILTER',
            'linkurl' => 'javascript:triggerEditFilter()',
            'linkicon' => ''
        );

        $massActionLinks[] = array(
            'linktype' => 'LISTVIEWMASSACTION',
            'linklabel' => 'LBL_DELETEFILTER',
            'linkurl' => 'javascript:triggerDeleteFilter()',
            'linkicon' => ''
        );

        $massActionLinks[] = array(
            'linktype' => 'LISTVIEWMASSACTION',
            'linklabel' => 'LBL_CREATEFILTER',
            'linkurl' => 'javascript:triggerCreateFilter()',
            'linkicon' => ''
        );

        foreach ($massActionLinks as $massActionLink) {
            $links['LISTVIEWMASSACTION'][] = Vtiger_Link_Model::getInstanceFromValues($massActionLink);
        }

        return $links;
    }
    
    /**
     * Function to get the list view header
     * @return <Array> - List of Vtiger_Field_Model instances
     */
    public function getListViewHeaders()
    {
        $listViewContoller = $this->get('listview_controller');
        $module = $this->getModule();
        $moduleName = $module->get('name');
        $headerFieldModels = array();
        $headerFields = $listViewContoller->getListViewHeaderFields();
        foreach ($headerFields as $fieldName => $webserviceField) {
            if ($webserviceField && !in_array($webserviceField->getPresence(), array(0, 2))) {
                continue;
            }
            $fieldInstance = Vtiger_Field_Model::getInstance($fieldName, $module);
            if (!$fieldInstance) {
                if ($moduleName == 'Calendar') {
                    $eventmodule = Vtiger_Module_Model::getInstance('Events');
                    $fieldInstance = Vtiger_Field_Model::getInstance($fieldName, $eventmodule);
                }
            }
            $headerFieldModels[$fieldName] = $fieldInstance;
        }
        return $headerFieldModels;
    }
    
    /**
     * Function to get the list view entries
     * @param Vtiger_Paging_Model $pagingModel
     * @return <Array> - Associative array of record id mapped to Vtiger_Record_Model instance.
     */
    public function getListViewEntries($pagingModel)
    {
        $db = PearDatabase::getInstance();

        $moduleName = $this->getModule()->get('name');
        $moduleFocus = CRMEntity::getInstance($moduleName);
        $moduleModel = Vtiger_Module_Model::getInstance($moduleName);
        $currentUser = Users_Record_Model::getCurrentUserModel();
        
        $queryGenerator = $this->get('query_generator');
        $listViewContoller = $this->get('listview_controller');
        $listViewFields = array('visibility','assigned_user_id');
        $queryGenerator->setFields(array_unique(array_merge($queryGenerator->getFields(), $listViewFields)));
        
        $searchParams = $this->get('search_params');
        $specialLimit = true;
        if (empty($searchParams)) {
            $searchParams = array();
        }
        foreach($searchParams as $s)
        {
            foreach($s['columns'] as $c)
            {
                if(strpos('date_start', $c['columnname']) !== false)
                {
                    $specialLimit = false;
                } else if(strpos('due_date', $c['columnname']) !== false)
                {
                    $specialLimit = false;
                }
            }
        }
        
        $glue = "";
        if (count($queryGenerator->getWhereFields()) > 0 && (count($searchParams)) > 0) {
            $glue = QueryGenerator::$AND;
        }
        $queryGenerator->parseAdvFilterList($searchParams, $glue);

        $searchKey = $this->get('search_key');
        $searchValue = $this->get('search_value');
        $operator = $this->get('operator');
        if (!empty($searchKey)) {
            $queryGenerator->addUserSearchConditions(array('search_field' => $searchKey, 'search_text' => $searchValue, 'operator' => $operator));
        }
        
        $orderBy = $this->getForSql('orderby');
        $sortOrder = $this->getForSql('sortorder');

        //List view will be displayed on recently created/modified records
        if (empty($orderBy) && empty($sortOrder) && $moduleName != "Users") {
            $orderBy = 'modifiedtime';
            $sortOrder = 'DESC';
        }

        if (!empty($orderBy)) {
            $columnFieldMapping = $moduleModel->getColumnFieldMapping();
            $orderByFieldName = $columnFieldMapping[$orderBy];
            $orderByFieldModel = $moduleModel->getField($orderByFieldName);
            if ($orderByFieldModel && $orderByFieldModel->getFieldDataType() == Vtiger_Field_Model::REFERENCE_TYPE) {
                //IF it is reference add it in the where fields so that from clause will be having join of the table
                $queryGenerator = $this->get('query_generator');
                $queryGenerator->addWhereField($orderByFieldName);
                //$queryGenerator->whereFields[] = $orderByFieldName;
            }
        }
        if (!empty($orderBy) && $orderBy === 'smownerid') {
            $fieldModel = Vtiger_Field_Model::getInstance('assigned_user_id', $moduleModel);
            if ($fieldModel->getFieldDataType() == 'owner') {
                $orderBy = 'COALESCE(CONCAT(vtiger_users.first_name,vtiger_users.last_name),vtiger_groups.groupname)';
            }
        }
        //To combine date and time fields for sorting
        if ($orderBy == 'date_start') {
            $orderBy = "str_to_date(concat(date_start,time_start),'%Y-%m-%d %H:%i:%s')";
        } elseif ($orderBy == 'due_date') {
            $orderBy = "str_to_date(concat(due_date,time_end),'%Y-%m-%d %H:%i:%s')";
        }

        $listQuery = $this->getQuery();
        if($specialLimit)
        {
            $listQuery .= ' AND (
                                vtiger_activity.date_start 
                                    BETWEEN DATE_SUB(NOW(), INTERVAL 10 DAY) and DATE_ADD(NOW(), INTERVAL 20 DAY)
                                OR vtiger_activity.due_date
                                    BETWEEN DATE_SUB(NOW(), INTERVAL 15 DAY) and DATE_ADD(NOW(), INTERVAL 15 DAY)
                                )';
        }

        $sourceModule = $this->get('src_module');
        if (!empty($sourceModule)) {
            if (method_exists($moduleModel, 'getQueryByModuleField')) {
                $overrideQuery = $moduleModel->getQueryByModuleField($sourceModule, $this->get('src_field'), $this->get('src_record'), $listQuery);
                if (!empty($overrideQuery)) {
                    $listQuery = $overrideQuery;
                }
            }
        }

        $startIndex = $pagingModel->getStartIndex();
        $pageLimit = $pagingModel->getPageLimit();



        if (!empty($orderBy)) {
            if ($orderByFieldModel && $orderByFieldModel->isReferenceField()) {
                $referenceModules = $orderByFieldModel->getReferenceList();
                $referenceNameFieldOrderBy = array();
                foreach ($referenceModules as $referenceModuleName) {
                    $referenceModuleModel = Vtiger_Module_Model::getInstance($referenceModuleName);
                    $referenceNameFields = $referenceModuleModel->getNameFields();

                    $columnList = array();
                    foreach ($referenceNameFields as $nameField) {
                        $fieldModel = $referenceModuleModel->getField($nameField);
                        $columnList[] = $fieldModel->get('table').$orderByFieldModel->getName().'.'.$fieldModel->get('column');
                    }
                    if (count($columnList) > 1) {
                        $referenceNameFieldOrderBy[] = getSqlForNameInDisplayFormat(array('first_name'=>$columnList[0], 'last_name'=>$columnList[1]), 'Users').' '.$sortOrder;
                    } else {
                        $referenceNameFieldOrderBy[] = implode('', $columnList).' '.$sortOrder ;
                    }
                }
                $listQuery .= ' ORDER BY '. implode(',', $referenceNameFieldOrderBy);
            } else {
                $listQuery .= ' ORDER BY '. $orderBy . ' ' .$sortOrder;
            }
        }

        $viewid = ListViewSession::getCurrentView($moduleName);
        if (empty($viewid)) {
            $viewid = $pagingModel->get('viewid');
        }
        $_SESSION['lvs'][$moduleName][$viewid]['start'] = $pagingModel->get('page');
        ListViewSession::setSessionQuery($moduleName, $listQuery, $viewid);

        $listQueryWithNoLimit = $listQuery;
        $listQuery .= " LIMIT $startIndex,".($pageLimit+1);

        $listResult = $db->pquery($listQuery, array());

        $listViewRecordModels = array();
        $listViewEntries =  $listViewContoller->getListViewRecords($moduleFocus, $moduleName, $listResult);

        $pagingModel->calculatePageRange($listViewEntries);

        if ($db->num_rows($listResult) > $pageLimit) {
            array_pop($listViewEntries);
            $pagingModel->set('nextPageExists', true);
        } else {
            $pagingModel->set('nextPageExists', false);
        }
        
        $groupsIds = Vtiger_Util_Helper::getGroupsIdsForUsers($currentUser->getId());
        $index = 0;
        foreach ($listViewEntries as $recordId => $record) {
            $rawData = $db->query_result_rowdata($listResult, $index++);
            $record['id'] = $recordId;
            $listViewRecordModels[$recordId] = $moduleModel->getRecordFromArray($record, $rawData);
        }
        
        return $listViewRecordModels;
    }

    /**
     * Function to get the list view entries
     * @param Vtiger_Paging_Model $pagingModel
     * @return <Array> - Associative array of record id mapped to Vtiger_Record_Model instance.
     */
    public function getListViewCount()
    {
        $db =& PearDatabase::getInstance();

        $queryGenerator = $this->get('query_generator');


        $searchParams = $this->get('search_params');
        $specialLimit = true;
        if (empty($searchParams)) {
            $searchParams = array();
        }
        foreach($searchParams as $s)
        {
            foreach($s['columns'] as $c)
            {
                if(strpos('date_start', $c['columnname']) !== false)
                {
                    $specialLimit = false;
                } else if(strpos('due_date', $c['columnname']) !== false)
                {
                    $specialLimit = false;
                }
            }
        }

        $glue = "";
        if (count($queryGenerator->getWhereFields()) > 0 && (count($searchParams)) > 0) {
            $glue = QueryGenerator::$AND;
        }
        $queryGenerator->parseAdvFilterList($searchParams, $glue);

        $searchKey = $this->get('search_key');
        $searchValue = $this->get('search_value');
        $operator = $this->get('operator');
        if (!empty($searchKey)) {
            $queryGenerator->addUserSearchConditions(array('search_field' => $searchKey, 'search_text' => $searchValue, 'operator' => $operator));
        }
        $moduleName = $this->getModule()->get('name');
        $moduleModel = Vtiger_Module_Model::getInstance($moduleName);

        $listQuery = $this->getQuery();
        if($specialLimit)
        {
            $listQuery .= ' AND (
                                vtiger_activity.date_start 
                                    BETWEEN DATE_SUB(NOW(), INTERVAL 10 DAY) and DATE_ADD(NOW(), INTERVAL 20 DAY)
                                OR vtiger_activity.due_date
                                    BETWEEN DATE_SUB(NOW(), INTERVAL 15 DAY) and DATE_ADD(NOW(), INTERVAL 15 DAY)
                                )';
        }

        $sourceModule = $this->get('src_module');
        if (!empty($sourceModule)) {
            $moduleModel = $this->getModule();
            if (method_exists($moduleModel, 'getQueryByModuleField')) {
                $overrideQuery = $moduleModel->getQueryByModuleField($sourceModule, $this->get('src_field'), $this->get('src_record'), $listQuery);
                if (!empty($overrideQuery)) {
                    $listQuery = $overrideQuery;
                }
            }
        }
        $position = stripos($listQuery, ' from ');
        if ($position) {
            $split = spliti(' from ', $listQuery);
            $splitCount = count($split);
            $listQuery = 'SELECT count(*) AS count ';
            for ($i=1; $i<$splitCount; $i++) {
                $listQuery = $listQuery. ' FROM ' .$split[$i];
            }
        }

        if ($this->getModule()->get('name') == 'Calendar') {
            $listQuery .= ' AND activitytype <> "Emails"';
        }

        $listResult = $db->pquery($listQuery, array());

        // not sure if this is the right thing to do here
        if (strpos($listQuery, 'GROUP BY ') === false) {
            $queryResult = $db->query_result($listResult, 0, 'count');
        } else {
            $queryResult = $db->num_rows($listResult);
        }
        return $queryResult;
    }



    public function getSideBarLinks($linkParams)
    {
        $res = parent::getSideBarLinks($linkParams);
        foreach ($res['SIDEBARWIDGET'] as $key => $widget) {
            if ($widget->linklabel == 'Google Calendar') {
                unset($res['SIDEBARWIDGET'][$key]);
                continue;
            }
            if ($widget->linklabel == 'LBL_CALENDAR_EXCHANGESYNC') {
                unset($res['SIDEBARWIDGET'][$key]);
            }
        }
        return $res;
    }
}
