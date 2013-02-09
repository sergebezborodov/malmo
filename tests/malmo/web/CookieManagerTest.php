<?php

/**
 * Test for cookie manager component
 */
class CookieManagerTest extends CTestCase
{
    public function setUp()
    {
        parent::setUp();

        $_COOKIE['first_cookie'] = 'first value';
    }

    protected function createManager()
    {
        $manager = new MCookieManager;
        $manager->cookies = array(
            'first' => array(
                'name' => 'first_cookie',
            ),
            'second' => array(
                'name'   => 'second_cookie',
                'expire' => '+1 year',
            ),
        );
        $manager->init();
        return $manager;
    }

    public function testValuesAndNull()
    {
        $manager = $this->createManager();

        $this->assertEquals($manager->getCookie('first'), 'first value');
        $this->assertNull($manager->getCookie('second'));
    }

    public function testArrayAccess()
    {
        $manager = $this->createManager();
        $this->assertEquals($manager['first'], 'first value');
        $this->assertNull($manager['second']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testDelete()
    {
        $manager = $this->createManager();

        $manager->removeCookie('first');
        $this->assertNull($manager['first']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSet()
    {
        $manager = $this->createManager();

        $manager->setCookie('first', 'new value');
        $this->assertEquals($manager['first'], 'new value');
    }

    public function testException()
    {
        $manager = $this->createManager();
        try {
            $manager->getCookie('foo');
            $this->setExpectedException('MCookieManagerException');
        } catch (Exception $e) {
            $this->assertInstanceOf('MCookieManagerException', $e);
        }
        try {
            $manager->setCookie('foo', 'bar');
            $this->setExpectedException('MCookieManagerException');
        } catch (Exception $e) {
            $this->assertInstanceOf('MCookieManagerException', $e);
        }
        try {
            $manager->removeCookie('foo');
            $this->setExpectedException('MCookieManagerException');
        } catch (Exception $e) {
            $this->assertInstanceOf('MCookieManagerException', $e);
        }
    }

    public function testHashNames()
    {
        $manager = $this->createManager();
        $manager->hashName = true;

        $this->assertNull($manager['first']);
        $this->assertNull($manager['second']);

        $manager->hashName = false;

        $this->assertNotNull($manager['first']);
        $this->assertNull($manager['second']);
    }
}
