# Meu DAO 
=======================================

Estrutura simples de classes para abstração de banco de dados
* Quase zero configuração;
* Bem testada e com testes unitários;
* Adaptável para diferentes banco de dados; 
* Foi criado seguindo referências de outros ORMs (Zend DB e Doctrine);
* Estrutura simples com apenas 7 classes.

Criando  query object
----------------------------

```php
<?php	$db = new \PDO("mysql:host=localhost;dbname=testedao", "root", "");
		$select = new Asouza\Dao\Select($db, 'columns', 'stdClass');
		$insert = new \Asouza\Dao\Insert($db, 'table');
		$update = new \Asouza\Dao\Update($db, 'table');
		$delete = new \Asouza\Dao\Delete($db, 'table');
```
	Ou 
	
```php
<?php	$db = new \PDO("mysql:host=localhost;dbname=testedao", "root", "");
		$select = Asouza\Dao\Query::create($db)->select('id, nome');
		$insert = Asouza\Dao\Query::create($db)->insert('table');
		$update = Asouza\Dao\Query::create($db)->update('table');
		$delete = Asouza\Dao\Query::create($db)->delete('table');
```

### Tabela de exemplo

Para os exemplos abaixo, considere a seguinte tabela:

```sql
   CREATE TABLE `usuario` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `nome` varchar(45) DEFAULT NULL,
			  `email` varchar(45) DEFAULT NULL,
			  `datacadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1
```
	
### Fetching
Listando todos os registros do banco:
```php
<?php	
		$result = Asouza\Dao\Query::create($db)
					->Select('id, nome')
					->from('usuario')
					->fetchAllObject();
		var_dump($result);

```
	
Filtrando registros:
```php
<?php	
		$filter = new Asouza\Dao\Filter();
		$filter->where('id > 2')->orderBy('id DESC');
		$result = Asouza\Dao\Query::create($db)
					->Select('id, nome')
					->from('usuario')
					->setFilter($filter)
					->fetchAllObject();
		var_dump($result);

```
	
### Prepare Statement

Usando prepare statement do PDO para tratar parametros:
```php
$result = Asouza\Dao\Query::create($db)
					->Select('id, nome')
					->from('usuario')
					->where('id = :id AND nome= :nome', array(
						'id'=> 2,
						'nome'=> 'João'
					))
					->fetchAllObject();
		var_dump($result);
```
	
### Joins:
```php
<?php	
		$select = new Asouza\Dao\Select($db, '*');
		$innerLeft = $select->from('usuario u')
				->leftJoin('item i ')->on(array('i.idusuario' => 'u.id'))
				->fetchAllObject();

		var_dump($innerLeft);
		
		$select->reset();//limpando filtros anteriores
		$inner = $select->from('usuario u')
				->innerJoin('item i ON i.idusuario = u.id')
				->fetchAllObject();
		var_dump($inner);

```

### Inserindo registros

Inserindo objeto:
```php
<?php	
		$std = new \stdClass();
		$std->nome = 'Marcia';
		$std->email = 'marcia@gmail';
		$insert = new \Asouza\Dao\Insert($db, 'usuario');
		echo "Ultimo id: " . 
				$insert->data($std)
						->save();

```
	
Inserindo array:
```php
<?php	
	$insert = new \Asouza\Dao\Insert($db, 'usuario');
	echo "Ultimo id inserido: " . 
			$insert->data(array(
					'nome' => 'Alex sandro',
					'email' => 'apssouza22@gmail.com'
					))->save();

```
	
### Atualizando registros
```php
<?php	
	$update = new \Asouza\Dao\Update($db, 'usuario');
	echo $update->data(array('nome' => 'Marcia',
							'email' => 'marcia@gmail.com'))
			->where("id = 3")
			->save(). " registros alterados ";;

```
	
### Deletando registros
```php
<?php	
	$delete = new \Asouza\Dao\Delete($db, 'usuario');
	echo $delete->where('id > 3')->exec(). ' registros deletados.';
```

### Debug
Facilitando o debug no ambiente de desenvolvimento
```php
Asouza\Dao\DB::$debug = true;
```

### Convenções
* A tabela deve ter uma  primary key nomeada como `id`.