<?php

	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$menu_data = array("7","8","9");
	else
	{
		$menu_data = array();
		$va1l = $this->db;
		$va1l->select("menu_id");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name",'main');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$menu_data[] = $row['menu_id'];
			
		}
	
	}
	//print_r($data);
	//die();

$assets_details = array
                        (
                            "title" => $this->lang->line("Assets Details"),
                            "id" => "widget1",
                            "column" => "first",
                            "editurl" => "",
                            "open" => "1",
                            "url" => base_url()."index.php/home/assets_det/id/".$id
                        );
$current_location = array(
                            "title" => $this->lang->line("Current Location"),
                            "column" => "second",
                            "id" => "widget2",
                            "url" => base_url()."index.php/home/map_widget/id/".$id,
                            "editurl" => "widgets/editwidget3.html",
                            "open" => "1"
                        );
$current_speed = array(
                            "title" => $this->lang->line("Max Speed"),
                            "column" => "second",
                            "id" => "widget3",
                            "url" => base_url()."index.php/home/speedometer_widget/id/".$id,
                            "editurl" => "widgets/editwidget3.html",
                            "open" => "1"
                        );
$distance_travel = array(
                            "title" => $this->lang->line("Distance Travel(Today)"),
                            "column" => "second",
                            "id" => "widget4",
                            "url" => base_url()."index.php/home/get_distance/id/".$id,
                            "editurl" => "widgets/editwidget3.html",
                            "open" => "1"
                        );
$speed_graph = array(
                            "title" => $this->lang->line("Speed Graph(Last 3 Hours)"),
                            "column" => "second",
                            "id" => "widget5",
                            "url" => base_url()."index.php/home/speedgraph_widget/id/".$id,
                            "editurl" => "widgets/editwidget3.html",
                            "open" => "1"
                        );
$distance_graph = array(
                            "title" => $this->lang->line("Distance Graph(Last 7 Days)"),
                            "column" => "second",
                            "id" => "widget6",
                            "url" => base_url()."index.php/home/distancegraph_widget/id/".$id,
                            "editurl" => "widgets/editwidget3.html",
                            "open" => "1"
                        );
$stop_report = array(
                            "title" => $this->lang->line("Stop Report")." (Last 10 Records)",
                            "column" => "first",
                            "id" => "widget7",
                            "url" => base_url()."index.php/home/stop_report/id/".$id,
                            "editurl" => "widgets/editwidget3.html",
                            "open" => "1"
                        );
$area_in_out_report = array(
                            "title" => $this->lang->line("Area In/Out Report")." (Last 10 Records)",
                            "column" => "first",
                            "id" => "widget8",
                            "url" => base_url()."index.php/home/area_in_out_report/id/".$id,
                            "editurl" => "widgets/editwidget3.html",
                            "open" => "1"
                        );
$landmark_report = array(
                            "title" => $this->lang->line("Landmark Report")." (Last 10 Records)",
                            "column" => "second",
                            "id" => "widget9",
                            "url" => base_url()."index.php/home/landmark_report/id/".$id,
                            "editurl" => "widgets/editwidget3.html",
                            "open" => "1"
                        );
$distance_wise_report = array(
                            "title" => $this->lang->line("Distance Location")." (Last 10 Records)",
                            "column" => "second",
                            "id" => "widget10",
                            "url" => base_url()."index.php/home/distance_wise_report/id/".$id,
                            "editurl" => "widgets/editwidget3.html",
                            "open" => "1"
                        );
$all_points_report = array(
                            "title" => $this->lang->line("All Points")." (Last 20 Records)",
                            "column" => "first",
                            "id" => "widget11",
                            "url" => base_url()."index.php/home/all_points_report/id/".$id,
                            "editurl" => "widgets/editwidget3.html",
                            "open" => "1"
                        );						
						
						$all= array();
						$all[]=$assets_details;
						$all[]=$current_location;
						$all[]=$all_points_report;
						$all[]=$distance_travel;
						$all[]=$current_speed;
						$all[]=$distance_graph;
						$all[]=$speed_graph;
/*						
						if(in_array('7',$menu_data)){
							$all[]=$distance_travel;
						}
						if(in_array('8',$menu_data)){
							$all[]=$speed_graph;
						}
						if(in_array('9',$menu_data)){
							$all[]=$distance_graph;
						}
*/						
						$all[]=$stop_report;
						$all[]=$area_in_out_report;
						$all[]=$landmark_report;
						$all[]=$distance_wise_report;
$data["result"] = array
        (
            "layout" => "layout2",
            "data" => $all


        );
		die(json_encode($data));
?>