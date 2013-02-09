<?php

/**
 * Test case for multisite url rule
 */
class TestUrlRule extends CTestCase
{
    protected function createRule($url)
    {
        $rule = new MMultiSiteUrlRule;
        $rule->url = $url;
        $rule->init();
        return $rule;
    }


    public function testStandart()
    {
        $rule = $this->createRule('site.ru');

        $_SERVER['HTTP_HOST'] = 'site.ru';
        $this->assertTrue($rule->getIsMath());

        $_SERVER['HTTP_HOST'] = 'site.RU';
        $this->assertTrue($rule->getIsMath());

        $rule = $this->createRule('site.RU');
        $this->assertTrue($rule->getIsMath());

        $_SERVER['HTTP_HOST'] = 'site.com';
        $this->assertFalse($rule->getIsMath());

        $_SERVER['HTTP_HOST'] = 'site.com';
        $this->assertFalse($rule->getIsMath());
    }

    public function testSubdomains()
    {
        $rule = $this->createRule('site.ru');
        $rule->withSubdomains = true;

        $_SERVER['HTTP_HOST'] = 'site.RU';
        $this->assertTrue($rule->getIsMath());

        $_SERVER['HTTP_HOST'] = 'subdomain.site.RU';
        $this->assertTrue($rule->getIsMath());

        $_SERVER['HTTP_HOST'] = 'subsub.subdomain.site.RU';
        $this->assertTrue($rule->getIsMath());

        $rule = $this->createRule('site.ru');
        $_SERVER['HTTP_HOST'] = 'subdomain.site.RU';
        $this->assertFalse($rule->getIsMath());
    }
}
