<?php

/**
 * Console Application class
 * register additional components
 *
 * @property MDbConnection $db The database connection.
 */
class MWorkerApplication extends WorkerApplication
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
        );

        $this->setComponents($components);
    }
}
