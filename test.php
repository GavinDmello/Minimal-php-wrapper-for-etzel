<?php
namespace etzelclient;
require('vendor/autoload.php');
require('wrapper.php');


$obj = new etzel("ws://127.0.0.1:8080/connect");

//for($i=0;$i<100;$i++)
//$obj->publish("dsf","sdkfg");
 //  $obj->fetch("dsf","mycallback");

$obj->publish("test1","ace");
$obj->subscribe("test1",function($data)use (&$obj){
	var_dump($data);

//$obj->acknowledge($data->qname,$data->uid);
});

$obj->startWork();
