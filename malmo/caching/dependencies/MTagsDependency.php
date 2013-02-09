<?php

/**
 * Cache dependency by tags
 */
class MTagsDependency implements ICacheDependency
{
    /**
     * @var int
     */
    protected $timestamp;

    /**
     * @var array
     */
    protected $tags;

    /**
     * Creates instance of dependency
     *
     * If first param array passed - it use as tags list
     * else you can pass each string as param
     * it will use as tags
     *
     * @params tag1, tag2, ..., tagN
     */
    function __construct($first)
    {
        if (is_array($first)) {
            $this->tags = func_get_args();
        } else {
            $this->tags = func_get_args();
        }
    }

    /**
     * Evaluates the dependency by generating and saving the data related with dependency.
     * This method is invoked by cache before writing data into it.
     */
    public function evaluateDependency()
    {
        $this->timestamp = time();
    }

    /**
     * @return boolean whether the dependency has changed.
     */
    public function getHasChanged()
    {
        $tags = array_map(function($i) {
            return MTaggingBehavior::PREFIX . $i;
        }, $this->tags);

        $values = Yii::app()->cache->mget($tags);

        foreach($values as $value) {
            if ((int)$value > $this->timestamp) {
                return true;
            }
        }

        return false;
    }
}
