    <?php

require("./assets/phpgrid/lib/inc/php-export-data.class.php");

class excel_export extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /* function reformatTitle($array,$key,$key2,$newKey){
      $array[$key][$newKey] = $array[$key][$key2];
      unset($array[$key][$key2]);
      return $array;
      } */

    function reformatTitle($array, $key, $newKey) {
        $array[0][$key] = $newKey;
        //unset($array[$key][$key2]); 
        return $array;
    }

    function export() {
        $exclusions = array('No', 'no');
        $raw_json = $_POST["row_data"];
        $array = json_decode($raw_json, true);


        $result = array();
        $data = array();
        if (isset($_COOKIE["jqgrid_colchooser"])) {
            $colchooser = json_decode($_COOKIE["jqgrid_colchooser"]);


            foreach ($colchooser as $k => $val) {
                $item_key = $val;

                $data[$item_key] = $val;
            }
            array_push($result, $data);
        } else {
            $colchooser = array_keys($array[0]);
            foreach ($colchooser as $k => $val) {
                $item_key = $val;
                if (!in_array($item_key, $exclusions)) {
                    $data[$item_key] = $val;
                }
            }

            array_push($result, $data);
        }





        //$colchooser = rtrim($colchooser);
        //print_r('<pre>');
        //print_r($colchooser);
        //print_r('\n<pre>');
        //print_r($array);
        //exit;


        foreach ($array as $key => $value) {
            $data = array();
            foreach ($colchooser as $k => $val) {

                $item_key = $val;
                if (array_key_exists($item_key, $value)) {
                    if (!in_array($item_key, $exclusions)) {
                        $data[$item_key] = $value[$item_key];
                    }
                }
            }

            array_push($result, $data);
        }



        //change titles
        foreach ($result[0] as $key => $value) {
            //print_r('<pre>');
            //print_r($result[0]['no']);
            //exit;
            //foreach($value as $key2 => $value2) {
            //$newArr[ $colchooser[ $key ] ] = $value;
            //print_r('<pre>');
            //result_push($result[$key] ,result($value['ID']=>$value[$key2]));
            //$result[$key]['ID'] = $result[$key]['no'];
            //unset($result[$key]['no']);



            switch ($key) {
                case "no":
                    $result = $this->reformatTitle($result, $key, 'NO');
                    break;
                case "assets_friendly_nm":
                    $result = $this->reformatTitle($result, $key, 'VEHICLE NAME');
                    break;
                case "assets_name":
                    $result = $this->reformatTitle($result, $key, 'PLATE NUMBER');
                    break;
                case "device_id":
                    $result = $this->reformatTitle($result, $key, 'DEVICE ID');
                    break;
                case "driver_name":
                    $result = $this->reformatTitle($result, $key, 'DRIVER NAME');
                    break;
                case "driver_phone":
                    $result = $this->reformatTitle($result, $key, 'DRIVER PHONE');
                    break;
                case "assets_type_nm":
                    $result = $this->reformatTitle($result, $key, 'TYPE');
                    break;
                case "assets_cat_name":
                    $result = $this->reformatTitle($result, $key, 'CATEGORY');
                    break;
                case "assets_group_name":
                    $result = $this->reformatTitle($result, $key, 'GROUP');
                    break;
                case "max_speed_limit":
                    $result = $this->reformatTitle($result, $key, 'SPEED LIMIT');
                    break;
                case "no_of_axles":
                    $result = $this->reformatTitle($result, $key, 'NO OF AXLES');
                    break;
                case "km_reading":
                    $result = $this->reformatTitle($result, $key, 'MILIEGE');
                    break;
                case "owner_name":
                    $result = $this->reformatTitle($result, $key, 'OWNER');
                    break;  
                case "add_user_name":
                    $result = $this->reformatTitle($result, $key, 'ADD USER');
                    break;
                case "add_user_phone":
                    $result = $this->reformatTitle($result, $key, 'USER PHONE');
                    break;
                case "phone_no":
                    $result = $this->reformatTitle($result, $key, 'PHONE');
                    break;
                case "address":
                    $result = $this->reformatTitle($result, $key, 'ADDRESS');
                    break;
                case "mobile_number":
                    $result = $this->reformatTitle($result, $key, 'PHONE');
                    break;
                default:
                    break;
            }
            //}
        }
        //$colchooser = $newArr;
        //print_r('<pre>');
        //print_r($result);
        //exit;
        //print_r($result);
        $excel = new ExportDataExcel('browser');
        $excel->filename = "export.xls";

        $excel->initialize();
        foreach ($result as $row) {
            //print_r('<pre');
            //print_r($row);
            $excel->addRow($row);
        }
        $excel->finalize();
        //exit(); 
    }

}
