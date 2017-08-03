<?php

class Mpdf_main extends CI_Controller {

    function __construct() {

        parent::__construct();

//        if ($this->session->userdata('itms_protocal') == "") {
//            redirect('login');
//        }
//
//        if ($this->session->userdata('itms_protocal') == 71) {
//            redirect('admin');
//        }
//
//        if ($this->session->userdata('itms_user_id') != "") {
//            redirect(home);
//        }

        $this->load->library('pdf');
        $this->load->model('mdl_reports');
        $this->load->model('main/mdl_main');
        $this->load->library('emailsend');
    }

    function print_vehicle_tpms() {

        $asset = $this->input->get('asset');
        $driver = $this->input->get('driver');
        $phone = $this->input->get('phone');
        $content = $this->input->get('content');
        $class = $this->input->get('class_');
        $id = $this->input->get('id_');
        $company_id = $this->session->userdata('itms_company_id');


        $filename = $asset . "_status_" . date('dmYHis');

        if (!file_exists("./Exports/$company_id")) {
            mkdir("./Exports/$company_id", 0777, true);
        }

        $pdfFilePath = "./Exports/$company_id/itms.pdf";

        $html = "<div class=" . $class . " id=" . $id . "><h3>" . $asset . "</h3><h4>Driver Name : " . $driver . "</h4><h4>Driver phone : " . $phone . "</h4><p>Created On : " . date('jS M Y H:i:s') . "</p><hr><br>" . $content . "</div>";

        global $pdf;
        ob_end_clean();
        $pdf = $this->pdf->load();
        $pdf->debug = true;
        $stylesheet = file_get_contents('./assets/css/bootstrap.min.css');
        $stylesheet .= file_get_contents('./assets/css/styles/default.css');
        $stylesheet .= file_get_contents('./assets/css/styles/telematics.css');


        $pdf->WriteHTML($stylesheet, 1);
        $pdf->WriteHTML($html, 2);
        $pdf->Output($pdfFilePath, 'F');

        echo $company_id . "/itms.pdf";
        exit;
    }

    function print_report() {


        $report_id = $this->input->get('report_id');
        $report_name_org = $this->input->get('report_name');
        $report_name = $report_name_org . '_' . date('dmy_His');

        $tab_one_ids = $this->input->get('tab_one_ids');
        $tab_two_ids = $this->input->get('tab_two_ids');        

        if ($tab_one_ids == "null") {
            $tab_one_ids = null;
        }
        if ($tab_two_ids == "null") {
            $tab_two_ids = null;
        }
        $format = $this->input->get('format');
        $start_period = $this->input->get('start_period');
        $end_period = $this->input->get('end_period');

        $start_period = date('Y-m-d H:i', strtotime($start_period));
        $end_period = date('Y-m-d H:i', strtotime($end_period));

        $email = $this->input->get('report_email');
        $company_id = $this->session->userdata('itms_company_id');
        $download = $this->input->get('download');
        $daily = $this->input->get('daily');
        $weekly = $this->input->get('weekly');

        if ($download == "false") {
            $download = false;
        }

        if ($daily == "false") {
            $daily = false;
        } else {
            $daily = true;
        }

        if ($weekly == "false") {
            $weekly = false;
        } else {
            $weekly = true;
        }

        if (!$download) {
            if (is_array($tab_one_ids)) {
                $tab_one_ids = implode(",", $tab_one_ids);
            }
            if (is_array($tab_two_ids)) {
                $tab_two_ids = implode(",", $tab_two_ids);
            }
            echo $this->mdl_reports->save_schedule($report_name_org, $report_id, $format, $tab_one_ids, $tab_two_ids, $daily, $weekly, $email, $start_period, $end_period, $company_id);
            exit;
        }

        $stylesheet = "custom_report.css";

        //if ($schedule != 'none') {
        //$this->schedule_reporting($schedule, $email, $report_id, $report_name, $vehicle_ids, $format, $company_id);
        //}

        switch ($report_id) {
            case 1:
                $data = $this->mdl_reports->get_overspeeds($company_id, $tab_one_ids, $tab_two_ids, $start_period, $end_period);

                $paper_settings = null;
                $content = null;
                $this->create_overspeed_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings);
                break;
            case 3:
                $data = $this->mdl_reports->get_alerts($company_id, $tab_one_ids, $start_period, $end_period);

                $paper_settings = null;
                $content = null;
                $this->create_alerts_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings);
                break;
            case 4:
                $data = $this->mdl_reports->get_trips_data($company_id, $tab_one_ids, $start_period, $end_period);

                $paper_settings = null;
                $content = null;
                $this->create_trips_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings);
                break;
            case 11:
                $data = $this->mdl_reports->get_dealers($tab_one_ids, $company_id);

                $paper_settings = null;
                $content = null;
                $this->create_dealers_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings);
                break;
            case 7:
                $data = $this->mdl_reports->get_distances($company_id, $tab_one_ids, $start_period, $end_period);

                $paper_settings = null;
                $content = null;
                $this->create_distance_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings);
                break;
            case 6:
                $owner_data = $this->mdl_reports->get_owners($tab_one_ids, $company_id);
                $asset_data = $this->mdl_reports->get_assets($tab_one_ids, $company_id);

                $paper_settings = null;
                $content = null;
                $this->create_owner_report($owner_data, $asset_data, $report_name, $company_id, $content, $stylesheet, $paper_settings);
                break;
            case 10:
                $data = $this->mdl_reports->get_personnel($company_id, $tab_one_ids);

                $paper_settings = null;
                $content = null;
                $this->create_personnel_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings);
                break;
            /* case 5:
              $data = $this->mdl_reports->get_vehicle_summary($company_id,$tab_one_ids);

              $paper_settings = null;
              $content = null;
              $this->create_vehicle_summary($data, $report_name, $company_id, $content, $stylesheet, $paper_settings);
              break; */
            case 12: //acc ignition
                $data = $this->mdl_reports->get_ignition_data($company_id, $tab_one_ids, $tab_two_ids, $start_period, $end_period);
                $tab_one_ids = implode(',', $tab_one_ids);
                $ignition_time = array();
                $i = 0;
                $temp = array();
                $temp_two = "";
                $assets_name = $this->mdl_reports->get_assets_name($tab_one_ids);
                
                foreach ($data as $value) {
                    $assets_name = $value['assets_name'];
                    $time = strtotime($value['time'])*1000;
                    
                    
                    array_push($temp, ($time));
                    array_push($temp, (int)$value['ignition']);
                    array_push($ignition_time, $temp);
                    $temp = array();

                    $temp_two .= "'" . $value['time'] . "'" . ":" . (int) $i . ",";
                    $i ++;
                }

                /*$ignition_index = json_encode(array('data' => $ignition_time, array('data_index' => $temp_two), "units" => "", "result_type" => "logic"));

                $final = array();
                array_push($final, array('data' => $ignition_time));
                array_push($final, $ignition_index);*/
               
                echo json_encode(array('data'=>$ignition_time,'asset_name'=>$assets_name));
//                echo "index.php/reports/ignition_graph?graph_data=" . $ignition_index . "&asset_name=" . $assets_name
//                . "&start_period=" . $start_period . "&end_period=" . $end_period . "&asset_id=" . $tab_one_ids;
                /*echo json_encode(array("graph_data" => $ignition_index , "asset_name" => $assets_name
                , "start_period"=> $start_period, "end_period"=> $end_period ,"asset_id" => $tab_one_ids));*/
                break;

            default:
                $content = "<div style='border:2px solid #eee; width:100%; text-align-center; font-size:16px; color:red; font-weight:600;'>Report Missing</div>";
                $paper_settings = null;
                $this->generate_pdf('Info', $report_name, $company_id, $content, $stylesheet, $paper_settings, 3);
        }
    }

    public function create_vehicle_summary($data, $report_name, $company_id, $content, $stylesheet, $paper_settings, $email = null) {
        $content = '<table class="" style="border-collapse:collapse;" border="1" width="100%" id="dataTables-example">';

        foreach ($data as $key => $value) {
            $content .= '<thead>
                                <tr><th>Vehicle Name :</th><th>' . $value->assets_friendly_nm . '</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Plate number :</td>
                                    <td>' . $value->assets_name . '</td>
                                </tr>
                                <tr>    
                                    <td>Device ID :</td>
                                    <td>' . $value->device_id . '</td>
                                </tr>
                                <tr>    
                                    <td>Type :</td>
                                    <td>' . $value->assets_type_nm . '</td>
                                </tr>
                                <tr>    
                                    <td>Category :</td>
                                    <td>' . $value->assets_cat_name . '</td>
                                </tr>
                                <tr>    
                                    <td>Axles :</td>
                                    <td>' . $value->no_of_axles . '</td>
                                </tr>
                                <tr>    
                                    <td>Tyre Config :</td>
                                    <td>' . $value->axle_tyre_config . '</td>
                                </tr>
                                <tr>    
                                    <td>Speed Limit :</td>
                                    <td>' . $value->max_speed_limit . ' Kmh</td>
                                </tr>
                                <tr> 
                                    <td>Owner :</td>
                                    <td>' . $value->owner_name . '</td>
                                </tr>
                                <tr>     
                                    <td>Driver :</td>
                                    <td>' . $value->driver_name . '</td>
                                </tr>
                                <tr>     
                                    <td>Driver Phone :</td>
                                    <td>' . $value->driver_phone . '</td>
                                </tr>
                            </tbody>                        
                        ';
            $content .='</table>';
        }
        if (!is_null($email)) {
            if ($total_data_size == 0) {
                $this->send_default_email($email, 'VEHICLE(S) SUMMARY', "No data for vehicles report this week");
                return;
            }
            $this->generate_email_pdf('VEHICLE(S) SUMMARY', $report_name, $company_id, $content, $stylesheet, $paper_settings, sizeof($data), $email);
        } else {
            $this->generate_pdf('VEHICLE(S) SUMMARY', $report_name, $company_id, $content, $stylesheet, $paper_settings, sizeof($data));
        }
    }

    public function create_alerts_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings, $email = null) {
        $filename = $report_name;
        $by = $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name');
        $date = date('jS M Y H:i:s');
        $pdfFilePath = "./Exports/" . $company_id . "/$filename.pdf";
        $content = '<table class="" style="border-collapse:collapse;" border="1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Driver</th>
                                <th>Vehicle Name</th>
                                <th>Overspeeding</th>
                                <th>Low Pressure</th>
                                <th>High Pressure</th>
                            </tr>   
                        </thead>
                        </tbody>';
        $i = 1;
        $total_data_size = 0;
        foreach ($data as $key => $value) {
            $content .= "<tr>
                            <td>" . $i . "</td>
                            <td>" . $value->fname . " " . $value->lname . "</td>
                            <td>" . $value->assets_name . "</td>
                            <td>" . $value->overspeeding . "</td>
                            <td>" . $value->low_pressure . "</td>
                            <td>" . $value->high_pressure . "</td>
                         </tr>       
                    ";
            $total_data_size += $value->data_size;
            $i++;
        }

        $content .= "</tbody></table>";
        if (!is_null($email)) {
            if ($total_data_size == 0) {
                $this->send_default_email($email, 'ALERT(S) SUMMARY', "No data for alerts report this week");
                return;
            }
            $this->generate_email_pdf('ALERT(S) SUMMARY', $report_name, $company_id, $content, $stylesheet, $paper_settings, sizeof($data), $email);
        } else {

            $this->generate_pdf('ALERT(S) SUMMARY', $report_name, $company_id, $content, $stylesheet, $paper_settings, $total_data_size);
        }
    }
    
        public function create_trips_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings, $email = null) {
        $filename = $report_name;
        $by = $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name');
        $date = date('jS M Y H:i:s');
        $pdfFilePath = "./Exports/" . $company_id . "/$filename.pdf";
        $content = '<table class="" style="border-collapse:collapse;" border="1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Driver</th>
                                <th>Vehicle Name</th>
                                <th>Completed</th>
                                <th>Incomplete</th>
                                <th>Distance Travelled</th>
                            </tr>   
                        </thead>
                        </tbody>';
        $i = 1;
        $data_size = $data[0]["data_size"];
        if($data_size >0){
        foreach ($data as $key => $value) {
            $content .= "<tr>
                            <td>" . $i . "</td>
                            <td>" . $value["fname"] . " " . $value["lname"] . "</td>
                            <td>" . $value["assets_name"] . "</td>
                            <td>" . $value["complete"] . "</td>
                            <td>" . $value["incomplete"] . "</td>
                            <td>" . $value["distance_travelled"] . "</td>
                         </tr>       
                    ";
            $i++;
        }
        $content .= "</tbody></table>";
        }else{
          $content = "NO Data Found!";  
        }

        
        if (!is_null($email)) {
            if ($total_data_size == 0) {
                $this->send_default_email($email, 'TRIP(S) SUMMARY', "No data for trips report this week");
                return;
            }
            $this->generate_email_pdf('TRIP(S) SUMMARY', $report_name, $company_id, $content, $stylesheet, $paper_settings, sizeof($data), $email);
        } else {

            $this->generate_pdf('TRIP(S) SUMMARY', $report_name, $company_id, $content, $stylesheet, $paper_settings, $total_data_size);
        }
    }

    private function create_overspeed_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings, $email = null) {
        $filename = $report_name;
        $by = $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name');
        $date = date('jS M Y H:i:s');
        $pdfFilePath = "./Exports/" . $company_id . "/$filename.pdf";
        $content = '<table class="" style="border-collapse:collapse;" border="1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Vehicle</th>
                                <th>Maximum Speed</th>
                                <th>Overspeed</th>
                                <th>Date Time</th>
                                <th>Position</th>
                            </tr>   
                        </thead>
                        </tbody>';
        $i = 1;
        $total_data_size = 0;
        foreach ($data as $key => $value) {
            $content .= "<tr>
                            <td>" . $i . "</td>
                            <td>" . $value->fname . "</td>
                            <td>" . $value->lname . "</td>
                            <td>" . $value->assets_friendly_nm . "</td>
                            <td>" . $value->max_speed_limit . "</td>
                            <td>" . $value->speed . "</td>
                            <td>" . date('Y-m-d', strtotime($value->add_date)) . " " . $value->add_time . "</td>
                            <td>" . $value->address . "</td>
                         </tr>       
                    ";
            $total_data_size += $value->data_size;
            $i++;
        }

        $content .= "</tbody></table>";
        $content .= "</tbody></table>";
        if (!is_null($email)) {
            if ($total_data_size == 0) {
                $this->send_default_email($email, 'OVERSPEED REPORT', "No data for overspeeds report this week");
                return;
            }
            $this->generate_email_pdf('OVERSPEED REPORT', $report_name, $company_id, $content, $stylesheet, $paper_settings, sizeof($data), $email);
        } else {
            $this->generate_pdf('OVERSPEED REPORT', $report_name, $company_id, $content, $stylesheet, $paper_settings, $total_data_size);
        }
    }

    public function create_dealers_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings, $email = null) {
        $filename = $report_name;
        $by = $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name');
        $date = date('jS M Y H:i:s');
        $pdfFilePath = "./Exports/" . $company_id . "/$filename.pdf";
        $content = '<table class="" style="border-collapse:collapse;" border="1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Dealer Name</th>
                                <th>Dealer Product</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Address</th>
                            </tr>   
                        </thead>
                        </tbody>';
        $i = 1;
        $total_data_size = 0;
        foreach ($data as $key => $value) {
            $content .= "<tr>
                            <td>" . $i . "</td>
                            <td>" . $value->dealer_name . "</td>
                            <td>" . $value->dealer_in . "</td>
                            <td>" . $value->phone_no . "</td>
                            <td>" . $value->email . "</td>
                            <td>" . $value->address . "</td>
                         </tr>       
                    ";
            $total_data_size += $value->data_size;
            $i++;
        }

        $content .= "</tbody></table>";
        if (!is_null($email)) {
            if ($total_data_size == 0) {
                $this->send_default_email($email, 'DEALER(S) SUMMARY', "No data for dealers report this week");
                return;
            }
            $this->generate_email_pdf('DEALER(S) SUMMARY', $report_name, $company_id, $content, $stylesheet, $paper_settings, sizeof($data), $email);
        } else {
            $this->generate_pdf('DEALER(S) SUMMARY', $report_name, $company_id, $content, $stylesheet, $paper_settings, $total_data_size);
        }
    }

    public function create_distance_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings, $email = null) {
        $filename = $report_name;
        $by = $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name');
        $date = date('jS M Y H:i:s');
        $pdfFilePath = "./Exports/" . $company_id . "/$filename.pdf";
        $content = '<table class="" style="border-collapse:collapse;" border="1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Vehicle Name</th>
                                <th>Owner</th>
                                <th>First Reading</th>
                                <th>Current Reading</th>
                                <th>Distance</th>
                                <th>Fuel Used</th>
                                <th>Fuel Filled</th>
                            </tr>   
                        </thead>
                        </tbody>';
        $i = 1;
        $total_data_size = 0;
        foreach ($data as $key => $value) {
            $content .= "<tr>
                            <td>" . $i . "</td>
                            <td>" . $value->assets_name . "</td>
                            <td>" . $value->owner_name . "</td>
                            <td>" . $value->first_reading . "</td>
                            <td>" . $value->current_reading . "</td>
                            <td>" . $value->distance . "</td>
                            <td>" . $value->fuel_used . "</td>
                            <td>" . $value->fuel_filled . "</td>
                         </tr>       
                    ";
            $total_data_size += $value->data_size;
            $i++;
        }

        $content .= "</tbody></table>";
        if (!is_null($email)) {
            if ($total_data_size == 0) {
                $this->send_default_email($email, 'DISTANCE(S) SUMMARY', "No data for distance report this week");
                return;
            }
            $this->generate_email_pdf('DISTANCE(S) SUMMARY', $report_name, $company_id, $content, $stylesheet, $paper_settings, sizeof($data), $email);
        } else {
            $this->generate_pdf('DISTANCE(S) SUMMARY', $report_name, $company_id, $content, $stylesheet, $paper_settings, $total_data_size);
        }
    }

    public function create_personnel_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings, $email = null) {
        $filename = $report_name;
        $by = $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name');
        $date = date('jS M Y H:i:s');
        $pdfFilePath = "./Exports/" . $company_id . "/$filename.pdf";
        $content = '<table class="" style="border-collapse:collapse;" border="1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>ID Number</th>
                                <th>Gender</th>
                                <th>Phone</th>
                                <th>Email</th>                                
                            </tr>   
                        </thead>
                        </tbody>';
        $i = 1;
        $total_data_size = 0;
        foreach ($data as $key => $value) {
            $status = $this->status_to_string($value->status);

            $content .= "<tr>
                            <td>" . $i . "</td>
                            <td>" . $value->fname . "</td>
                            <td>" . $value->lname . "</td>
                            <td>" . $value->role_name . "</td>
                            <td>" . $status . "</td>
                            <td>" . $value->id_no . "</td>
                            <td>" . $value->gender . "</td>
                            <td>" . $value->phone_no . "</td>
                            <td>" . $value->email . "</td>
                         </tr>       
                    ";
            $total_data_size += $value->data_size;
            $i++;
        }

        $content .= "</tbody></table>";
        if (!is_null($email)) {
            if ($total_data_size == 0) {
                $this->send_default_email($email, 'PERSONNEL SUMMARY', "No data for personnel report this week");
                return;
            }
            $this->generate_email_pdf('PERSONNEL SUMMARY', $report_name, $company_id, $content, $stylesheet, $paper_settings, sizeof($data), $email);
        } else {
            $this->generate_pdf('PERSONNEL SUMMARY', $report_name, $company_id, $content, $stylesheet, $paper_settings, $total_data_size);
        }
    }

    public function create_owner_report($owner_data, $asset_data, $report_name, $company_id, $content, $stylesheet, $paper_settings, $email = null) {
        $filename = $report_name;
        $by = $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name');
        $date = date('jS M Y H:i:s');
        $pdfFilePath = "./Exports/" . $company_id . "/$filename.pdf";


        foreach ($owner_data as $owner_key => $owner_value) {
            $status = $this->status_to_string($owner_value->status);

            $content .= '<table class="" style="border-collapse:collapse; width:100%;" border="1">
                        <thead>
                            <tr>
                                <th>Owner Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Status</th>                               
                            </tr>   
                        </thead>
                        <tbody>';

            $content .= "<tr>
                            <td>" . $owner_value->owner_name . "</td>
                            <td>" . $owner_value->phone_no . "</td>
                            <td>" . $owner_value->email . "</td>                            
                            <td>" . $owner_value->address . "</td>
                            <td>" . $status . "</td>
                         </tr>       
                    ";
            $content .= "</tbody></table>";


            $asset_count = 0;
            foreach ($asset_data as $asset_key => $asset_value) {
                if ($owner_value->owner_id == $asset_value->owner_id) {
                    $asset_count++;
                    $content .= '<table class="" style="border-collapse:collapse; width:100%;" border="1 ">
                        <tbody>';
                    $content .= "
                            <tr>
                                <td><strong>Asset Name</strong></td><td>" . $asset_value->assets_name . "</td>
                            </tr> 
                            <tr>
                                <td><strong>Asset Type</strong></td><td>" . $asset_value->asset_type . "</td>
                            </tr> 
                            <tr>   
                                <td><strong>Status</strong></td><td>" . $this->status_to_string($asset_value->status) . "</td>
                            </tr> 
                            <tr>
                                <td><strong>Date Added</strong></td><td>" . $asset_value->add_date . "</td>
                            </tr>
                    ";

                    $content .= "</tbody></table><div height='20px'/>";
                }
            }
            if ($asset_count == 0) {
                $content .= '<table class="" style="border-collapse:collapse; width:100%;" border="1 ">
                        <tbody>';
                $content .= "<tr><td>No Assets Data</td></tr>";
                $content .= "</tbody></table><div height='20px'/>";
            }
        }



        $this->generate_pdf('OWNER AND ASSETS SUMMARY', $report_name, $company_id, $content, $stylesheet, $paper_settings, sizeof($owner_data));
    }

    //cahanges 1/0 to Active/Inactive
    private function status_to_string($value) {
        if ($value == 0) {
            $status = "Inactive";
        } else if ($value == 1) {
            $status = "Active";
        }
        return $status;
    }

    public function generate_pdf($title, $filename, $company_id, $content, $stylesheet, $paper_settings, $data_size) {

        //$filename = $filename;
        $by = $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name');
        $date = date('jS M Y H:i:s');
        $pdfFilePath = $filename . ".pdf";

        if ($data_size == 0) {
            $content = "<div align='center'>No data found</div>";
        }

        $start_period = $this->input->get('start_period');
        $end_period = $this->input->get('end_period');

        $html = "<div class='top-report-div'>
                    <h4 style='text-align:center; width:100%'>" . $title . "</h4>" .
                "<div width='100%' style='text-align:center;'>(" . $start_period . " - " . " " . $end_period . ")</div>
                    <div style='width:45%; float:left;text-align:left; color:#ccc'>Printed by: " . $by . "</div>
                    <div style='width:45%; float:left;text-align:right; color:#ccc'>" . $date . "</div>

                </div>";
        $html .= $content;

        global $pdf;
        ob_end_clean();
        $pdf = $this->pdf->load($paper_settings);
        $pdf->debug = true;

        //$stylesheet = file_get_contents('./assets/css/bootstrap.min.css');
        $stylesheet .= file_get_contents('./assets/css/styles/' . $stylesheet);
        $pdf->setFooter('{PAGENO}');
        $pdf->WriteHTML("
          table {
          border-collapse: collapse;
          width: 100%;
          }

          th, td {
          text-align: left;
          padding: 2px;
          }

          tr:nth-child(even){background-color: #f2f2f2}

          th {
          background-color: #4CAF50;
          color: white;
          }", 1);
        $pdf->WriteHTML($html);
        $watermark = $this->session->userdata('company_name');
        $pdf->showWatermarkText = true;
        $pdf->watermark_font = 'DejaVuSansCondensed';
        $pdf->watermarkTextAlpha = 0.1;
        $pdf->SetWatermarkText($watermark);
        $pdf->Output($pdfFilePath, 'I');
    }

    function fetch_scheduled_report_by_id() {
        $id = $this->input->get('id');
        $result = $this->mdl_main->fetch_scheduled_report_by_id($id);
        echo json_encode((sizeof($result) > 0) ? $result[0] : null);
    }

    function fetch_scheduled_reports() {
        echo json_encode($this->mdl_main->fetch_scheduled_reports());
    }

    function delete_report_schedule() {
        $id = $this->input->get('id');
        $this->mdl_main->delete_report_schedule($id);
    }

    function cmd() {
        system('schtasks /create /tn "ITMS weekly" /tr "C:\xampp\php\php.exe C:\xampp\htdocs\itmsafrica\index.php mpdf_main/mpdf_main weekly" /sc weekly', $result);
        echo $result;
    }

    /**
     * Run weekly email report schedules
     */
    public function weekly() {

        $data = $this->mdl_reports->scheduled_reports("weekly");
            for ($i = 0; $i < count($data); $i++) {
                $this->create_report($data[$i]['report_type_id'], $data[$i]['company_id'], $data[$i]['tab_one_ids'], $data[$i]['tab_two_ids'], $data[$i]['email']);
            }
    }

    public function daily() {

        $data = $this->mdl_reports->scheduled_reports("daily");
            for ($i = 0; $i < count($data); $i++) {
                $this->create_report($data[$i]['report_type_id'], $data[$i]['company_id'], $data[$i]['tab_one_ids'], $data[$i]['tab_two_ids'], $data[$i]['email']);
            }
    }

    /**
     * Creates report for scheduled emails
     * @param type $report_id
     * @param type $company_id
     * @param type $tab_one_ids
     * @param type $tab_two_ids
     * @param type $email
     */
    function create_report($report_id, $company_id, $tab_one_ids, $tab_two_ids, $email) {
        $report_name = date("Y-m-d H:i:s");
        $start_period = date("Y-m-d H:i:s", strtotime("-7 days"));
        $end_period = date("Y-m-d H:i:s");

        $stylesheet = file_get_contents('./assets/css/styles/default.css');
        switch ($report_id) {
            case 1:
                $data = $this->mdl_reports->get_overspeeds($company_id, $tab_one_ids, $tab_two_ids, $start_period, $end_period);

                $paper_settings = null;
                $content = null;
                $this->create_overspeed_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings);
                break;
            case 3:
                $data = $this->mdl_reports->get_alerts($company_id, $tab_one_ids, $start_period, $end_period);

                $paper_settings = null;
                $content = null;
                $this->create_alerts_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings, $email);
                break;
            case 11:
                $data = $this->mdl_reports->get_dealers($tab_one_ids, $company_id);

                $paper_settings = null;
                $content = null;
                $this->create_dealers_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings);
                break;
            case 7:
                $data = $this->mdl_reports->get_distances($company_id, $tab_one_ids, $start_period, $end_period);

                $paper_settings = null;
                $content = null;
                $this->create_distance_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings);
                break;
            case 6:
                $owner_data = $this->mdl_reports->get_owners($tab_one_ids, $company_id);
                $asset_data = $this->mdl_reports->get_assets($tab_one_ids, $company_id);

                $paper_settings = null;
                $content = null;
                $this->create_owner_report($owner_data, $asset_data, $report_name, $company_id, $content, $stylesheet, $paper_settings);
                break;
            case 10:
                $data = $this->mdl_reports->get_personnel($company_id, $tab_one_ids);

                $paper_settings = null;
                $content = null;
                $this->create_personnel_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings);
                break;
            /* case 5:
              $data = $this->mdl_reports->get_vehicle_summary($company_id,$tab_one_ids);

              $paper_settings = null;
              $content = null;
              $this->create_vehicle_summary($data, $report_name, $company_id, $content, $stylesheet, $paper_settings);
              break; */
            case 12: //acc ignition
                $data = $this->mdl_reports->get_ignition_data($company_id, $tab_one_ids, $tab_two_ids, $start_period, $end_period);
                $tab_one_ids = implode(',', $tab_one_ids);
                $ignition_time = array();
                $i = 0;
                $temp = array();
                $temp_two = "";
                $assets_name = $this->mdl_reports->get_assets_name($tab_one_ids);

                foreach ($data as $value) {
                    $assets_name = $value['assets_name'];
                    array_push($temp, $value['time']);
                    array_push($temp, $value['ignition']);
                    array_push($ignition_time, $temp);
                    $temp = array();

                    $temp_two .= "'" . $value['time'] . "'" . ":" . (int) $i . ",";
                    $i ++;
                }

                $ignition_index = json_encode(array('data' => $ignition_time, array('data_index' => $temp_two), "units" => "", "result_type" => "logic"));

                $final = array();
                array_push($final, array('data' => $ignition_time));
                array_push($final, $ignition_index);


//                echo "index.php/reports/ignition_graph?graph_data=" . $ignition_index . "&asset_name=" . $assets_name
//                . "&start_period=" . $start_period . "&end_period=" . $end_period . "&asset_id=" . $tab_one_ids;
                echo json_encode(array("graph_data" => $ignition_index , "asset_name" => $assets_name
                , "start_period"=> $start_period, "end_period"=> $end_period ,"asset_id" => $tab_one_ids));
                break;

            default:
                $content = "<div style='border:2px solid #eee; width:100%; text-align-center; font-size:16px; color:red; font-weight:600;'>Report Missing</div>";
                $paper_settings = null;
                $this->generate_pdf('Info', $report_name, $company_id, $content, $stylesheet, $paper_settings, 3);
        }
    }

    function generate_email_pdf($title, $filename, $company_id, $content, $stylesheet, $paper_settings, $data_size, $email) {
        //$filename = $filename;
        $by = $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name');
        $date = date('jS M Y H:i:s');
        $pdfFilePath = "./temp_pdfs/itms_report.pdf";

        if ($data_size == 0) {
            $content = "<div align='center'>No data found</div>";
        }

        $start_period = $this->input->get('start_period');
        $end_period = $this->input->get('end_period');

        $html = "<div class='top-report-div'>
                    <h4 style='text-align:center; width:100%'>" . $title . "</h4>" .
                "<div width='100%' style='text-align:center;'>(" . $start_period . " - " . " " . $end_period . ")</div>
                    <div style='width:45%; float:left;text-align:left; color:#ccc'>Printed by: " . $by . "</div>
                    <div style='width:45%; float:left;text-align:right; color:#ccc'>" . $date . "</div>

                </div>";
        $html .= $content;

        global $pdf;
        //ob_end_clean();
        $pdf = $this->pdf->load($paper_settings);
        $pdf->debug = true;

        //$stylesheet = file_get_contents('./assets/css/bootstrap.min.css');
        //$stylesheet .= file_get_contents('./assets/css/styles/' . $stylesheet);
        $pdf->setFooter('{PAGENO}');
        $pdf->WriteHTML("
          table {
          border-collapse: collapse;
          width: 100%;
          }

          th, td {
          text-align: left;
          padding: 2px;
          }

          tr:nth-child(even){background-color: #f2f2f2}

          th {
          background-color: #4CAF50;
          color: white;
          }", 1);
        $pdf->WriteHTML($html);
        $watermark = $this->session->userdata('company_name');
        $pdf->showWatermarkText = true;
        $pdf->watermark_font = 'DejaVuSansCondensed';
        $pdf->watermarkTextAlpha = 0.1;
        $pdf->SetWatermarkText($watermark);
        $pdf->Output($pdfFilePath, 'F');
        $message = "Find attached reports";
        echo $this->emailsend->send_email_report($email, $title, $message, $pdfFilePath);
    }

    /**
     * Sends email with message of not report data
     * @param type $email
     * @param type $subject
     * @param type $message
     */
    function send_default_email($email, $subject, $message) {
        echo $this->emailsend->send_email_report($email, $subject, $message, "blank");
    }

}
