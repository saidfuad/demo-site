<?php
$lang=array();
$lang['english'] = "English";
$lang['gujarati'] = "ગુજરાતી";
$lang['hindi'] = "હિન્દી";
$lang['Live Map'] = "Live Map";
$lang['Reports'] = "Reports";
$lang['About Us'] = "About Us";
$lang['Contact Us'] = "Contact Us";

if($_SERVER['HTTP_HOST'] == 'omex.nkonnect.com') {
	$lang['Company'] = 'OmexSol';
	$lang['devindia_address'] = "";
	$lang['contact'] = '';
	$lang['mobile'] = "";
	$lang['email'] = "";
	$lang['website'] = "";
}
else if ($_SERVER['HTTP_HOST'] == 'vts.trackeron.com') {
	$lang['Company'] = 'TrackerOn';
	$lang['devindia_address'] = "TrackerOn<br/>
	451/9, Office No. 84.<br/>
	Prashant Building,<br/>
	Vishrant Wadi-Lohegaon Road,<br/>
	Pune, Maharashtra<br/>
	India.<br/>";
	$lang['contact'] = '';
	$lang['mobile'] = "";
	$lang['email'] = "sales@trackeron.com";
	$lang['website'] = "www.trackeron.com";
}
else if ($_SERVER['HTTP_HOST'] == 'vehicle.worldwidetrackingservices.com') {
	$lang['Company'] = 'Worldwide Tracking Services';
	$lang['devindia_address'] = "S3TechnoServices<br/>
	451/9, Office No. 84.<br/>
	Prashant Building,<br/>
	Vishrant Wadi-Lohegaon Road,<br/>
	Pune, Maharashtra<br/>
	India.<br/>";
	$lang['contact'] = '';
	$lang['mobile'] = "";
	$lang['email'] = "sales@worldwidetrackingservices.com";
	$lang['website'] = "www.worldwidetrackingservices.com";
}
else {
	$lang['Company'] = "NKonnect";
	$lang['devindia_address'] = "'VINOD', 4/6 Kishanpara,<br/>
	Gaurav path,<br/>
	Near Kishanpara Chowk,<br/>
	Rajkot 360 001, Gujarat INDIA.<br/>";
	$lang['contact'] = '+91 281 2 45 84 49';
	$lang['mobile'] = "+91 97141 25000, +91 98240 84414";
	$lang['email'] = "info@nkonnect.com";
	$lang['website'] = "www.nkonnect.com";
}
$lang['about_us_1'] = $lang['Company'] . " is research based Innovative, next generation technology product developing company.";
$lang['about_us_2'] = "We are always open to execute ideas that bring positive change to human lives & environment.";
$lang['about_us_3'] = "We are mainly focusing on developing innovative, affordable & reliable solutions.";
$lang['about_us_4'] = "From cloud to consumer electronics, we are working in computer, mobile, embedded & cloud based solution development.";
$lang['back'] = "Back";

$lang['Contact No'] = "Contact No";
$lang['Mobile No'] = "Mobile No";
$lang['Email'] = "Email";
$lang['Website'] = "Website";
$lang['Home'] = "Home";
$lang['Logout'] = "Logout";
$lang['Stop Report'] = "Stop Report";
$lang['Area In/Out Report'] = "Area In/Out Report";
$lang['Landmark Report'] = "Landmark Report";
$lang['Distance Report'] = "Distance Report";
$lang['Trip Report'] = "Trip Report";
$lang['Route Break Report'] = "Route Break Report";
$lang['Distance Graph'] = "Distance Graph";
$lang['Speed Graph'] = "Speed Graph";
$lang['View'] = "View";
$lang['from'] = "from";
$lang['Previous'] = "Previous";
$lang['Next'] = "Next";
$lang['Date'] = "Date";
$lang['Assets'] = "Assets";

$lang['No Data Found'] = "No Data Found";
$lang['Stop Time'] = "Stop Time";
$lang['Start Time'] = "Start Time";
$lang['Address'] = "Address";
$lang['Duration'] = "Duration";
$lang['Area In/Out Report'] = "Area In Out Report";
$lang['Area Name'] = "Area Name";
$lang['From Date'] = "From Date";
$lang['To Date'] = "To Date";
$lang['Landmark Report'] = "Landmark Report";
$lang['Landmark Name'] = "Landmark Name";
$lang['Date Time'] = "Date Time";
$lang['Distance'] = "Distance";
$lang['Trip Report'] = "Trip Report";
$lang['Trip name'] = "Trip name";
$lang['Distance From Route'] = "Distance From Route";
$lang['In/Out'] = "In/Out";
$lang['End Time'] = "End Time";
?>