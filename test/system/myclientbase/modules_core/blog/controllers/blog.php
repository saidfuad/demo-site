<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Blog extends Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper(array("url","uri"));
	}
	
	function index(){
		$this->load->database();
		$this->db->where("del_date",Null);
		$this->db->where("status",1);
		$data['total_page']= ceil($this->db->count_all_results("vts_blog_data")/10);
		$this->db->limit(10,0);
		$this->db->where("del_date",Null);
		$this->db->where("status",1);
		$this->db->order_by("blog_date", "desc"); 
		$res = $this->db->get("vts_blog_data");
		$data['page']=1;
		$data['res']=$res;
		$this->load->view('blog',$data);
	}
	function page($page){
		if($page==1){
			redirect("blog");
		}
		$this->load->database();
		$data['total_page']= ceil($this->db->count_all_results("vts_blog_data")/10);
		if($page<1){
			redirect("blog/page/1");
		}
		if($page>$data['total_page']){
			redirect("blog/page/".$data['total_page']);
		}
		$this->db->limit(10,$page*10-10);
		$this->db->where("del_date",Null);
		$this->db->where("status",1);
		$this->db->order_by("blog_date", "desc"); 
		$res = $this->db->get("vts_blog_data");
		$data['page']=$page;
		$data['res']=$res;
		$this->load->view('blog',$data);
	}
	function view($id){
		$this->load->database();
		$id= explode(" - ",$id);
		if(count($id)>1){
			$id= trim($id[0]);
			$this->db->where("id",$id);
			$this->db->where("del_date",Null);
			$this->db->where("status",1);
			$res = $this->db->get("vts_blog_data");
			if($res->num_rows()==0){
				redirect("blog");
			}else{
				$res = $res->result_Array();
				$data['row']= $res[0];
				$data['id']= $id;
				$this->db->where("blog_id",$id);
				$this->db->where("del_date",Null);
				$this->db->where("status",1);
				$data['comment'] =  $this->db->get("comment");
				$this->load->view('view',$data);
			}
		}else{
			redirect("blog");
		}

	}
	function add_comment(){
		$this->load->database();
		$date=date('Y-m-d H:i:s');
		$blog_id = $_REQUEST['blog_id'];
		$blog_comment_author = $_REQUEST['blog_comment_author'];
		$blog_comment = $_REQUEST['blog_comment'];
		$author_email = $_REQUEST['author_email'];
		$t_count = $_REQUEST['count'];
		$data = array(
			"blog_id"=>$blog_id,
			"blog_comment_author"=>$blog_comment_author,
			"blog_comment"=>$blog_comment,
			"author_email"=>$author_email,
			"add_uid"=>1,
			"add_date"=>$date,
			"status"=>1,
			
		);
		$this->db->insert("comment",$data);
		$data=array(
				"total_comment"=> "$t_count",
		);
		$this->db->where("id",$blog_id);
		$this->db->update("vts_blog_data",$data);
	}
}
?>