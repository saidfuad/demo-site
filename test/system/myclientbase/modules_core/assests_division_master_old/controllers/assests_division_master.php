<?php (defined("BASEPATH")) OR exit("No direct script access allowed");

			class assests_division_master extends Admin_Controller {
				
				function __construct() {
					parent::__construct(TRUE);
					$this->load->model("assests_division_master_model","",TRUE);
					$this->load->model("form_model","",TRUE);
				}
				function index(){
					$this->load->view( "assests_division_master" );
				}
				function export(){
					$this->assests_division_master_model->getAllData(); 
				}
				function loadData(){
					
					$data = $this->assests_division_master_model->getAllData(); 
					$responce = new stdClass();
					$responce->page = $data["page"];
					$responce->total = $data["total_pages"];
					$responce->records = $data["count"];
					
					
					$i=0;  
					foreach($data["result"] as $row) {  
						$responce->rows[$i] = $row;
						$i++; 
					} 
					echo json_encode($responce);
				}
				function deleteData(){
					
					echo $this->assests_division_master_model->delete_assests_division_master(); 
				}

				function form() {
					if (!$this->form_model->validate()) {

						$this->load->helper("form");

						if (!$_POST AND uri_assoc("id")) {

							$this->form_model->prep_validation(uri_assoc("id"));

						}
						$this->load->view("form");

					}
					else {
						
						$formdata = $this->form_model->db_array();
						$formdata["add_date"] = date("Y-m-d H:i:s");
						$formdata["add_uid"] = $this->session->userdata("user_id");
						$formdata["status"] = 1;
						
						if(uri_assoc("id")){
							$this->db->where("id",uri_assoc("id"));
							$res = $this->db->get("assests_division_master");
							$this->form_model->save($formdata, uri_assoc("id"));
						}else{
							$this->assests_division_master_model->save($formdata, uri_assoc("id"));
						} 
					}

				} 

			}
			?>