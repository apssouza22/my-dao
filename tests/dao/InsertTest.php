<?php

namespace dao;
require_once dirname(__FILE__) . '/../../dao/DB.php';
require_once dirname(__FILE__) . '/../../dao/Filter.php';
require_once dirname(__FILE__) . '/../../dao/Insert.php';

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-03-18 at 20:15:18.
 */
class InsertTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var Insert
	 */
	protected $object;
	private $db;

	public function __construct()
	{
		$this->db = new \PDO("mysql:host=localhost;dbname=testedao", "root", "");
		$this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$this->db->query('DROP TABLE IF EXISTS usuario;');
		$this->db->query("CREATE TABLE `usuario` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `nome` varchar(45) DEFAULT NULL,
			  `email` varchar(45) DEFAULT NULL,
			  `datacadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1");

		$this->db->query('DROP TABLE IF EXISTS item;');
		$this->db->query("CREATE TABLE `item` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `idproduto` int(11) DEFAULT NULL,
					  `idusuario` int(11) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1");

		$this->db->query('DROP TABLE IF EXISTS produto;');
		$this->db->query("CREATE TABLE `produto` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(45) DEFAULT NULL,
					  `preco` decimal(10,2) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1");
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new Insert($this->db, 'usuario', 'stdClass');
	}

	public function testGetQuery()
	{
		
		$this->object->data(array(
			'nome' => 'Alexsandro Pereira',
			'email' => "alex@gmail.com"
		));

		$this->assertEquals('INSERT INTO usuario (nome, email) VALUES (:nome, :email)', $this->object->getQuery());
		
		$std = new \stdClass();
		$std->nome = 'Marcia';
		$std->email = 'marcia@gmail';
		$this->object->data($std);
		
		$this->assertEquals('INSERT INTO usuario (nome, email) VALUES (:nome, :email)', $this->object->getQuery());
	}

	public function testSave()
	{
		$std = new \stdClass();
		$std->nome = 'Marcia';
		$std->email = 'marcia@gmail';
		$this->object->data($std);
		
		$this->assertEquals(1, $this->object->save());
		
		$this->object = new Insert($this->db, 'usuario', 'stdClass');//abrindo novamente a conexão
		$this->object->data(array(
			'nome' => 'Alexsandro Pereira',
			'email' => "alex@gmail.com"
		));
		
		$this->assertEquals(2, $this->object->save());
	}

}
