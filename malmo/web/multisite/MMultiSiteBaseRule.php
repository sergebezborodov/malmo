<?php

/**
 * Base rule for checking multisite
 */
abstract class MMultiSiteBaseRule extends CComponent
{
    /**
     * Init instance
     */
    public function init()
    {}

    /**
     * @return bool is current request math rule
     */
    public abstract function getIsMath();
}
