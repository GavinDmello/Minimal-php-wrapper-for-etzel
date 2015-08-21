<?php
namespace etzelclient;
require('vendor/autoload.php');

use WebSocket\Client;

 
//$client = new Client("ws://localhost:80");
class etzel extends Client {
    public function __construct($uri) {
        $this->fragment_count = array(
            'send' => 0,
            'receive' => 0,
        );
        return parent::__construct($uri);
    }

 function isleep($qname) {
    $obj = new \stdClass();
    $obj->qname=$qname;
    $obj->cmd = "ISLP";
    $data = json_encode($obj);
        $client = new Client("ws://echo.websocket.org/");
         $client->send($data);
         echo "message was sent successfully bitch";
    }
function publish($qname,$msg,$options)
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
   // $client = new Client("ws://echo.websocket.org/");
     $client->send($data);
     echo "message was published successfully bitch";

}
function acknowledge($qname,$uid)
{
    $obj = new \stdClass();
    $obj->qname=$qname;
    $obj->cmd="ACK";
    $obj->uid=$uid;
    $data=json_encode($obj);

    // $client = new Client("ws://echo.websocket.org/");
     $client->send($data);
     echo "message was acknowledged successfully bitch";

}
function fetch($qname)
{
    $obj = new \stdClass();
    $obj->qname=$qname;
    $obj->cmd="FET";
    $data=json_encode($obj);

     //$client = new Client("ws://echo.websocket.org/");
     $client->send($data);
   
 echo "done";

}
}
$obj = new etzel("ws://echo.websocket.org/");
   $obj->fetch("ds");

?>