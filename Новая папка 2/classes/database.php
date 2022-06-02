<?php

class Database
{
  protected static $pdo = null;
   
  protected static function connect() : PDO
  {
    
    
      if (static::$pdo == null)
    	{
      	$config = include 'classes/config.php';
      	static::$pdo = new PDO('mysql:host='.($config['db_host'] ?? '127.0.0.1').';dbname='.($config['db_name']??'').'', ''.($config['db_user']??'').'', ''.($config['db_pass']??'').'');
      } 
      return static::$pdo;
   	
  }
  public static function getPdo()
  {
    static::connect();
    return static::$pdo;
  }
  public static function query($sql, $className = null)
  {
    if (!$className)
    {
      return static::getPdo()->query($sql);
    }
    else
    {
      $result = static::getPdo()->query($sql);
      return $result->fetchAll(PDO::FETCH_CLASS, $className);
    }
  }
  
  public static function prep($sql)
  {
    return static::getPdo()->prepare($sql);
  }
  
  static public function exec($sql, $sql_params=null)
  {
    if ($sql_params)
    {
      $query = static::connect()->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

      $query->execute($sql_params);
    }
    else {
      $query = static::connect()->prepare($sql);
      $query->execute();
    }

    return $query->fetchAll();
  }

  static public function add_sql($sql_query, $add)
  {
    $one = false;
    if (strpos($sql_query, "WHERE")) { $one = true; } else { $sql_query .= " WHERE "; }
    if ($one) { $sql_query .= " AND "; }

    $sql_query .= $add;

    return $sql_query;
  }
}