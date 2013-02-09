<?php

/**
 * Rule for checking user agent for mobile devices or not
 */
class MMultiSiteMobileRule extends MMultiSiteBaseRule
{
    /**
     * @var Mobile_Detect
     */
    protected static $_mobileDetect;

    protected function getMobileDetect()
    {
        if (self::$_mobileDetect == null) {
            Yii::import('malmo.vendors.mobile-detect.Mobile_Detect', true);
            self::$_mobileDetect = new Mobile_Detect();
        }
        return self::$_mobileDetect;
    }

    /**
     * @return bool is current request math rule
     */
    public function getIsMath()
    {
        return $this->getMobileDetect()->isMobile();
    }
}
