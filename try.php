<?php 

class Etzel {


function isleep($qname) {
	$x = new stdClass();
	$x->qname=$qname;
	$x->cmd = "ISLP";
    //$data = json_decode($x);
    $tempData = html_entity_decode($x);
     $cleanData = json_decode($tempData);
   // console.log(Obj.qname + Obj.cmd);
    $sock = socket_create(AF_INET, SOCK_STREAM, 0);
    socket_connect($sock , '127.0.0.1' , 80);
   if( ! socket_send ( $sock , $data , strlen($cleanData) , 0))  {
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
     
    die("Could not send data: [$errorcode] $errormsg \n");
		}
	}


 


}

$e=new Etzel();
$e->isleep("d");
 ?>