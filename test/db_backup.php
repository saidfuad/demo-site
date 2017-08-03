<?php

$db_host = localhost ; // Here enter the databse host name.
$db_user = root ; // Here enter the username to access your database.
$db_pass = 'tracker2013' ; // Here enter the password to access your database.
$db_name = 'trackeron' ; // Here enter the database name, of which you want to take the backup.
$zip = new ZipArchive();
$dir = "site-backup-stark";
// call the backup_db function.
backup_db($db_host,$db_user,$db_pass,$db_name);


/**
* @desc : create database backup (.sql file) of the database
* @param : $db_host(string),$db_user(string),$db_pass(string),$db_name(string),$tables(by default it will take '*' for all tables in db), $drop(bool)
* @return : void
**/

/* backup the db OR just a table */
function backup_db($host,$user,$pass,$name,$tables = '*')
{
$con = mysql_connect($host,$user,$pass);
mysql_select_db($name,$con);

//get all of the tables
if($tables == '*')
{
$tables = array();
$result = mysql_query('SHOW TABLES');
while($row = mysql_fetch_row($result))
{
$tables[] = $row[0];
}
}
else
{
$tables = is_array($tables) ? $tables : explode(',',$tables);
}
unset($tables['tbl_track']);
$return = "";

//cycle through
foreach($tables as $table)
{
$result = mysql_query('SELECT * FROM '.$table);
$num_fields = mysql_num_fields($result);
$return.= 'DROP TABLE '.$table.';';
$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
$return.= "nn".$row2[1].";nn";

while($row = mysql_fetch_row($result))
{
$return.= 'INSERT INTO '.$table.' VALUES(';
for($j=0; $j<$num_fields; $j++)
{
$row[$j] = addslashes($row[$j]);
$row[$j] = preg_replace("#n#","n",$row[$j]);
if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
if ($j<($num_fields-1)) { $return.= ','; }
}
$return.= ");n";
}
$return.="nnn";
}

//save file
$handle = fopen('db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
fwrite($handle,$return);
fclose($handle);
}

if (glob("*.sql") != false)
{
$filecount = count(glob("*.sql"));
$arr_file = glob("*.sql");

for($j=0;$j<$filecount;$j++)
{
$res = $zip->open($arr_file[$j].".zip", ZipArchive::CREATE);
if ($res === TRUE)
{
$zip->addFile($arr_file[$j]);
$zip->close();
unlink($arr_file[$j]);
}
}
}
//get the current folder name-start
$path = dirname($_SERVER['PHP_SELF']);
$position = strrpos($path,'/') + 1;
$folder_name = substr($path,$position);


//get the current folder name-end
$zipname = date('Y/m/d');
$str = "stark-".$zipname.".zip";
$str = str_replace("/", "-", $str);
// open archive
if ($zip->open($str, ZIPARCHIVE::CREATE) !== TRUE) {
die ("Could not open archive");
}
// initialize an iterator
// pass it the directory to be processed
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("../$folder_name/"));
// iterate over the directory
// add each file found to the archive

foreach ($iterator as $key=>$value) {
if( strstr(realpath($key), "db") == FALSE) {
$zip->addFile(realpath($key), $key) or die ("ERROR: Could not add file: $key");
}

}
// close and save archive
$zip->close();

//get the array of zip files
if(glob("*.zip") != false) {
$arr_zip = glob("*.zip");
}

//copy the backup zip file to site-backup-stark folder
foreach ($arr_zip as $key => $value) {
print_r($key);
print_r($value);
die;
if (strstr($value, "db")) {
$delete_zip[] = $value;
copy("$value", "$dir/$value");
}
}
for ($i=0; $i < count($delete_zip); $i++) {
unlink($delete_zip[$i]);
}
?>