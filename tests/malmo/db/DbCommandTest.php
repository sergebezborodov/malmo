<?php


class DbCommandTest extends CDbTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->getFixtureManager()->resetTable('item');
        $this->getFixtureManager()->loadFixture('item');
    }

    public function testAssocFull()
    {
        $command = Yii::app()->db->createCommand();

        $items = $command
            ->select('*')
            ->from('item')
            ->where('1=1')
            ->queryAssoc();

        $this->assertCount(3, $items);

        $this->assertArrayHasKey(1, $items);
        $this->assertArrayHasKey(3, $items);
        $this->assertArrayHasKey(5, $items);

        $first = $items[1];
        $this->assertEquals('first record', $first['title']);
        $this->assertEquals(1, $first['user_id']);
        $this->assertNotEmpty($first['date_updated']);

        $third = $items[3];
        $this->assertEquals('third record', $third['title']);
        $this->assertEquals(30, $third['user_id']);
        $this->assertNotEmpty($third['date_updated']);

        $fifth = $items[5];
        $this->assertEquals('fifth record', $fifth['title']);
        $this->assertEquals(50, $fifth['user_id']);
        $this->assertNotEmpty($fifth['date_updated']);
    }

    public function testAssocSimple()
    {
        $command = Yii::app()->db->createCommand();

        $items = $command
            ->select('id, title')
            ->from('item')
            ->where('1=1')
            ->queryAssoc();

        $this->assertCount(3, $items);

        $this->assertArrayHasKey(1, $items);
        $this->assertEquals('first record', $items[1]);

        $this->assertArrayHasKey(3, $items);
        $this->assertEquals('third record', $items[3]);
        $this->assertArrayHasKey(5, $items);
        $this->assertEquals('fifth record', $items[5]);
    }

    public function testAddWhere()
    {
        $command = Yii::app()->db
            ->createCommand()
            ->select('id, title')
            ->from('item')
            ->where('1=1');

        $command->addWhere('1=0');
        $this->assertEquals('(1=1) AND (1=0)', $command->getWhere());

        $command->addWhere(array('and', '1=3', '2=3'));
        $this->assertEquals('((1=1) AND (1=0)) AND ((1=3) AND (2=3))', $command->getWhere());

        $command->where('1=1');
        $this->assertEquals('1=1', $command->getWhere());
    }
}
