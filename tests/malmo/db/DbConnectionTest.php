<?php

/**
 * Test cases for db connection
 */
class DbConnectionTest extends CDbTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->getFixtureManager()->resetTable('item');
    }

    public function testTransactions()
    {
        $db = Yii::app()->db;
        $this->assertInstanceOf('MDbConnection', $db);

        $db->beginTransaction()->commit();
        $db->beginTransaction()->rollback();
    }

    public function testNested()
    {
        $db = Yii::app()->db;
        $db->emulateNestedTransaction = true;

        $first = $db->beginTransaction();

        $second = $db->beginTransaction();
        Yii::app()->db->createCommand()->insert('item', array('title' => 'first'));
        $second->commit();
        Yii::app()->db->createCommand()->insert('item', array('title' => 'second'));

        $first->commit();
        $count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM item')->queryScalar();
        $this->assertEquals(2, $count);
    }

    public function testNestedRollback()
    {
        $db = Yii::app()->db;
        $db->emulateNestedTransaction = true;

        $first = $db->beginTransaction();
        Yii::app()->db->createCommand()->insert('item', array('title' => 'first'));

        // --- second
        $second = $db->beginTransaction();
        Yii::app()->db->createCommand()->insert('item', array('title' => 'second'));
        $second->rollback();

        $count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM item')->queryScalar();
        $this->assertEquals(2, $count);

        // --- third
        $third = $db->beginTransaction();
        Yii::app()->db->createCommand()->insert('item', array('title' => 'third'));
        $third->commit();
        $count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM item')->queryScalar();
        $this->assertEquals(3, $count);

        // --- first
        $first->commit();

        $count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM item')->queryScalar();
        $this->assertEquals(3, $count);
    }

    public function testFullRollback()
    {
        $db = Yii::app()->db;
        $db->emulateNestedTransaction = true;

        $first = $db->beginTransaction();
        Yii::app()->db->createCommand()->insert('item', array('title' => 'first'));

        $second = $db->beginTransaction();
        Yii::app()->db->createCommand()->insert('item', array('title' => 'second'));
        $second->commit();

        $count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM item')->queryScalar();
        $this->assertEquals(2, $count);

        $first->rollback();

        $count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM item')->queryScalar();
        $this->assertEquals(0, $count);
    }

    public function testCreateCommand()
    {
        $command = Yii::app()->db->createCommand();
        $this->assertInstanceOf('MDbCommand', $command);
    }

    public function testNonNested()
    {
        /** @var MDbConnection $db */
        $db = Yii::app()->db;
        $db->emulateNestedTransaction = false;
        $this->assertInstanceOf('MDbConnection', $db);

        $trans = $db->beginTransaction();
        try {
            $db->beginTransaction();
            $this->setExpectedException('PDOException');
        } catch (Exception $e) {
            $this->assertInstanceOf('PDOException', $e);
        }

        $trans->commit();
    }
}
