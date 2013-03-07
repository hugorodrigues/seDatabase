<?php
//     seDatabase.php
//     http://starteffect.com | https://github.com/hugorodrigues
//     (c) Copyright 2010-2013 Hugo Rodrigues, StartEffect U. Lda
//     This code may be freely distributed under the MIT license.

class seDatabase {
	var $pdo;

	function __construct($config){

    try {
      // Open Connection
      $this->pdo = new PDO($config['dsn'], $config['user'], $config['password'], $config['options']);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Set Attributes
      foreach ((array) $config['attributes'] as $k => $v ) 
          $this->pdo->setAttribute(constant($k),constant($v));
        
    } catch (PDOException $error) {
        $this->error($error);
    }

	}


	function error($error){
		die($error->getMessage());
	}


	function insert($table, $data){

    if ($table == '' or !is_array($data)) 
    	return $this->error('Insert - No data');

    foreach((array) $data as $field=>$value)
    {
        $fields .= "$field ,";
        $values .= ":$field ,";
    }

    $sql = "INSERT INTO $table ( ".substr($fields, 0,-1)." ) values ( ".substr($values, 0,-1)." )";
    
    $this->query($sql, $data, true);
    return $this->pdo->lastInsertId();
	}



	function update($table, $data, $where='1 = 1', $binds=array()){

		// Numerico para where
    if (is_numeric($where))
    {
      $binds = array(':id'=>$where);      
      $where = 'id = :id';
    }

    $fields = array();

    // Processa Valores/Campos
    foreach((array) $data as $field=>$value)
    {
			$fields[] = "$field = :update_$field";
			$binds[':update_'.$field] = $value;
    }


    // Cola dados
    $fields = implode(',', $fields);

    return $this->query("UPDATE $table SET $fields WHERE $where", $binds, true);
	}


	function delete($table, $where='1 = 1', $binds=array()) {

    // Numerico para where
    if (is_numeric($where))
    {
      $binds = array(':id'=>$where);      
      $where = 'id = :id';
    }

    return $this->query("DELETE FROM $table WHERE $where", $binds, true);
	}	


  public function query($sql='',$params='', $status= false)
  {

		try {
			$tmp = $this->pdo->prepare($sql);

			if (is_array($params)) 
				$result = $tmp->execute($params);
			else 
				$result = $tmp->execute();
		}
		catch(Exception $exception)
		{
			return $this->error($exception);
		}

		return ($status == false) ?  $tmp : $result;
  }


  public function getVar($sql='',$params='')
  {
      $tmp = $this->query($sql, $params);
      $res = $tmp->fetch(PDO::FETCH_NUM);
      return $res[0];
  }


  public function getRow($sql='',$params='')
  {
      $tmp = $this->query($sql, $params);
      $res =  $tmp->fetch(PDO::FETCH_ASSOC);
      return $res;
  }


  public function getResults($sql='',$params='')
  {
      $tmp =  $this->query($sql, $params);
      $res =  $tmp->fetchAll(PDO::FETCH_ASSOC);
      return $res;
  }

} 

