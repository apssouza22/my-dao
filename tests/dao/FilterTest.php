<?php

namespace Dao;
require_once dirname(__FILE__) . '/../../Dao/Filter.php';

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-03-18 at 19:44:22.
 */
class FilterTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var Filter
	 */
	protected $object;

	
	protected function setUp()
	{
		$this->object = new Filter;
	}

	public function testWhere()
	{
		$this->object->where('id = 1');
		$this->assertEquals(' WHERE id = 1',$this->object->getWhere());
	}

	
	public function testGetWhere()
	{
		$this->object->where('id = 1');
		$this->assertEquals(' WHERE id = 1',$this->object->getWhere());
	}

	/**
	 * @covers dao\Filter::limit
	 * @todo   Implement testLimit().
	 */
	public function testLimit()
	{
		$this->object->limit(5, 2);
		 $this->assertEquals(' WHERE 1 LIMIT 2 , 5',$this->object->getFilter());
	}

	/**
	 * @covers dao\Filter::orderBy
	 * @todo   Implement testOrderBy().
	 */
	public function testOrderBy()
	{
		$this->object->orderBy('id ASC');
		$this->assertEquals(' WHERE 1 ORDER BY id ASC',$this->object->getFilter());
	}

	/**
	 * @covers dao\Filter::groupBy
	 * @todo   Implement testGroupBy().
	 */
	public function testGroupBy()
	{
		$this->object->groupBy('id');
		$this->assertEquals(' WHERE 1 GROUP BY id',$this->object->getFilter());
	}

	/**
	 * @covers dao\Filter::getFilter
	 * @todo   Implement testGetFilter().
	 */
	public function testGetFilter()
	{
		$this->object->groupBy('id');
		$this->assertEquals(' WHERE 1 GROUP BY id',$this->object->getFilter());
	}

}
