<?php

namespace Asouza\Dao;
require_once dirname(__FILE__) . '/../../../Dao/DB.php';
require_once dirname(__FILE__) . '/../../../Dao/Filter.php';
require_once dirname(__FILE__) . '/../../../Dao/Insert.php';

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

	public static function setUpBeforeClass()
	{
		$db = require dirname(__FILE__) .'/../config.php';
		$db->query('DROP TABLE IF EXISTS usuario;');
		$db->query("CREATE TABLE `usuario` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `nome` varchar(45) DEFAULT NULL,
			  `email` varchar(45) DEFAULT NULL,
			  `datacadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1");
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->db =  require dirname(__FILE__) .'/../config.php';
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
		
		$this->object->data(array(
			'nome' => 'Alexsandro Pereira',
			'email' => "alex@gmail.com"
		));
		
		$this->assertEquals(2, $this->object->save());
	}

}
