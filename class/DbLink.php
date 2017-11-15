<?php

class DbLink
{
	public $link;
	public $table;
	
	public function __construct(string $table = NULL)
	{
		@$this->link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB);
		
		if ($this->link === NULL) {
			throw new Exception("Não foi possível estabelecer conexão com o banco de dados.");
		}
		
		if ($table)
			$this->table = $table;
	}
	
	public function setData(array $keyValues)
	{
		$query = 'INSERT INTO ' . $this->table . ' ';
		
		$keys = array_keys($keyValues);
		
		$query .= '(`'. implode('`,`', $keys) . '`) VALUES (';
		
		foreach ($keyValues as $value) {
			$query .= '\''.$this->link->escape_string($value).'\',';
		}
		
		$query = substr($query, 0, -1) . ')';
		
		return $this->execute($query);
	}
	
	public function getData(array $keys = array(), string $condition = '')
	{
		$query = 'SELECT ';
		
		if (empty($keys))
			$query .= '* ';
		else
			$query .= '`'. implode('`,`', $keys) . '` ';
		
		$query .= 'FROM ' . $this->table . ' ' . $condition;
		
		return $this->execute($query);
	}
	
	private function execute(string $query)
	{
		$dataset = $this->link->query($query);
		
		if (!$dataset) {
			throw new Exception("Ocorreu um erro durante a consulta.");
		}
		
		return $dataset;
	}
}