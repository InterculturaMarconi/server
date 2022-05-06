<?php

/**
 * 
 */
class DB 
{
	private $pdo;

	public function getPdo()
	{
		return $this->pdo;
	}

	public function setPdo($pdo)
	{
		$this->pdo = $pdo;
	}

	public function select($fields,$table,$conditions = [],$booleans = [],$orderBy='', $limit = '')
	{
		$sql = "SELECT ";
		if($fields == '*')
			$sql .= "* FROM `" . $table ."`";
		else
		{
			foreach($fields as $f)
			{
				$sql .= "`".$f."`, ";
			}
			$sql = substr($sql,0,-1);
			$sql = rtrim($sql, ',');
            $sql .= " FROM `" . $table ."`";
		}

		if(count($conditions) > 0)
		{
			$sql .= " WHERE ";
			$z = 0;
			foreach ($conditions as $col => $val) {
				if($val != "NULL" || $val == 0)
					$sql .= " `".$col."` = '".$val."' ";
				else
					$sql .= " `".$col."` IS NULL";

				if(count($booleans) > 0 && $z < count($conditions)-1 )
				{
					$sql .= $booleans[$z]." ";
				}
				$z++;
			}
		}

		if($orderBy != '')
			$sql .= " ORDER BY `".$orderBy['columns']."` ".$orderBy['option'];

		if($limit != '')
		{
			$sql .= ' LIMIT ';

			foreach($limit as $l)
				$sql .= $l .', ';

			$sql = substr($sql,0,-2);
			$sql = rtrim($sql, ',');

		}

		$stmt = $this->pdo->prepare($sql);
		return $stmt;

	}

	public function updateComplete($modifiche,$table,$conditions = [],$booleans = [])
	{
		$sql = "UPDATE " . $table ." SET ";
		
		foreach($modifiche as $campo => $valore)
		{
			$sql .= "`".$campo."` = :" . $campo. ", ";
		}
		
		$sql = substr($sql,0,-1);
		$sql = rtrim($sql, ',');
		if(count($conditions) > 0)
		{
			$sql .= " WHERE ";
			$z = 0;
			foreach ($conditions as $col => $val) {
				if($val != "NULL")
					$sql .= " `".$col."` = '".$val."' ";
				else
					$sql .= " `".$col."` IS NULL";

				if(count($booleans) > 0 && $z < count($conditions)-1 )
				{
					$sql .= $booleans[$z]." ";
				}
				$z++;
			}
		}

		$stmt = $this->pdo->prepare($sql);

		foreach($modifiche as $campo => $valore)
			$stmt->bindValue(':'.$campo,$valore);

		return $stmt;
	}

	
	public function insert($daInserire,$table)
	{
		$sql = "INSERT INTO `" . $table ."` SET ";
		
		foreach($daInserire as $campo => $valore)
		{
			$sql .= "`".$campo."` = :" . $campo. ", ";
		}
		
		$sql = substr($sql,0,-1);
		$sql = rtrim($sql, ',');

		$stmt = $this->pdo->prepare($sql);

		foreach($daInserire as $campo => $valore)
			$stmt->bindValue(':'.$campo,$valore);

		//echo $sql;
		return $stmt;
	}

	public function delete($table,$conditions = [],$booleans = [])
	{
		$sql = "DELETE FROM " . $table;

		if(count($conditions) > 0)
		{
			$sql .= " WHERE ";
			$z = 0;
			foreach ($conditions as $col => $val) {
				if($val != "NULL")
					$sql .= " `".$col."` = '".$val."' ";
				else
					$sql .= " `".$col."` IS NULL";

				if(count($booleans) > 0 && $z < count($conditions)-1 )
				{
					$sql .= $booleans[$z]." ";
				}
				$z++;
			}
		}

		$stmt = $this->pdo->prepare($sql);

		return $stmt;
	}
}

?>