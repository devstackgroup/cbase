<?php

namespace CBase\Query;

use CBase\Database\Mysql\Mysql;


/**
* Query class
*/
class Query
{
	private $pdo = null;
	private $table = null;
	private $rowCount = 0;
	private $sqlQuery = null;

	private $pageNumbers = 0;
	private $currentPage = null;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = new Mysql($pdo);
	}

	public function read($column = '*')
	{
		if(is_array($column))
			$column = implode(',', $column);
		
		$table = "FROM {$this->table}";
		$this->sqlQuery = "SELECT {$column} {$table}";

		return $this;
	}

	public function get(array $fetchAttributes = [])
	{
		if(!empty($fetchAttributes)){
			switch (count($fetchAttributes)) {
				case 2:
					return $this->pdo
								->query($this->sqlQuery, $fetchAttributes['all'], $fetchAttributes['fetch']);	
				case 1:
					return $this->pdo
								->query($this->sqlQuery, $fetchAttributes['all']);				
				default:
					return $this->pdo
								->query($this->sqlQuery);
			}
		}
		return $this->pdo
					->query($this->sqlQuery)
					->get();
	}

	public function limit($limit)
	{
		$perPage = $limit;

		$this->pageNumbers = $limit = ceil($this->rowCount / $perPage);
		$this->currentPage = (isset($_GET['page']) && ($_GET['page'] > 0) && ($_GET['page'] <= $limit)) ? $_GET['page'] : 1;
		$this->sqlQuery .= " LIMIT ".(($this->currentPage - 1) * $perPage)." , {$perPage}";

		return $this;
	}

	public function orderBy($params)
	{
		if(isset($params['conditions']) && is_array($params['conditions'])){
			foreach($params['conditions'] as $key => $value){
				if(is_numeric($key)){
					$conditions[] = $value;
				}else{
					$conditions[] = $key.' = '.$value;
				}				
			}

			$this->sqlQuery .= ' WHERE '.implode(' AND ', $conditions);
		}

		if(isset($params['order']) && is_array($params['order'])){
			foreach($params['order'] as $key => $value){
				if(is_numeric($key)){
					$orders[] = $value;
				}else{
					$orders[] = $key.' '.$value;
				}	
			}

			$this->sqlQuery .=' ORDER BY '.implode(',', $orders);
		}

		return $this;
	}

	public function setTable($table)
	{
		$this->table = $table;
	}
}