<?php
namespace etzelclient;
require('vendor/autoload.php');

use WebSocket\Client;

 
//$client = new Client("ws://localhost:80");
class etzel extends Client {
    private $qbacks=array();
public function __construct($uri) {
        return parent::__construct($uri);
    }

 function isleep($qname) {
    $obj = new \stdClass();
    $obj->qname=$qname;
    $obj->cmd = "ISLP";
    $data = json_encode($obj);
    parent::send($data);
    }
function publish($qname,$msg)
{
    $obj = new \stdClass();
    $obj->qname=$qname;
    $obj->cmd="PUB";
    $obj->delay=0;
    $obj->expires=0;
     $data = json_encode($obj);

    if($options->delay != 0){

        $obj->delay = $options->delay;
    }

    if($options->expires != 0){

       $obj->expires = $options->expires;
    }
     parent::send($data);

}
function acknowledge($qname,$uid)
{
    $obj = new \stdClass();
    $obj->qname=$qname;
    $obj->cmd="ACK";
    $obj->uid=$uid;
    $data=json_encode($obj);

    parent::send($data);
    $this->onmessage();

}
function onmessage(){
   $evt=parent::receive();
   $data=json_decode($evt->data);
   if($data->cmd=='awk')
   {
    $this.fetch($data->qname);

   }
   if($data->cmd=='nomsg')
   {
    $this->isleep($data->qname);
   }
   if($data->cmd=='msg'){
    $this->qbacks[$data->qname]($data->msg);
    $this->fetch($data->qname);
   }



}
function fetch($qname)
{
    $obj = new \stdClass();
    $obj->qname=$qname;
    $obj->cmd="FET";
    $data=json_encode($obj);
    parent::send($data);
   // echo "done";
}
function subsendcmd($qname)
{
    $obj = new \stdClass();
    $obj->qname=$qname;
    $obj->cmd="SUB";
    $data=json_encode($obj);
    parent::send($data);
}
function subscribe($qname,$callback)
{
    
    $this->subsendcmd($qname);
    $qbacks[$qname]=$callback;
    $this->fetch($qname);
}
}
$obj = new etzel("ws://echo.websocket.org/");
   $obj->acknowledge("dsf","mycallback");

?>