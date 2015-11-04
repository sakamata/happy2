<?php
namespace WebSocket\Application;
class Happy2Application extends Application
{
	private $_clients = array();

	public function onConnect($client)
	{
		$id = $client->getClientId();
		$this->_clients[$id] = $client;
	}

	public function onDisconnect($client)
	{
		$id = $client->getClientId();
		unset($this->_clients[$id]);
	}

	public function onData($data, $client)
	{
		foreach($this->_clients as $sendto)
		{
			$sendto->send($data);
		}
	}
}
