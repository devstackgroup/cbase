<?php

namespace CBase\Database\Mysql;

/**
* Mysql class
*/
final class Mysql
{
	private $isConnect = false;
	private $pdo = null;

	private $rowCount = 0;
	private $sqlQuery = "";
	private $sqlResult = [];
	
	private $fetchModeArray = [
		'assoc' => PDO::FETCH_ASSOC,
		'both'	=> PDO::FETCH_BOTH,
		'num'	=> PDO::FETCH_NUM,
		'obj' 	=> PDO::FETCH_OBJ
	];

	public function __construct(PDO $pdo)
	{
		return $this->connect($pdo);
	}

	public function isConnected()
	{
		return $this->isConnect;
	}

	public function connect(PDO $pdo)
	{
		if(!empty($pdo)){
			$this->pdo = $pdo;
			$this->isConnect = true;
			return $pdo;
		}
		return false;
	}

	public function disconnect()
	{
		if(isset($this->isConnect)){
			if($this->pdo->close()){
				$this->isConnect = false;
				return true;
			} else {
				return false;
			}
		}
	}

	public function query($statement,  $fetchAll = true, $fetchMode = 'obj')
	{
		$this->sqlQuery =  $this->pdo
								->query($statement);

		if(isset($this->sqlQuery)){
			$this->rowCount =  $this->sqlQuery
									->rowCount();

			if($this->isCUD($statement,['INSERT','UPDATE','DELETE']) || ($fetchAll && $fetchMode === 'obj')){
				$this->sqlResult = $this->sqlQuery;
				return $this;
			} 

			if($fetchMode !== 'obj' && !array_key_exists($fetchMode, $this->fetchModeArray))
				$fetchMode = 'obj';
			 
			if($fetchAll || !is_bool($fetchAll))
				$this->sqlResult = $this->sqlQuery
										->fetchAll($this->fetchModeArray[$fetchMode]);
			else
				$this->sqlResult = $this->sqlQuery
										->fetch($this->fetchModeArray[$fetchMode]);

			return $this->sqlResult;
		}

		array_push($this->sqlResult, $this->pdo->errorInfo());
		return $this;
	}

	public function prepare($statement, array $attributes, $fetchAll = true, $fetchMode = 'obj')
	{
		$this->sqlQuery =  $this->pdo
								->query($statement);
		$executeResponse = $this->sqlQuery
							 	->execute($attributes);

		if(isset($this->sqlQuery)){
			$this->rowCount =  $this->sqlQuery
									->rowCount();

			if($this->isCUD($statement,['INSERT','UPDATE','DELETE'])){
				return $executeResponse;
			} 

			if($fetchMode !== 'obj' && !array_key_exists($fetchMode, $this->fetchModeArray))
				$fetchMode = 'obj';
			 
			if($fetchAll || !is_bool($fetchAll))
				$this->sqlResult = $this->sqlQuery
										->fetchAll($this->fetchModeArray[$fetchMode]);
			else
				$this->sqlResult = $this->sqlQuery
										->fetch($this->fetchModeArray[$fetchMode]);

			return $this->sqlResult;
		}

		array_push($this->sqlResult, $this->pdo->errorInfo());
		return $this;	
	}

	public function get()
	{
		return $this->sqlResult;
	}

	public function getRowCount()
	{
		return $this->rowCount;
	}

	public function getLastQuery()
	{
		return $this->sqlQuery;
	}

	public function lastInsertId()
	{
		return $this->pdo->lastInsertId();
	}

	private function isCUD($statement, array $queryType)
	{
		foreach ($queryType as $type) {
			if(strpos($statement, $type) === 0)
				return true;
		}
		return false;
	}
}