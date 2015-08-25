<?php
namespace etzelclient;
require('vendor/autoload.php');

use WebSocket\Client;

 
//$client = new Client("ws://localhost:80");
class etzel extends Client {


	private $qbacks=array();
	public $inc=0;

	public function __construct($uri) {
		return parent::__construct($uri,['timeout'=>3600*6]);
	    }

	 function isleep($qname) {
	    $obj = new \stdClass();
	    $obj->qname=$qname;
	    $obj->cmd = "ISLP";
	    $data = json_encode($obj);
	    parent::send($data);
	    }
	    
	function publish($qname,$msg,$options=null)
	{
	    $obj = new \stdClass();
	    $obj->qname=$qname;
	    $obj->msg=$msg;
	    $obj->cmd="PUB";
	    $obj->delay=0;
	    $obj->expires=0;
	    

	    if(isset($options->delay) && $options->delay != 0){

		$obj->delay = (int)$options->delay;
	    }

	    if(isset($options->expires) && $options->expires != 0){

	       $obj->expires = (int)$options->expires;
	    }
	    
	    
	     $data = json_encode($obj);
	     echo $data;
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
	   // $this->onmessage();

	}
	
	function work(){
	   $evt=parent::receive();
	   $data=json_decode($evt);
	   
	   if(!isset($data->cmd)){
	   	return false;
	   }
	   
	   if($data->cmd=='awk')
	   {
	    $this->fetch($data->qname);

	   }
	   
	   if($data->cmd=='nomsg')
	   {
	    $this->isleep($data->qname);
	   }
	   
	   
	   if($data->cmd=='msg'){
	   
	    $func=$this->qbacks[$data->qname];
	    call_user_func($func,$data);
	    $this->fetch($data->qname);
	   }


		return true;
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
	    $this->qbacks[$qname]=$callback;
	    $this->fetch($qname);
	}
	
	
	function test(){
	
		$this->inc+=1;
		echo $this->inc." <- count\n";
		return $this->inc;
	}


	function startWork()
	{
		while($this->work());

	}

}


