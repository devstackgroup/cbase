<?php

use CBase\Query\Query;

require_once dirname(__FILE__) . '/YamlTestCase.php';

class TestTableTest extends YamlTestCase
{
    public function testQueryTable()
    {
        $queryTable = $this->getConnection()->createQueryTable('testTable', 'SELECT * FROM testTable');
        $expectedTable = $this->getConnection()->createDataSet()->getTable('testTable'); 

        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testReadAllFromTable()
    {
    	$fixtureRow = $this->getFixtureRow('testTable', 0);

    	$db = $this->getQuery();
		$data =  $db->read()
					->get();
		
		$queryRow = [];
		foreach ($data as $row) {
			$queryRow[] = $row;
		}

		$this->getConnection()->close();

		$this->assertEquals($fixtureRow['id'], $queryRow[0]['id']);
		$this->assertEquals($fixtureRow['field'], $queryRow[0]['field']);
    }

    public function testReadAllOrderByIdDescFromTable()
    {
    	$fixtureRow = $this->getFixtureRow('testTable', 1);

    	$db = $this->getQuery();
		$data = $db->read()
				   ->orderBy([
					'id' => 'DESC'
				   ])
				   ->get();
		
		$queryRow = [];
		foreach ($data as $row) {
			$queryRow[] = $row;
		}

		$this->getConnection()->close();

		$this->assertEquals($fixtureRow['id'], $queryRow[0]['id']);
		$this->assertEquals($fixtureRow['field'], $queryRow[0]['field']);
    }

    public function testInsertIntoTable()
    {
    	$beforeInsertRowCount = $this->getConnection()->getRowCount('testTable');

    	$db = $this->getQuery();
    	$insertResult = $db->create([
							'field' => 1
						   ]);

		$afterInsertRowCount = $this->getConnection()->getRowCount('testTable');

		$this->getConnection()->close();

		$this->assertEquals(true, $insertResult);
		$this->assertEquals($beforeInsertRowCount + 1, $afterInsertRowCount);
    }

    public function testUpdateTable()
    {
    	$db = $this->getQuery();
		$updateResult = $db->update([
							'field' => 2
						   ])
						   ->where([
							'id' => 1
						   ])
						   ->exec();

		$data = $db->read('field')
				   ->where([ 
				   	'id' => 1
				   ])
				   ->get([
				   	'all' => true, 
					'fetch' => 'assoc'
				   ]);

		$queryRow = [];
		foreach ($data as $row) {
			$queryRow[] = $row;
		}

		$this->getConnection()->close();

		$this->assertEquals(true, $updateResult);
		$this->assertEquals(2, $queryRow[0]['field']);
    }

    public function testDeleteFromTable()
    {
    	$beforeDeleteRowCount = $this->getConnection()->getRowCount('testTable');

    	$db = $this->getQuery();
    	$db->delete()
		   ->where([
			'id' => 1
		   ])
		   ->exec();

		$afterDeleteRowCount = $this->getConnection()->getRowCount('testTable');

		$this->getConnection()->close();

		$this->assertEquals($beforeDeleteRowCount - 1, $afterDeleteRowCount);
		
    }

    private function getFixtureRow($table, $index)
    {
        $fixtureTable = $this->getConnection()->createDataSet()->getTable($table);

        return $fixtureTable->getRow($index);
    }

    private function getQuery()
    {
    	$pdo = $this->getConnection()->getConnection();
        $db = new Query($pdo);
		$db->setTable('testTable');

		return $db;
    }
}