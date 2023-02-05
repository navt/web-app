<?php
class PlainDB
{
	private $connectDB;

	public function __construct($dbHost, $dbUser, $dbPass, $dbName, $charset="utf8mb4"){
		$this->connectDB = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
		if ($this->connectDB->connect_errno){
			throw new Exception('Connection to MySQL server failed. Error message:' . $this->connectDB->connect_error);
		}
		$this->connectDB->set_charset($charset);
	}
	public function query($q){
		$result = $this->connectDB->query($q);
		if ($result === false){
			throw new Exception('The request failed. ' . $this->connectDB->error);
		} else {
			return $result;
		}
	}
	public function getConnect(){
		return $this->connectDB;
	}
	public function close(){
		$this->connectDB->close();
	}
}
