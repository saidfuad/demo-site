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
else if ($_SERVER['HTTP_HOST'] == 'vts.trackeron.com' || $_SERVER['HTTP_HOST'] == 'test.trackeron.com') {
	$lang['Company'] = 'TrackerOn';
	$lang['devindia_address'] = "Chate Global Services<br/>
	Block No. 5, Electronic Sadan, STPI-<br/>
	Software Technology Park of India,<br/>
	Ckikalthana MIDC, Aurangabad, Maharashtra, India<br/>
	Pin Number - 431210<br/>";
	$lang['contact'] = '';
	$lang['mobile'] = "";
	$lang['email'] = "sales@chateglobalservices.com";
	$lang['website'] = "www.chateglobalservices.com";
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
$lang['back'] = "de volta";

$lang['Contact No'] = "Contacto Nenhum";
$lang['Mobile No'] = "móvel Sem";
$lang['Email'] = "Email";
$lang['Website'] = "site";
$lang['Home'] = "casa";
$lang['Logout'] = "Sair";
$lang['Stop Report'] = "Relatório de parada";
$lang['Area In/Out Report'] = "Relatório Area In / Out";
$lang['Landmark Report'] = "Landmark Relatório";
$lang['Distance Report'] = "Distância Relatório";
$lang['All Point Report'] = "Todos Relatório Ponto";
$lang['All Point Map'] = "Todo ponto do mapa";
$lang['Trip Report'] = "Relatório de Viagem";
$lang['Route Break Report'] = "Route Ruptura Relatório";
$lang['Distance Graph'] = "Distância Graph";
$lang['speed'] = "velocidade";
$lang['Speed Graph'] = "velocidade Graph";
$lang['View'] = "vista";
$lang['from'] = "a partir de";
$lang['Previous'] = "anterior";
$lang['Next'] = "Next";
$lang['All Assets Map'] = "Todos os ativos Mapa";
$lang['Date'] = "data";
$lang['Assets'] = "Assets";
$lang['GridView'] = "Grid View";
$lang['MapView'] = "map View";
$lang['Area'] = "área";
$lang['No Data Found'] = "Não foram encontrados dados";
$lang['Stop Time'] = "Stop Time";
$lang['Start Time'] = "Start Time";
$lang['Address'] = "endereço";
$lang['Duration'] = "duração";
$lang['Area In Out Report'] = "Área In Out Relatório";
$lang['Area Name'] = "área Nome";
$lang['From Date'] = "A partir do dia";
$lang['To Date'] = "a Data";
$lang['Landmark Report'] = "Landmark Relatório";
$lang['Landmark Name'] = "Nome Landmark";
$lang['Date Time'] = "data Hora";
$lang['Distance'] = "distância";
$lang['Trip Report'] = "Relatório de Viagem";
$lang['Trip name'] = "nome Trip";
$lang['Distance From Route'] = "Distância De Route";
$lang['In/Out'] = "In/Out";
$lang['End Time'] = "End Time";
$lang['status'] = "estado";
$lang['Save Inspection'] = "Salvar Inspeção";
$lang['All Users'] = "Todos os usuários";
$lang['All Group'] = "Todo o Grupo";
$lang['All Zones'] = "todas as Zonas";
$lang['All Areas'] = "todas as Áreas";
$lang['All Landmark'] = "Todos Landmark";
$lang['All Owners'] = "Todos os Proprietários";
$lang['All Division'] = "Todos Divisão";
$lang['Landmark'] = "ponto de referência";
$lang['Map'] = "mapa";
$lang['Refresh After'] = "Refresque Depois";
$lang['Off'] = "fora";
$lang['On'] = "em";
$lang['search'] = "pesquisa";
$lang['No Users to View Click Here To Home'] = "Nenhum usuário para Ver Clique Aqui To Home";
$lang['No Group to View Click Here To Home'] = "No Grupo para Ver Clique Aqui To Home";
$lang['No Zone to View Click Here To Home'] = "Nenhuma zona para Ver Clique Aqui To Home";
$lang['No Area to View Click Here To Home'] = "No Espaço para Ver Clique Aqui To Home";
$lang['No Landmark to View Click Here To Home'] = "No Landmark para Ver Clique Aqui To Home";
$lang['No Owner to View Click Here To Home'] = "Nenhum proprietário para Ver Clique Aqui To Home";
$lang['No Divisition to View Click Here To Home'] = "Sem Divisition para Ver Clique Aqui To Home";


?>