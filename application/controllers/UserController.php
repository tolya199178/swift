<?php

class UserController extends Swift_Controller_Action {

    public function init() {
        if (!$this->_me) {
            $this->_redirect("/");
        }
    }

    public function trackAction() {
        $locationsModel = new Locations;
        $this->view->locations = $locationsModel->getAll();

        $employeesModel = new Employees;
        if ($this->_me->admin == 1) {
            $this->view->employees = $employeesModel->getAll();
        } else {
            $this->view->employees = $employeesModel->getAllByLocation($this->_me->location);
        }

        $substitutesModel = new Substitutes;
        if ($this->_me->admin == 1) {
            $this->view->substitutes = $substitutesModel->getAll();
        } else {
            //$this->view->substitutes = $substitutesModel->getAllByLocation($this->_me->location);
            $this->view->substitutes = $substitutesModel->getAll();
        }

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            if ($this->_me->admin == 1) {
                $emp = $employeesModel->getById($data['employee']);
                $data['location'] = $emp->location;
            }

            $data['user'] = $this->_me->id;
            $data['date_created'] = date('Y-m-d h:i:s', strtotime('now'));

            $start_day = date('l', strtotime($data['date']));
            if ($start_day <> 'Saturday' && $start_day <> 'Sunday') {
                $days = $data['days'];
                unset($data['days']);

                // payrate
                $subs = $substitutesModel->getById($data['substitute']);
                if ($subs) {
                    $data['payrate'] = $subs->payrate;
                }

                $recordsModel = new Records;


                if ($recordsModel->insert($data)) {

                    if ($days > 1) {
                        $i = 1;

                        $cnt_day = 0;
                        $startdate = $data['date'];
                        while ($i < $days) {
                            $dayName = date('l', strtotime($startdate . " + $i days"));
                            $c_day = 0;

                            //omit weekends
                            if ($dayName == 'Saturday') { // + 2 dias
                                $cnt_day = 2;
                            }

                            if ($dayName == 'Sunday') {// + 1 dias
                                $cnt_day = 2;
                            }

                            $c_day = $cnt_day + $i;

                            $data['date'] = date('Y-m-d', strtotime($startdate . " + $c_day days"));
                            $recordsModel->insert($data);

                            $i++;
                        }
                    }

                    $this->view->success = "Leave Record Saved.";
                } else {
                    $this->view->error = "An Error Occurred.";
                }
            } else {
                $this->view->error = "Saturday and Sunday are not working days, please select other day.";
            }
        }
    }

    public function reportsAction() {
        $locationsModel = new Locations;
        $this->view->locations = $locationsModel->getAll();

        $employeesModel = new Employees;
        if ($this->_me->admin == 1) {
            $this->view->employees = $employeesModel->getAll();
        } else {
            $this->view->employees = $employeesModel->getAllByLocation($this->_me->location);
        }
        $recordsModel = new Records;
        $cols = $recordsModel->info(Zend_Db_Table_Abstract::COLS);

        $this->view->columns = $cols;

        $columnValues = array();
        foreach ($cols AS $col) {
            if ($col <> 'id' && $col <> 'date') {
                $columnValues[$col] = $recordsModel->getValuesForColumns($col);
            }
        }

        $this->view->columnValues = $columnValues;

        /**
         * Added by Anatoly
         */
        $this->view->isAdmin = $this->_me->admin == 1;
    }

    public function leavestatementAction() {
        $method = $this->getRequest()->getParam('method', 'html');
        $id = $this->getRequest()->getParam('id');
        $this->view->method = $method;
        $employeesModel = new Employees;
        $this->view->employee = $employeesModel->getById($id);

        $recordsModel = new Records;
        $this->view->records = $recordsModel->getAllByEmployee($id);

        if ($method == 'pdf') {
            error_reporting(0);
            $this->_helper->viewRenderer->setNoRender(true);
            $html = $this->view->render('user/leavestatement.phtml');
            $this->_helper->layout->disableLayout();
            require_once(APPLICATION_PATH . "/../data/dompdf/dompdf_config.inc.php");

            $dompdf = new DOMPDF();
            $dompdf->load_html($html);
            $dompdf->render();
            $dompdf->stream("Leave_Statement-" . $this->view->employee->firstname . "-" . $this->view->employee->lastname . "-" . date('Y-m-d') . "." . $method);
        }
        if ($method == 'csv') {
            //$this->_helper->viewRenderer->setNoRender(true);
            $this->_helper->layout->disableLayout();
        }
    }

    public function dataverificationAction() {
        $method = $this->getRequest()->getParam('method', 'html');
        $start = $this->getRequest()->getParam('start');
        $locaion = (int) $this->getRequest()->getParam('locaion');
        $end = false;
        if (strstr($start, '~')) {
            $dates = explode("~", $start);
            $start = $dates[0];
            $end = $dates[1];
            $this->view->end = $end;
        }
        $this->view->start = $start;
        $this->view->method = $method;
        $recordsModel = new Records;

        $employeesModel = new Employees;
        if ($this->_me->admin == 1) {
            if ($locaion == 0) {
                $employees = $employeesModel->getAll();
            } else {
                $employees = $employeesModel->getAllByLocation($locaion);
            }
        } else {
            $employees = $employeesModel->getAllByLocation($this->_me->location);
        }

        $totalData = array();
        $i = 0;
        foreach ($employees AS $employee) {
            $totalData[$i]['employee'] = $employee;
            $totalData[$i]['records'] = $recordsModel->getAllByEmployeeDate($employee->id, $start, $end);
            $i++;
        }

        $this->view->totalData = $totalData;

        if ($method == 'pdf') {
            error_reporting(0);
            ini_set('memory_limit', '-1');
            $this->_helper->viewRenderer->setNoRender(true);
            $html = $this->view->render('user/dataverification.phtml');
            $this->_helper->layout->disableLayout();
            require_once(APPLICATION_PATH . "/../data/dompdf/dompdf_config.inc.php");
            $dompdf = new DOMPDF();
            $dompdf->load_html($html);
            $dompdf->render();
            $dompdf->stream("Data-Verification-" . date('M-Y', strtotime($start)) . "-" . date('Y-m-d') . "." . $method);
        }
        if ($method == 'csv') {
            //$this->_helper->viewRenderer->setNoRender(true);
            $this->_helper->layout->disableLayout();
        }
    }

    public function generatereportAction() {

        $dataArray = array();
        $dataArray[] = array('column1' => 'value1', 'column2' => 'value2', 'column3' => 'value3', 'column4' => 'value4', 'column5' => 'value5');
        $dataArray[] = array('column1' => 'value1-2', 'column2' => 'value2-2', 'column3' => 'value3-2', 'column4' => 'value4-2', 'column5' => 'value5-2');
        if (isset($_GET['type'])) {
            $method = $_GET['type'];
        } else {
            $method = 'html';
        }
        if (isset($_GET['filename'])) {
            $filename = @$_GET['filename'];
        } else {
            $filename = "Leavetrax - " . strtoupper($method);
        }
        if (isset($_GET['title'])) {
            $filename = urldecode($_GET['title']);
        }

        //CSV OUTPUT
        if ($method == 'csv') {
            $this->_helper->viewRenderer->setNoRender(true);
            $this->_helper->layout->disableLayout();
            $csv = "";

            foreach ($dataArray[0] AS $key => $value) {
                if ($key == 'ID') {
                    $key = "RecordID";
                }
                $csv .= $key . ",";
            }
            $csv .="\n";
            foreach ($dataArray AS $rowData) {
                foreach ($rowData AS $key => $value) {
                    $csv .= $value . ",";
                }
                $csv .="\n";
            }
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=' . $filename . "-" . date('Y-m-d') . "." . $method);
            header('Pragma: no-cache');
            echo $csv;
        }

        //CSV OUTPUT
        if ($method == 'html') {
            $this->view->dataArray = $dataArray;
            if (isset($_GET['title'])) {
                $this->view->reportTitle = urldecode($_GET['title']);
            }
        }

        if ($method == 'pdf') {

            error_reporting(0);
            $this->_helper->viewRenderer->setNoRender(true);
            $this->_helper->layout->disableLayout();
            require_once(APPLICATION_PATH . "/../data/dompdf/dompdf_config.inc.php");


            $html = "<html><body style='font-family:Arial, sans-serif; font-size:.8em;'>";

            if (isset($_GET['title'])) {
                $html .= "<h1>" . $_GET['title'] . "</h1>";
            }
            $html .="<table border='1' style='border-collapse:collapse;' width='100%' class='zebra'><thead><tr style='background:#000; color:#fff;'>";

            foreach ($dataArray[0] AS $key => $value) {
                $html .= "<td>" . $key . "</td>";
            }

            $html .="</tr></thead>";

            foreach ($dataArray AS $rowData) {
                $html .="<tr>";
                foreach ($rowData AS $key => $value) {
                    $html .= "<td>" . $value . "</td>";
                }
                $html .="</tr>";
            }

            $html .="</table></body></html>";

            $dompdf = new DOMPDF();
            $dompdf->load_html($html);
            $dompdf->render();
            $dompdf->stream($filename . "-" . date('Y-m-d') . "." . $method);
        }
    }

    public function settingsAction() {
        if ($this->_me->admin <> 1) {
            $this->_redirect("/");
        }
        $siteSettingsModel = new SiteSettings;


        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            if ($siteSettingsModel->update($data, 1)) {
                $this->view->success = "Settings updated.";
            }
        }

        $this->view->settings = $siteSettingsModel->getById(1);
    }

    public function exportcsvAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        $recordsModel = new Records;

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();

            $records = $recordsModel->searchByArray($data);

            $csv = "RecordID, Date, EmployeeFirst,EmployeeLast,EmpID,TitlePaid,EmpAcct#1,Percent#1,Percent#2,Location,SubFirst,SubLast,SubCert,Payrate,SubSS,Reason,Note,LeaveForm,SubID";
            $csv.="\n";
            foreach ($records AS $record) {

                if (trim($record->acct) <> '') {
                    $acctNumber = $this->formatMumberAccount($record->acct);
                } else {
                    $acctNumber = $this->formatMumberAccount($record->account_number);
                }

                if (trim($record->acct_two) <> '') {
                    $acctNumberTwo = $this->formatMumberAccount($record->acct_two);
                } else {
                    $acctNumberTwo = $this->formatMumberAccount($record->account_number_two);
                }

                if (trim($record->payrate) <> '' && $record->payrate <> NULL) {
                    $payrateNumber = $record->payrate;
                } else {
                    $payrateNumber = $record->spayrate;
                }

                $l_form = 'No';
                if ($record->leave_form == '1') {
                    $l_form = 'Yes';
                }
                if ($record->leave_form == '0') {
                    $l_form = 'No';
                }

                $csv .= $record->id . ",";
                $csv .= $record->date . ",";
                $csv .= $record->efirstname . ",";
                $csv .= $record->elastname . ",";
                $csv .= $record->ecp_id . ",";
                $csv .= $record->epaid . ",";

                if ($record->substitute == 129 || !$record->substitute) {
                    $acctNumber = '5-000-0-0000-0000-00000-0000-0';
                }

                $csv .= '="' . $acctNumber . '",';

                $csv .= $record->percent . ",";
                $csv .= $record->percent_two . ",";
                $csv .= $record->lname . ",";
                $csv .= $record->sfirstname . ",";
                $csv .= $record->slastname . ",";
                $csv .= $record->scertified . ",";
                $csv .= $payrateNumber . ",";
                $csv .= $record->ssn . ",";
                $csv .= $record->reason . ",";
                $csv .= str_replace(",", "", $record->notes) . ",";
                $csv .= $l_form . ",";
                $csv .= $record->ssecondary_id . ",";
                $csv.="\n";
            }

            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=CSV_Export-' . date('Y-m-d') . ".csv");
            header('Pragma: no-cache');
            echo $csv;
        }
    }

    protected function formatMumberAccount($nroAccount) {
        $nroAccount = str_replace('-', '', $nroAccount);
        $letters = str_split($nroAccount);

        $account_number = $letters[0];

        if (isset($letters[1])) {
            $account_number = $account_number . "-" . $letters[1];
        }
        if (isset($letters[2])) {
            $account_number .= $letters[2];
        }
        if (isset($letters[3])) {
            $account_number .= $letters[3];
        }
        if (isset($letters[4])) {
            $account_number .= "-" . $letters[4];
        }
        if (isset($letters[5])) {
            $account_number .= "-" . $letters[5];
        }
        if (isset($letters[6])) {
            $account_number .= $letters[6];
        }
        if (isset($letters[7])) {
            $account_number .= $letters[7];
        }
        if (isset($letters[8])) {
            $account_number .= $letters[8];
        }
        if (isset($letters[9])) {
            $account_number .= "-" . $letters[9];
        }
        if (isset($letters[10])) {
            $account_number .= $letters[10];
        }
        if (isset($letters[11])) {
            $account_number .= $letters[11];
        }
        if (isset($letters[12])) {
            $account_number .= $letters[12];
        }
        if (isset($letters[13])) {
            $account_number .= "-" . $letters[13];
        }
        if (isset($letters[14])) {
            $account_number .= $letters[14];
        }
        if (isset($letters[15])) {
            $account_number .= $letters[15];
        }
        if (isset($letters[16])) {
            $account_number .= $letters[16];
        }
        if (isset($letters[17])) {
            $account_number .= $letters[17];
        }
        if (isset($letters[18])) {
            $account_number .= "-" . $letters[18];
        }
        if (isset($letters[19])) {
            $account_number .= $letters[19];
        }
        if (isset($letters[20])) {
            $account_number .= $letters[20];
        }
        if (isset($letters[21])) {
            $account_number .= $letters[21];
        }
        if (isset($letters[22])) {
            $account_number .= "-" . $letters[22];
        }

        return $account_number;
    }

    public function editAllRecordsAction() {
        $recordsModel = new Records;

        if ($this->getRequest()->isPost()) {
            if (!isset($data['method'])) {
                $data = $this->getRequest()->getPost();

                $ids = $data['id'];
                $date = $data['date'];
                $employee = $data['employee'];

                $location = $data['location'];

                $substitute = $data['substitute'];
                $reason = $data['reason'];
                $percent = $data['percent'];
                $percent_two = $data['percent_two'];
                $leave_form = $data['leave_form'];
                $notes = $data['notes'];
                $acct = $data['acct'];
                $acct_two = $data['acct_two'];
                $payrate = $data['payrate'];

                $current_url = $data['current_url'];


                if (count($ids) > 0) {
                    for ($i = 0; $i < count($ids); $i ++) {
                        $data = array();
                        $id = $ids[$i];

                        if (isset($date[$i]))
                            $data['date'] = $date[$i];

                        if (isset($employee[$i]))
                            $data['employee'] = $employee[$i];

                        if (isset($location[$i]))
                            $data['location'] = $location[$i];

                        if (isset($substitute[$i]))
                            $data['substitute'] = $substitute[$i];

                        if (isset($reason[$i]))
                            $data['reason'] = $reason[$i];

                        if (isset($percent[$i]))
                            $data['percent'] = $percent[$i];

                        if (isset($percent_two[$i]))
                            $data['percent_two'] = $percent_two[$i];

                        if (isset($leave_form[$i]))
                            $data['leave_form'] = $leave_form[$i];

                        if (isset($notes[$i]))
                            $data['notes'] = $notes[$i];

                        if (isset($acct[$i]))
                            $data['acct'] = $acct[$i];

                        if (isset($acct_two[$i]))
                            $data['acct_two'] = $acct_two[$i];

//							if($data['substitute'] == 129){
//								$data['acct'] = '5-000-0-0000-0000-00000-0000-0';								                
//							}

                        if (isset($payrate))
                            $data['payrate'] = $payrate[$i];

                        $recordsModel->updateRecord($id, $data);
                    }

                    return $this->redirect($current_url);
                }
            }
        }
    }

    public function editrecordsAction() {
        $recordsModel = new Records;

        $searchQuery = $this->getRequest()->getParam('search', false);
        $sort = $this->getRequest()->getParam('sort');
        $sorttype = $this->getRequest()->getParam('sorttype');
        $this->view->sort = $sort;
        $this->view->sorttype = $sorttype;

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();

            if (!isset($data['method'])) {

                $id = $data['id'];
                unset($data['id']);

                if ($data['substitute'] == 129) {
                    $data['acct'] = '5-000-0-0000-0000-00000-0000-0';
                }

                $recordsModel->updateRecord($id, $data);
            } else {

                if ($data['method'] == 'remove') {

                    $recordsModel->deleteMultiple($data['ids']);
                }

                if ($data['method'] == 'skipToPage') {
                    $this->_redirect($this->view->url(array('page' => $data['gotopage'])));
                }


                if ($data['method'] == 'search') {
                    unset($data['method']);
                    $search = array();

                    if (trim($data['startdate']) <> '') {
                        $startdate = date('Y-m-d', strtotime($data['startdate']));

                        $search['startdate'] = $startdate;
                    }
                    if (trim($data['enddate']) <> '') {
                        $enddate = date('Y-m-d', strtotime($data['enddate']));

                        $search['enddate'] = $enddate;
                    }

                    if (trim($data['reason']) <> '') {
                        $search['reason'] = $reason;
                    }

                    unset($data['startdate']);
                    unset($data['enddate']);


                    foreach ($data AS $key => $value) {
                        if (trim($value) <> '') {
                            $search[$key] = $value;
                        }
                    }
                    if (!empty($search)) {
                        $searchString = urlencode(base64_encode(serialize($search)));

                        $this->_redirect($this->view->url(array('search' => $searchString)));
                    }
                }
            }
        }

        $this->view->searchQuery = $searchQuery;
        $searchArray = unserialize(base64_decode(urldecode($searchQuery)));
        $this->view->search = (object) $searchArray;

        if ($this->_me->admin == 1) {
            $records = $recordsModel->getAll($sort, $sorttype, $searchArray);
        } else {
            $records = $recordsModel->getAllByLocation($this->_me->location, true, $searchArray);
        }

        $page = $this->_getParam('page', 1);
        $this->view->page = $page;

        $paginator = Zend_Paginator::factory($records);
        $paginator->setItemCountPerPage(20);
        $paginator->setCurrentPageNumber($page);

        $this->view->records = $paginator;

        $substitutesModel = new Substitutes;
        $this->view->substitutes = $substitutesModel->getAll();

        $locationsModel = new Locations;
        $this->view->locations = $locationsModel->getAll();

        $employeesModel = new Employees;


        if ($this->_me->admin == 1) {
            $this->view->employees = $employeesModel->getAll();
        } else {
            $this->view->employees = $employeesModel->getAllByLocation($this->_me->location);
        }
    }

    public function deleterecordsAction() {
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            unset($data['method']);
            $search = array();
            if (trim($data['startdate']) <> '') {
                $startdate = date('Y-m-d', strtotime($data['startdate']));
                $search['startdate'] = $startdate;
            }
            if (trim($data['enddate']) <> '') {
                $enddate = date('Y-m-d', strtotime($data['enddate']));
                $search['enddate'] = $enddate;
            }
            if (trim($data['reason']) <> '') {
                $search['reason'] = $reason;
            }
            unset($data['startdate']);
            unset($data['enddate']);
            foreach ($data AS $key => $value) {
                if (trim($value) <> '') {
                    $search[$key] = $value;
                }
            }
            $recordsModel = new Records;
            if ($this->_me->admin == 1) {
                $recordsSelect = $recordsModel->getAll(null, null, $search);
            } else {
                $recordsSelect = $recordsModel->getAllByLocation($this->_me->location, null, $search);
            }
            $records = $recordsModel->fetchAll($recordsSelect);
            $ids = [];
            if (count($records) > 0) {
                foreach ($records as $recode) {
                    $ids[] = $recode['id'];
                }
            }
            if (count($ids) > 0) {
                $recordsModel->deleteMultiple(implode(",", $ids));
            }
            $searchString = urlencode(base64_encode(serialize($search)));
            $this->_redirect("/editrecords/1/date/desc/" . $searchString);
            die();
        }
    }

}
