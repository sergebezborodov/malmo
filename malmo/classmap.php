<?php

/**
 * Malmo internal class map
 */
return array(
    'MConsoleApplication'      => '/console/MConsoleApplication.php',

    'MWebApplication'          => '/web/MWebApplication.php',
    'MController'              => '/web/MController.php',


    'MDbConnection'            => '/db/MDbConnection.php',
    'MDbCommand'               => '/db/MDbCommand.php',
    'MDbTransaction'           => '/db/MDbTransaction.php',
    'MActiveRecord'            => '/db/ar/MActiveRecord.php',

    'MMultiSite'               => '/web/multisite/MMultiSite.php',
    'MMultiSiteManager'        => '/web/multisite/MMultiSiteManager.php',
    'MMultiSiteBaseRule'       => '/web/multisite/MMultiSiteBaseRule.php',
    'MMultiSiteUrlRule'        => '/web/multisite/MMultiSiteUrlRule.php',
    'MMultiSiteMobileRule'     => '/web/multisite/MMultiSiteMobileRule.php',
    'MMultiSiteUnit'           => '/web/multisite/MMultiSiteUnit.php',

    'MCookieManager'           => '/web/MCookieManager.php',


    'MWorkerApplication'       => '/gearman/MWorkerApplication.php',
    'IWorkerAction'            => '/gearman/worker/interfaces/IWorkerAction.php',
    'IWorkerApplication'       => '/gearman/worker/interfaces/IWorkerApplication.php',
    'IWorkerController'        => '/gearman/worker/interfaces/IWorkerController.php',
    'IWorkerDaemon'            => '/gearman/worker/interfaces/IWorkerDaemon.php',
    'IWorkerJob'               => '/gearman/worker/interfaces/IWorkerJob.php',
    'IWorkerRoute'             => '/gearman/worker/interfaces/IWorkerRoute.php',
    'IWorkerRouter'            => '/gearman/worker/interfaces/IWorkerRouter.php',

    'AbstractWorkerAction'     => '/gearman/worker/AbstractWorkerAction.php',
    'AbstractWorkerController' => '/gearman/worker/AbstractWorkerController.php',
    'WorkerApplication'        => '/gearman/worker/WorkerApplication.php',
    'WorkerDaemon'             => '/gearman/worker/WorkerDaemon.php',
    'WorkerInlineAction'       => '/gearman/worker/WorkerInlineAction.php',
    'WorkerJob'                => '/gearman/worker/WorkerJob.php',
    'WorkerRoute'              => '/gearman/worker/WorkerRoute.php',
    'WorkerRouter'             => '/gearman/worker/WorkerRouter.php',

    'MException'               => '/base/MException.php',
    'MTaggingBehavior'         => '/caching/MTaggingBehavior.php',
    'MTagsDependency'          => '/caching/dependencies/MTagsDependency.php',
);
