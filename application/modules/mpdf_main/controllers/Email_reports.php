<?php
class Email_reports extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->load->library('emailsend');
        $this->load->model('mpdf_main/mdl_reports');
        $this->load->library('pdf');
    }
    
    public function weekly() {
        echo "Weekly";
        exit;
         $data = $this->mdl_reports->scheduled_reports("weekly");
        for ($i = 0; $i < count($data); $i++) {
            $this->create_report($data[$i]['report_type_id'], $data[$i]['company_id'], $data[$i]['tab_one_ids'], $data[$i]['tab_two_ids'], $data[$i]['email']);
        }
    }

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


                echo "index.php/reports/ignition_graph?graph_data=" . $ignition_index . "&asset_name=" . $assets_name
                . "&start_period=" . $start_period . "&end_period=" . $end_period . "&asset_id=" . $tab_one_ids;

                break;

            default:
                $content = "<div style='border:2px solid #eee; width:100%; text-align-center; font-size:16px; color:red; font-weight:600;'>Report Missing</div>";
                $paper_settings = null;
                $this->generate_pdf('Info', $report_name, $company_id, $content, $stylesheet, $paper_settings, 3);
        }
    }

    public function generate_pdf($title, $filename, $company_id, $content, $stylesheet, $paper_settings, $data_size, $email,$message) {

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
        echo $this->emailsend->send_email_report($email, $subject, $message, $pdfFilePath);
    }

    public function create_alerts_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings, $email) {
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
                                <th>Tyre Pressure</th>
                            </tr>   
                        </thead>
                        </tbody>';
        $org_content = $content;
        $i = 1;
        $total_data_size = 0;
        foreach ($data as $key => $value) {
            $content .= "<tr>
                            <td>" . $i . "</td>
                            <td>" . $value->fname . " " . $value->lname . "</td>
                            <td>" . $value->assets_name . "</td>
                            <td>" . $value->overspeeding . "</td>
                            <td>" . $value->tyre_pressure . "</td>
                         </tr>       
                    ";
            $total_data_size += $value->data_size;
            $i++;
        }
        if($total_data_size==0){
            $this->send_default_email($email,"Alerts Summary Report","No data for alerts report this week");
            exit;
        }
        $content .= "</tbody></table>";
        
        $this->generate_pdf('ALERT(S) SUMMARY', $report_name, $company_id, $content, $stylesheet, $paper_settings, sizeof($data), $email);
    }
    
    private function create_overspeed_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings) {
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
            $i++;
        }

        $content .= "</tbody></table>";
        $this->generate_pdf('OVERSPEED REPORT', $report_name, $company_id, $content, $stylesheet, $paper_settings, sizeof($data));
    }

    public function create_dealers_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings) {
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
            $i++;
        }

        $content .= "</tbody></table>";
        $this->generate_pdf('DEALER(S) SUMMARY', $report_name, $company_id, $content, $stylesheet, $paper_settings, sizeof($data));
    }

    public function create_distance_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings) {
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
            $i++;
        }

        $content .= "</tbody></table>";
        $this->generate_pdf('DISTANCE(S) SUMMARY', $report_name, $company_id, $content, $stylesheet, $paper_settings, sizeof($data));
    }

    public function create_personnel_report($data, $report_name, $company_id, $content, $stylesheet, $paper_settings) {
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
            $i++;
        }

        $content .= "</tbody></table>";
        $this->generate_pdf('PERSONNEL SUMMARY', $report_name, $company_id, $content, $stylesheet, $paper_settings, sizeof($data));
    }

    public function create_owner_report($owner_data, $asset_data, $report_name, $company_id, $content, $stylesheet, $paper_settings) {
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
    function send_default_email($email,$subject,$message){
        echo $this->emailsend->send_email_report($email, $subject, $message, "blank");
    }
    public function daily() {
        echo "Daily";
    }

}
