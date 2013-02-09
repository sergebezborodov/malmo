<?php

/**
 * Cache tagging behavior
 */
class MTaggingBehavior extends CBehavior
{
    const PREFIX = '__tag__';

    /**
     * Invalidate cached data by tags
     *
     * @param array $tags
     * @return void
     */
    public function clear($tags)
    {
        foreach((array)$tags as $tag) {
            $this->owner->set(self::PREFIX . $tag, time());
        }
    }
}
