<?php

Yii::import('malmo.vendors.mobile-detect.Mobile_Detect', true);

class CustomMMultiSiteMobileRule extends MMultiSiteMobileRule
{
    protected function getMobileDetect()
    {
        return new Mobile_Detect();
    }
}

/**
 * Simple test case for nultisite mobile rule
 * More test available in included library Mobile Detect
 */
class MobileRuleTest extends CTestCase
{
    public function testMobile()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'BlackBerry7100i/4.1.0 Profile/MIDP-2.0 Configuration/CLDC-1.1 VendorID/103';
        $rule = new MMultiSiteMobileRule;
        $this->assertTrue($rule->getIsMath());
    }

    public function testNoMobile()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_5) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.56 Safari/537.17';
        $rule = new CustomMMultiSiteMobileRule;
        $this->assertFalse($rule->getIsMath());
    }
}
