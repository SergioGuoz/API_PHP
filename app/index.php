<?php
error_reporting(E_ALL);
ini_set('display_errors','1');

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$request_method=$_SERVER["REQUEST_METHOD"];
date_default_timezone_set('America/Guatemala');

switch($request_method){
	
	case 'GET':
		$client = new MongoDB\Client('mongodb+srv://sergio:Mongo123@cluster0-aebc3.gcp.mongodb.net/test?retryWrites=true');

		$db = $client->arqui_usac;
		$collectionRegistro=$db->garage;
		$collectionPass=$db->pass;
		$time = date('h:i:s A'); //obtener hora formato h i s
		$date= date('l jS \of F Y ');
		$pass_received=htmlspecialchars($_GET['password'],ENT_QUOTES);
		$consulta = $collectionPass->findOne();
		if($pass_received==$consulta['password']){
			$save = array(
				"State"=>"correct",
				"Date"=>$date,
				"Time"=>$time
			);
			$collectionRegistro->insertOne($save);
			echo "1";
		}else{
			$save = array(
				"Sate"=>"wrong",
				"Date"=>$date,
				"Time"=>$time
			);
			$collectionRegistro->insertOne($save);
			echo "1";
		}
		 break;

	 case 'POST':
		 $client = new MongoDB\Client('mongodb+srv://sergio:Mongo123@cluster0-aebc3.gcp.mongodb.net/test?retryWrites=true');

		$db = $client->arqui_usac;
		$collectionRegistro=$db->garage;
		$collectionPass=$db->pass;
	 $op = htmlspecialchars($_POST["operacion"]);
	 if ($op=="1") {//guardar pass
	 	$new_pass = htmlspecialchars($_POST["password"]);
	 	$change   = array('user' => 'root','password'=>$new_pass);
	 	$old = array('user' => 'root','password' => '12345');
		$consulta = $collectionPass->replaceOne($old,$change);
	 }else{//

	 	$consulta = $collectionRegistro->find();
	 	echo "{ \"datos\":[ ";
		foreach ($consulta as $doc) {
		    echo json_encode($doc);
		    echo ", \n";
		}
		echo json_encode($doc);
		echo "] }";
	 }
	 break;
 default:
	 header("HTTP/1.0 405 Method Not Allowed");
	 break;
 }
	
?>