<?php 

require_once("db.php"); 

function insert($table, $data){
	if(count($data)>0){
		$q="INSERT INTO $table";
		$v=''; $n='';
		$date_pattern = '/^(?P<dd>[0-9][0-9])\.(?P<mm>[0-9][0-9])\.(?P<yy>[0-9][0-9][0-9][0-9])$/';
		foreach($data as $key=>$val) {
			$n.="`$key`, ";
			if(strtolower($val)=='null') $v.="NULL, ";
			elseif(strtolower($val)=='now()') $v.="NOW(), ";
			elseif(preg_match($date_pattern, $val, $dates)){
					$v 	.= "'".mysql_date_format($dates["yy"]."-".$dates["mm"]."-".$dates["dd"])."',";
				}
		
			else $v.= "'".$val."', ";
		}
	
		$q .= "(". rtrim($n, ', ') .") VALUES (". rtrim($v, ', ') .");";
		$query = mysql_query($q) or generateMSG(NULL, $q);
		if($query){
			return true;
		}else{
			return false;
		}
	}
}
function update($table, $data, $where='1'){
	$q="UPDATE `".$table."` SET ";

	// for each data value generate value for each field like ID=1, Name=Vimal etc
	foreach($data as $key=>$val) {
		if(strtolower($val)=='null') $q.= "`$key` = NULL, ";
		elseif(strtolower($val)=='now()') $q.= "`$key` = NOW(), ";
		else $q.= "`$key`='".$val."', ";
	}
	// put where condition, for updating particular records, by default where condition is blank means update all records
	$q = rtrim($q, ', ') . ' WHERE '.$where.';';
	
	$query = mysql_query($q);
	if($query){
		return true;
	}else{
		return false;
	}
}
function constructWhere($s, $table_name = ""){
    $qwery = "";
	//['eq','ne','lt','le','gt','ge','bw','bn','in','ni','ew','en','cn','nc']
    $qopers = array(
				  'eq'=>" = ",
				  'ne'=>" <> ",
				  'lt'=>" < ",
				  'le'=>" <= ",
				  'gt'=>" > ",
				  'ge'=>" >= ",
				  'bw'=>" LIKE ",
				  'bn'=>" NOT LIKE ",
				  'in'=>" IN ",
				  'ni'=>" NOT IN ",
				  'ew'=>" LIKE ",
				  'en'=>" NOT LIKE ",
				  'cn'=>" LIKE " ,
				  'nc'=>" NOT LIKE " );
    if ($s) {
        $jsona = json_decode($s,true);
		if($jsona['rules'][0]['field']=='date_time')
		{
			$jsona['rules'][0]['field'] = "date(".$jsona['rules'][0]['field'].")";
			$jsona['rules'][0]['data'] = date('Y-m-d',strtotime($jsona['rules'][0]['data']));;
		}
        if(is_array($jsona)){
			$gopr = $jsona['groupOp'];
			$rules = $jsona['rules'];
            $i =0;
            foreach($rules as $key=>$val) {
				// echo $val['field'], ", ";
				$pos = strpos($val['field'], ".");
                if($pos == false || $pos == "") {
					$field = $table_name.$val['field'];
				}
				else {
					$field = $val['field'];
				}
				// echo ", [$pos], $field";
                $op = $val['op'];
                $v = $val['data'];
				if($v && $op) {
	                $i++;
					// ToSql in this case is absolutley needed
					$v = ToSql($field,$op,$v);
					if ($i == 1) $qwery = " AND ";
					else $qwery .= " " .$gopr." ";
					switch ($op) {
						// in need other thing
					    case 'in' :
					    case 'ni' :
					        $qwery .= $field.$qopers[$op]." (".$v.")";
					        break;
						default:
					        $qwery .= $field.$qopers[$op].$v;
					}
				}
            }
        }
    }
	
    return $qwery;
}
function ToSql ($field, $oper, $val) {
	// we need here more advanced checking using the type of the field - i.e. integer, string, float
	switch ($field) {
		case 'id':
			return intval($val);
			break;
		case 'amount':
		case 'tax':
		case 'total':
			return floatval($val);
			break;
		default :
			//mysql_real_escape_string is better
			if($oper=='bw' || $oper=='bn') return "'" . addslashes($val) . "%'";
			else if ($oper=='ew' || $oper=='en') return "'%" . addcslashes($val) . "'";
			else if ($oper=='cn' || $oper=='nc') return "'%" . addslashes($val) . "%'";
			else return "'" . addslashes($val) . "'";
	}
}


function Strip($value)
{
	if(get_magic_quotes_gpc() != 0)
  	{
    	if(is_array($value))  
			if ( array_is_associative($value) )
			{
				foreach( $value as $k=>$v)
					$tmp_val[$k] = stripslashes($v);
				$value = $tmp_val; 
			}				
			else  
				for($j = 0; $j < sizeof($value); $j++)
        			$value[$j] = stripslashes($value[$j]);
		else
			$value = stripslashes($value);
	}
	return $value;
}
function array_is_associative ($array)
{
    if ( is_array($array) && ! empty($array) )
    {
        for ( $iterator = count($array) - 1; $iterator; $iterator-- )
        {
            if ( ! array_key_exists($iterator, $array) ) { return true; }
        }
        return ! array_key_exists(0, $array);
    }
    return false;
}
function mysql_date_format($value) {

	if (gettype($value) == 'string') $value = strtotime($value);
	return date(MYSQL_DATE, $value);

}

function generateMSG($id, $msg, $result=false) {
	if($result == false) {
		$data["result"] = $result;
		$data["eid"] = $id;
		$data["error"] = $msg;
	}
	else {
		$data["result"] = "true";
		$data["msg"] = $msg;
	}
	die(json_encode($data));
}

function final_result($message){
	die('{"result":"false","message":"'.$message.'"}');
}

function checkMax($x1, $x2, $id, $error){
	$remainder = $x1 - $x2;
	if($remainder > 0){
		generateMSG($id,"$error");
	}
	else {
		// Return if you want
	}
}
function final_result_xml($theString,$bool,$SQLError) {
	//$MySql_string = str_replace("'","''",$SQLError);
	die("<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n<root>\n<result>$bool</result>\n<message>$theString</message>\n</root>");
}
?>