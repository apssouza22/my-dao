<?php
define(DB_NOME, 'blowtex');
define(DB_SENHA, '');
define(DB_USUARIO, 'root');
define(DB_HOST, 'localhost');
define(DB_PORTA, '');

require_once 'database/DB.php';
require_once 'database/Delete.php';
require_once 'database/Select.php';
require_once 'database/Insert.php';
require_once 'database/Update.php';
require_once 'database/Query.php';




$insert = Query::create()
		->insert('amostras_chupa')
		->data(array(
			'nome' => "Alex teste2",
			'bairro' => "tete2",
			'cidade' => null
		),true)
		->set('email', "teste@teste2")
		->set('telefone', "276184253")
		->getQuery();
	//	->save();
echo $insert;

$update = Query::create()
		->update('amostras_chupa')
		->data(array(
			'nome' => "marcia souza ",
			'bairro' => "'sucego'",
			'cidade' =>null
		),false)
		->set('email', "teste@teste")
		->set('telefone', "76184253")
		->where('id = :id', array(
			'id'=> 41
		))
		//->getQuery();
		->save();
echo $update;
		

$query = Query::create()
		->delete('amostras_chupa')
		->where("id  < :id",array('id'=>40))
		->exec();


$select = Query::create('DB')
		->select()
		->from('amostras_chupa')
		->where('id= :id',array(
			'id'=>38
		))
		->limit(5)
		->groupBy('bairro')
		->orderBy('cidade')
		//->getQuery();

//		->fetchOne()
//		->fetchObject()
//		->fetchAll()
		->fetchObjectAll();

var_dump($select);
?>
