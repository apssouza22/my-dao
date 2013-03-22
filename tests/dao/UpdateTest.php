<?php

namespace dao;
require_once dirname(__FILE__) . '/../../dao/DB.php';
require_once dirname(__FILE__) . '/../../dao/Filter.php';
require_once dirname(__FILE__) . '/../../dao/Update.php';

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-03-20 at 18:26:25.
 */
class UpdateTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var Update
	 */
	protected $object;
	protected $db;
	
	public static function setUpBeforeClass()
	{
		$db = new \PDO("mysql:host=localhost;dbname=testedao", "root", "");
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
	}
	
	protected function setUp()
	{
		$this->db = new \PDO("mysql:host=localhost;dbname=testedao", "root", "");
		$this->object = new Update($this->db, 'usuario');
	}

	
	/**
	 * @covers dao\Update::getQuery
	 * @todo   Implement testGetQuery().
	 */
	public function testGetQuery()
	{
		$this->object->data(array('nome' =>'Deda'))->where('id=1');
		$this->assertEquals('UPDATE usuario SET nome = :nome WHERE id=1', $this->object->getQuery());
	}

	/**
	 * @covers dao\Update::save
	 * @todo   Implement testSave().
	 */
	public function testSave()
	{
		$this->object->data(array('nome' =>'Deda'))->where('id=1');
		$this->assertEquals(1, $this->object->save());
		
		$select = new Select($this->db);
		$select->where('id = 1')->from('usuario');
		$user = $select->fetchObject();
		
		$this->assertEquals('Deda', $user->nome );
	}

}
