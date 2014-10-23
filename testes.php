<?php

require_once 'Asouza/Dao/DB.php';
require_once 'Asouza/Dao/Filter.php';
require_once 'Asouza/Dao/Query.php';
require_once 'Asouza/Dao/Select.php';
require_once 'Asouza/Dao/Insert.php';
require_once 'Asouza/Dao/Update.php';
require_once 'Asouza/Dao/Delete.php';
require_once 'ContainerDi.php';


use helpers as h;

//ajuda no debug, retirar em produção!!!!!!!
Asouza\Dao\DB::$debug = true;

        

$db = new \PDO("mysql:host=localhost;dbname=testedao", "root", "");
$select = new Asouza\Dao\Select($db, '*', 'stdClass');

$std = new \stdClass();
        $std->nome = 'Marcia';
        $std->email = 'marcia@gmail';
        $insert = new \Asouza\Dao\Insert($db, 'usuario');
        echo "Ultimo id: " . 
                $insert->data($std)
                        ->save();
        

echo "<h2>Todos os registros de forma associativa</h2>";
$allAssoc = $select->from('usuario')->fetchAll();
var_dump($allAssoc);

echo "<h2>Query com objeto fluído</h2>";
$allObj = Asouza\Dao\Query::create($db)
		->Select('id, nome')
		->from('usuario')
		->fetchAllObject();
var_dump($allObj);

echo "<h2>Usando conteiner</h2>";
$allObj = h\ContainerDi::getObject('Asouza\Dao\Select', array('id'))
		->from('usuario')
		->fetchAllObject();
var_dump($allObj);

echo "<h2>todos os registros em forma de objetos</h2>";
$allObj = $select->from('usuario')->fetchAllObject();
var_dump($allObj);

echo "<h2>Filtrando resultados</h2>";
$filter = new \Asouza\Dao\Filter;
$filter->where('id > 1')
		->limit(2)
		->orderBy('id DESC');
$filterResult = $select->from('usuario u')->setFilter($filter)->fetchAll();
var_dump($filterResult);

echo "<h2>Inner Left</h2>";
$select->reset();
$innerLeft = $select->from('usuario u')
				->leftJoin('item i ')->on(array('i.idusuario' => 'u.id'))
				->fetchAllObject();

var_dump($innerLeft);

echo "<h2>Inner Join</h2>";
$select->reset();
$inner = $select->from('usuario u')
				->innerJoin('item i ON i.idusuario = u.id')
				->fetchAllObject();
var_dump($inner);


echo "<h2>Inserindo objeto</h2>";
$std = new \stdClass();
$std->nome = 'Marcia';
$std->email = 'marcia@gmail';
$insert = new \Asouza\Dao\Insert($db, 'usuario');
echo "Ultimo id: " . 
		$insert->data($std)
				->save();

echo "<h2>Inserindo array</h2>";
echo "Ultimo id: " . 
h\ContainerDi::getObject('Asouza\Dao\Insert',array('usuario')) 
		->data(array('nome' => 'Alex sandro',
							'email' => 'alex@agenciasalve.com.br'))
		->save();

echo "<h2>Editando</h2>";
$update = new \Asouza\Dao\Update($db, 'usuario');
echo $update->data(array('nome' => 'Alex',
						'email' => 'cicero@agenciasalve.com.br'))
		->where("id = 3")
		->save(). " registros alterados ";

echo "<h2>Deletando</h2>";
$delete = new \Asouza\Dao\Delete($db, 'usuario');
echo $delete->where('id > 3')->exec(). ' registros deletados.';




?>
