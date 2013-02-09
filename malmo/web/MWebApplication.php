<?php

/**
 * Web Application class
 * register additional components
 *
 * @property MDbConnection $db The database connection.
 */
class MWebApplication extends CWebApplication
{
    /**
     * Registers the core application components.
     * This method overrides the parent implementation by registering additional core components.
     * @see setComponents
     */
    protected function registerCoreComponents()
    {
        parent::registerCoreComponents();

        $components = array(
            'db' => array(
                'class' => 'MDbConnection',
            ),
            'multisite' => array(
                'class' => 'MMultisiteManager',
            ),
            'cache' => array(
                'class' => 'CDummyCache',
                'behaviors' => array(
                    'tags' => array(
                        'class' => 'MTaggingBehavior',
                    ),
                ),
            ),
            'cookieManager' => array(
                'class' => 'MCookieManager',
            ),
        );

        $this->setComponents($components);
    }

    /**
     * @return MCookieManager
     */
    public function getCookieManager()
    {
        return $this->getComponent('cookieManager');
    }
}
