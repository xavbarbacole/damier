<?php

class DB{
	private $pdo;
	private $st = null;
	private static $db = null;//objet instance unique - singleton


	public function __construct(){
		try{
			$this->pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD, array(
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET names utf8'
				));
		}
		catch(Exception $e){
			die("Une erreur s'est produite lors de la connexion à la base de données");
		}
	}

	//SINGLETON
	public static function getDB(){
		if (!(self::$db instanceof self)){//cf. http://fr.wikipedia.org/wiki/Singleton_(patron_de_conception)#PHP_5
			self::$db = new self();
		}

		return self::$db;
	}

	public function getPDO(){
		return $this->pdo;
	}

	//volontairement pas traité les paramètres en ?
	public function query($q, $params = array()){
		if(!empty($this->pdo)){
			$this->st = $this->pdo->prepare($q);
			if(!empty($params)){
				foreach($params as $p => $val){
					if(is_array($val) && count($val) == 2){//c'est si l'utilisateur a passé un PDO::PARAM_STR ou PDO::PARAM_INT
						$this->st->bindValue($p, $val[0], $val[1]);
					}
					else{
						$this->st->bindValue($p, $val);
					}
				}
			}

			$this->st->execute();
			return $this->st;
		}
		else return null;
	}

	public function numrows($statement = null){
		if( is_null($statement) && ! is_null($this->st)){
			$statement = $this->st;
		}
		return $statement->rowCount();
	}

	public function fetchrow($statement = null, $mode = PDO::FETCH_ASSOC){
		if( is_null($statement) && ! is_null($this->st)){
			$statement = $this->st;
		}

		return $statement->fetch($mode);
	}
}
