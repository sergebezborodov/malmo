<?php

class CustomMultiSite extends MMultiSite {}

/**
 * Test case for multisite component
 */
class TestMultiSiteComponent extends CTestCase
{

    protected function createMS()
    {
        $multisite = new MMultiSiteManager();
        $multisite->units = array(
            'site' => array(
                'one' => array(
                    'id' => 1,
                    'attributes' => array(
                        'param' => 'value',
                    ),
                ),
                'two',
            ),
            'platform' => array(
                'web',
                'mobile',
            ),
        );
        $multisite->multisites = array(
            'main_site' => array(
                'id' => 1,
                'class' => 'MMultiSite',
                'units' => array(
                    'site' => 'one',
                    'platform' => 'web',
                ),
                'rule' => array('class' => 'MMultiSiteUrlRule', 'url' => 'site.ru'),
                'attributes' => array(
                    'attr1' => 'value1',
                    'attr2' => 'value2',
                ),
            ),
            'second_site' => array(
                'class' => 'CustomMultiSite',
                'rule' => 'second.ru',
                'units' => array(
                    'site' => 'two',
                    'platform' => 'web',
                ),
                'attributes' => array(
                    'attr3' => 'value3',
                    'attr4' => 'value4',
                ),
            ),
        );
        $multisite->init();
        return $multisite;
    }


    public function testUnits()
    {
        $multisite = $this->createMS();

        $units = $multisite->getUnits();

        $this->assertCount(2, $units);

        $this->assertArrayHasKey('site', $units);
        $this->assertArrayHasKey('platform', $units);

        $this->assertCount(2, $units['site']);
        $this->assertArrayHasKey('one', $units['site']);
        $this->assertArrayHasKey('two', $units['site']);

        // test site one
        $siteOne = $units['site']['one'];
        $this->assertEquals($siteOne->id, 1);
        $this->assertEquals($siteOne->name, 'one');

        $this->assertEquals($siteOne->attributes, array('param' => 'value'));

        // test site two
        $siteTwo = $units['site']['two'];
        $this->assertEmpty($siteTwo->id);
        $this->assertEquals($siteTwo->name, 'two');
        $this->assertEmpty($siteTwo->attributes);
    }

    public function testUnitsFindByType()
    {
        $multisite = $this->createMS();

        $siteUnits = $multisite->findUnitsByType('site');
        $this->assertNotEmpty($siteUnits);
        $this->assertCount(2, $siteUnits);
        $this->assertArrayHasKey('one', $siteUnits);
        $this->assertArrayHasKey('two', $siteUnits);

        $platform = $multisite->findUnitsByType('platform');
        $this->assertNotEmpty($platform);
        $this->assertCount(2, $platform);
        $this->assertArrayHasKey('web', $platform);
        $this->assertArrayHasKey('mobile', $platform);
    }

    public function testUnitsFindByNameOrId()
    {
        $multisite = $this->createMS();

        $siteOne = $multisite->findUnitByIdOrName(1, 'site');
        $this->assertNotEmpty($siteOne);
        $this->assertEquals('one', $siteOne->name);
        $this->assertEquals('site', $siteOne->type);

        $siteTwo = $multisite->findUnitByIdOrName('two', 'site');
        $this->assertNotEmpty($siteTwo);
        $this->assertEquals('two', $siteTwo->name);
        $this->assertEquals('site', $siteTwo->type);

        $notFound = $multisite->findUnitByIdOrName('three', 'site');
        $this->assertEmpty($notFound);

        $pWeb = $multisite->findUnitByIdOrName('web', 'platform');
        $this->assertNotEmpty($pWeb);
        $this->assertEquals('web', $pWeb->name);
        $this->assertEquals('platform', $pWeb->type);

        $pMobile = $multisite->findUnitByIdOrName('mobile', 'platform');
        $this->assertNotEmpty($pMobile);
        $this->assertEquals('mobile', $pMobile->name);
        $this->assertEquals('platform', $pMobile->type);
    }


    public function testMultisites()
    {
        $multisite = $this->createMS();


        $this->assertCount(2, $multisite->multisites);
        $this->assertCount(2, $multisite->getMultiSites());

        $this->assertArrayHasKey('main_site', $multisite->multisites);
        $this->assertEquals($multisite->multisites['main_site']->attributes, array(
            'attr1' => 'value1',
            'attr2' => 'value2',
        ));

        $this->assertArrayHasKey('second_site', $multisite->multisites);
        $this->assertInstanceOf('CustomMultiSite', $multisite->multisites['second_site']);
        $this->assertEquals($multisite->multisites['second_site']->attributes, array(
            'attr3' => 'value3',
            'attr4' => 'value4',
        ));
    }

    public function testMultisiteFind()
    {
        $multisite = $this->createMS();

        // find first multisite
        $inst = $multisite->findMultiSite('main_site');
        $this->assertNotNull($inst);
        $inst = $multisite->findMultiSite(1);
        $this->assertNotNull($inst);
        $inst = $multisite->findMultiSite('1');
        $this->assertNotNull($inst);


        $inst2 = $multisite->findMultiSite('second');
        $this->assertNull($inst2);
        $inst2 = $multisite->findMultiSite(2);
        $this->assertNull($inst2);

        $inst2 = $multisite->findMultiSite('second_site');
        $this->assertNotNull($inst2);
        $this->assertInstanceOf('CustomMultiSite', $inst2);
    }

    public function testSetActive()
    {
        $multisite = $this->createMS();

        $_SERVER['HTTP_HOST'] = 'subdomain.site.RU';
        $active = $multisite->getActive();
        $this->assertNull($active);

        $multisite->setActive(1);
        $active = $multisite->getActive(true);
        $this->assertNotNull($active);
        $this->assertEquals(1, $active->id);

        $multisite->setActive('second_site');
        $active = $multisite->getActive(true);
        $this->assertNotNull($active);
        $this->assertEquals('second_site', $active->name);
    }

    public function testMultisiteUnits()
    {
        $ms = $this->createMS();
        $_SERVER['HTTP_HOST'] = 'site.ru';
        $active = $ms->getActive();
        $this->assertNotEmpty($active);

        $units = $active->getUnits();
        $this->assertCount(2, $units);
        $this->assertArrayHasKey('site', $units);
        $this->assertArrayHasKey('platform', $units);

        $site = $active->getUnit('site');
        $this->assertNotEmpty($site);
        $this->assertEquals('site', $site->type);
        $this->assertEquals('one', $site->name);

        $non = $active->getUnit('non');
        $this->assertEmpty($non);

        $platform = $active->getUnit('platform');
        $this->assertNotEmpty($platform);
        $this->assertEquals('platform', $platform->type);
        $this->assertEquals('web', $platform->name);
    }

    public function testBadUnits()
    {
        $multisite = new MMultiSiteManager();
        $multisite->units = array(
            'site' => array(
                'one',
                'two',
            ),
        );
        $multisite->multisites = array(
            'main_site' => array(
                'id' => 1,
                'class' => 'MMultiSite',
                'units' => array(
                    'site' => 'one',
                    'platform' => 'web',
                ),
                'rule' => 'site.ru',
            ),
        );
        $multisite->init();
        $_SERVER['HTTP_HOST'] = 'site.ru';

        $active = $multisite->active;
        $this->assertNotEmpty($active);

        try {
            $units = $active->getUnits();
            $this->setExpectedException('MMultiSiteException');
        } catch (Exception $e) {
            $this->assertInstanceOf('MMultiSiteException', $e);
        }

        try {
            $unit = $active->getUnit('platform');
            $this->setExpectedException('MMultiSiteException');
        } catch (Exception $e) {
            $this->assertInstanceOf('MMultiSiteException', $e);
        }
    }
}
