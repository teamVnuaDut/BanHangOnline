<?php
	
	require_once dirname(__FILE__).'/MC_Rest_Base.php';
	
	class MC_Rest extends MC_Rest_Base
	{
		var $name = '';

		var $id = null;

		function __construct( $api_key )
		{	
			$dc = explode('-', $api_key);
			$dc = end($dc);
			parent::__construct($dc);

			$this->apiKey = $api_key;

			$this->path = $this->url . $this->name . '/';
		}
		function getAll( $data = null )
		{
			$this->request = '';
			return $this->execute( 'GET', $data );
		}
	}

?>