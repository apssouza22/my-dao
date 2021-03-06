<?php

namespace Asouza\Dao;
require_once dirname(__FILE__) . '/../../../Dao/DB.php';
require_once dirname(__FILE__) . '/../../../Dao/Filter.php';
require_once dirname(__FILE__) . '/../../../Dao/Delete.php';

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-03-20 at 18:03:14.
 */
class DeleteTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var Delete
	 */
	protected $object;
	protected $db;
	
	
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
		
		$db->query("INSERT INTO `testedao`.`usuario`(`nome`,`email`)
					VALUES('Alexsandro Souza','apssouza22@gmail.com');");
		$db->query("INSERT INTO `testedao`.`usuario`(`nome`,`email`)
					VALUES('Marcia Souza','marcia@gmail.com');");
		$db = null;
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->db =  require dirname(__FILE__) .'/../config.php';
		$this->object = new Delete($this->db, 'usuario');
	}

	
	public function testFrom()
	{
		$this->object->from('usuario');
		$this->assertEquals('DELETE FROM usuario WHERE 1', $this->object->getQuery());
	}

	/**
	 * @covers dao\Delete::limit
	 * @todo   Implement testLimit().
	 */
	public function testLimit()
	{
		$this->object->from('usuario');
		$this->object->limit(5);
		$this->assertEquals('DELETE FROM usuario WHERE 1 LIMIT 0 , 5', $this->object->getQuery());
	}

	/**
	 * @covers dao\Delete::getQuery
	 * @todo   Implement testGetQuery().
	 */
	public function testGetQuery()
	{
		$this->object->from('usuario');
		$this->assertEquals('DELETE FROM usuario WHERE 1', $this->object->getQuery());
	}

	
	public function testExec()
	{
		$this->object->from('usuario');
		$this->assertEquals(2, $this->object->exec());
	}

}
