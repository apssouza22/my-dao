# Meu DAO 
=======================================

Estrutura simples de classes para abstração de banco de dados


Criando  query object
----------------------------

```php
<?php	$db = new \PDO("mysql:host=localhost;dbname=testedao", "root", "");
		$select = new dao\Select($db, 'columns', 'stdClass');
		$insert = new \dao\Insert($db, 'table');
		$update = new \dao\Update($db, 'table');
		$delete = new \dao\Delete($db, 'table');
```
	Ou 
	
```php
<?php	$db = new \PDO("mysql:host=localhost;dbname=testedao", "root", "");
		$select = dao\Query::create($db)->select('id, nome');
		$insert = dao\Query::create($db)->insert('table');
		$update = dao\Query::create($db)->update('table');
		$delete = dao\Query::create($db)->delete('table');
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
		$result = dao\Query::create($db)
					->Select('id, nome')
					->from('usuario')
					->fetchAllObject();
		var_dump($result);

```
	
Filtrando registros:
```php
<?php	
		$filter = new dao\Filter();
		$filter->where('id > 2')->orderBy('id DESC');
		$result = dao\Query::create($db)
					->Select('id, nome')
					->from('usuario')
					->setFilter($filter)
					->fetchAllObject();
		var_dump($result);

```
	
### Joins:
```php
<?php	
		$select = new dao\Select($db, '*');
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
		$insert = new \dao\Insert($db, 'usuario');
		echo "Ultimo id: " . 
				$insert->data($std)
						->save();

```
	
Inserindo array:
```php
<?php	
	$insert = new \dao\Insert($db, 'usuario');
	echo "Ultimo id inserido: " . 
			$insert->data(array(
					'nome' => 'Alex sandro',
					'email' => 'apssouza22@gmail.com'
					))->save();

```
	
### Atualizando registros
```php
<?php	
	$update = new \dao\Update($db, 'usuario');
	echo $update->data(array('nome' => 'Marcia',
							'email' => 'marcia@gmail.com'))
			->where("id = 3")
			->save(). " registros alterados ";;

```
	
### Deletando registros
```php
<?php	
	$delete = new \dao\Delete($db, 'usuario');
	echo $delete->where('id > 3')->exec(). ' registros deletados.';
```

### Debug
Facilitando o debug no ambiente de desenvolvimento
```php
dao\DB::$debug = true;
```

### Convenções
* A tabela deve ter uma  primary key nomeada como `id`.