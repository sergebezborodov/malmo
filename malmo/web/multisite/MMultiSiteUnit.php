<?php

/**
 * Unit of multisite
 */
class MMultiSiteUnit extends CComponent
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string type name
     */
    public $type;

    /**
     * @var array
     */
    public $attributes = array();

    /**
     * Init unit instance
     */
    public function init()
    {}
}
