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
$lang['back'] = "Voltar";

$lang['Contact No'] = "Nenhum Contato";
$lang['Mobile No'] = "Sem mobile";
$lang['Email'] = "E-mail";
$lang['Website'] = "Site";
$lang['Home'] = "Inicio";
$lang['Logout'] = "Sair";
$lang['Stop Report'] = "Relatório de Paradas";
$lang['Area In/Out Report'] = "Relatório de Area In / Out";
$lang['Landmark Report'] = "Relatório de Pontos de Referência";
$lang['Distance Report'] = "Relatório de Distância Percorrida (KM)";
$lang['All Point Report'] = "Rastrear Ponto a Ponto";
$lang['All Point Map'] = "Todos Pontos do Mapa";
$lang['Trip Report'] = "Relatório de Viagem";
$lang['Route Break Report'] = "Relatório de Parada Rota ";
$lang['Distance Graph'] = "Gráfico de Distância ";
$lang['speed'] = "velocidade";
$lang['Speed Graph'] = "Gráfico de Velocidade";
$lang['View'] = "Vista";
$lang['from'] = "a partir de";
$lang['Previous'] = "Anterior";
$lang['Next'] = "Próximo";
$lang['All Assets Map'] = "Todos os Ativos no Mapa";
$lang['Date'] = "Data";
$lang['Assets'] = "Ativos";
$lang['GridView'] = "Grid View";
$lang['MapView'] = "Map View";
$lang['Area'] = "Área";
$lang['No Data Found'] = "Não Foram Encontrados Dados";
$lang['Stop Time'] = "Hora de Parada";
$lang['Start Time'] = "Hora de Partida";
$lang['Address'] = "Endereço";
$lang['Duration'] = "Duração";
$lang['Area In Out Report'] = "Relatório de Área In / Out";
$lang['Area Name'] = "Nome da Área";
$lang['From Date'] = "Data Inicial ";
$lang['To Date'] = "Data Final ";
$lang['Landmark Report'] = "Relatório de Pontos de Referência ";
$lang['Landmark Name'] = "Nome do Ponto de Referência";
$lang['Date Time'] = "Data Hora";
$lang['Distance'] = "Distância";
$lang['Trip Report'] = "Relatório de Viagem";
$lang['Trip name'] = "Nome da Viagem";
$lang['Distance From Route'] = "Distância de Rota";
$lang['In/Out'] = "In/Out";
$lang['End Time'] = "End Time";
$lang['status'] = "Estado";
$lang['Save Inspection'] = "Salvar Inspeção";
$lang['All Users'] = "Todos os Usuários ";
$lang['All Group'] = "Todos os Grupos";
$lang['All Zones'] = "Todas as Zonas";
$lang['All Areas'] = "Todas as Áreas";
$lang['All Landmark'] = "Todos os Pontos de Referência";
$lang['All Owners'] = "Todos os Proprietários";
$lang['All Division'] = "Todas as Divisões";
$lang['Landmark'] = "Ponto de Referência";
$lang['Map'] = "Mapa";
$lang['Refresh After'] = "Atualizar em";
$lang['Off'] = "Desligado";
$lang['On'] = "Ligado";
$lang['search'] = "Pesquisar";
$lang['No Users to View Click Here To Home'] = "Nenhum usuário para ver clique aqui para inicio";
$lang['No Group to View Click Here To Home'] = "Nenhum Grupo para Ver Clique Aqui para inicio";
$lang['No Zone to View Click Here To Home'] = "Nenhuma zona para ver Clique Aqui para inicio";
$lang['No Area to View Click Here To Home'] = "No Espaço para Ver Clique Aqui para inicio";
$lang['No Landmark to View Click Here To Home'] = "No Landmark para Ver Clique Aqui para inicio";
$lang['No Owner to View Click Here To Home'] = "Nenhum proprietário para Ver Clique Aqui para inicio";
$lang['No Divisition to View Click Here To Home'] = "Sem Divisão para Ver Clique Aqui para inicio";
$lang['Assets Details'] = "Detalhes do Ativos";
$lang['Engine Off'] = "Ignição Desligada";
$lang['Engine On'] = "Ignição Ligada";
$lang['Running'] = "Em Movimento";
$lang['Parked'] = "Estacionado";
$lang['Out of Network'] = "Sem Comunicação de Rede";
$lang['Device Fault'] = "falha no aparelho";
$lang['Device Not Found'] = "dispositivo não encontrado";
$lang['Address ON'] = "endereço em";
$lang['Address OFF'] = "endereço fora";
$lang['Columns to display...'] = "Mostrar Colunas";

?>