<?php
/**
 * File contains class WorkerApplication
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @link https://github.com/mitallast/yii-gearman
 * @copyright Alexey Korchevsky <mitallast@gmail.com> 2010-2011
 * @license https://github.com/mitallast/yii-gearman/blob/master/license
 */



/**
 * Class WorkerApplication extends CApplication by providing functionality by worker specific requests.
 *
 * WorkerApplication managers contollers in MVC pattern, provides specific core components to work with
 * job queue:
 * <ul>
 *    <li>{@link worker} Worker daemon, connect to job server queue, implements command routing.</li>
 *    <li>{@link router} Worker router, implements command to controller action routing.</li>
 * </ul>
 *
 * Example of worker bootstrap script:
 * <code>
 * // change the following paths if necessary
 * $yii=dirname(__FILE__).'/../yii/yii.php';
 * $config=dirname(__FILE__).'/protected/config/worker.php';
 *
 * // remove the following lines when in production mode
 * defined('YII_DEBUG') or define('YII_DEBUG',true);
 *
 * // specify how many levels of call stack should be shown in each log message
 * defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
 * require_once($yii);
 * require_once(dirname(__FILE__).'/protected/extensions/worker/WorkerApplication.php');
 *
 * Yii::createApplication("WorkerApplication", $config)->run();
 * </code>
 *
 * Example of worker config:
 * <code>
 * return array(
 *     'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
 *     'import'=>array(
 *         'ext.worker.*',
 *     ),
 *     'components'=>array(
 *         'worker'=>array(
 *             'class'=>'WorkerDaemon',
 *             'servers'=>array('192.168.56.101'),
 *         ),
 *         'router'=>array(
 *             'class'=>'WorkerRouter',
 *             'routes'=>array(
 *                 'reverse'=>'application.controllers.gearman',
 *             ),
 *         ),
 *    ),
 * );
 * </code>
 *
 * @see WorkerDaemon
 * @see WorkerRoute
 * @see WorkerController
 * @see AbstractWorkerAction
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @package ext.worker
 * @version 0.2
 * @since 0.2
 *
 * @param
 */
class WorkerApplication extends CApplication implements IWorkerApplication
{
	/**
	 * @return string the ID of the default controller. Defaults to 'worker'.
	 */
	public $defaultController = 'worker';

	/**
	 * Start worker cycle.
	 * To add custom route rules you can add in at worker.
	 * <code>
	 *
	 * // add callback in php5.3 style
	 * $app->getWorker()->setCommand("commandName", function($job){
	 *      $job->setReturn($data->getMessage());
	 * });
	 *
	 * // add callback as
	 * $app->getWorker()->setCommand("commandName", array("controllerId", "action"));
	 * </code>
	 */
	public function processRequest()
	{
		$routes = $this->getRouter()->getRoutes();
	    $worker = $this->getWorker();

	    foreach($routes as $route)
	    {
		    $worker->setCommand($route->getCommandName(), array($this, 'runCommand'));
	    }

	    $worker->run();
	}
	/**
	 * Get worker daemon component.
	 * Also you can call $app->getComponent("worker") or $app->worker.
	 * 
	 * @return IWorkerDaemon
	 */
	public function getWorker()
	{
		return $this->getComponent("worker");
	}
	/**
	 * Set worker daemon component.
	 * Also you can call $app->setWorker("router", $component) or $app->router = $component.
	 *
	 * @param mixed $worker
	 * @see setComponent
	 */
	public function setWorker($worker)
	{
		$this->setComponent("worker", $worker);
	}
	/**
	 * Get worker route component.
	 * Also you can call $app->getComponent("router") or $app->router.
	 *
	 * @return IWorkerRouter
	 * @see getComponent
	 */
	public function getRouter()
	{
		return $this->getComponent("router");
	}
	/**
	 * Set worker route component.
	 * Also you can call $app->setComponent("router", $component) or $app->router = $component.
	 *
	 * @param mixed $router
	 * @return void
	 */
	public function setRouter($router)
	{
		$this->setComponent("router", $router);
	}
	/**
	 * Default callback worker daemon.
	 * It's calls when worker get new job and router have not custom callback.
	 *
	 * @param IWorkerJob $job
	 */
	public function runCommand(IWorkerJob $job)
	{
		try{
			$route = $this->getRouter()->getRoute($job);

			if(is_null($route))
			{
				$controllerId = $this->defaultController;
			    $actionId = $job->getCommandName();
			}
			else
			{
				$controllerId = $route->getControllerId();
			    $actionId = $route->getCommandName();
			}


			$controller = $this->createController($controllerId);
			$controller->init();
			
			/** @var $action IWorkerAction */
			$action = $controller->createAction($actionId);
			if($action instanceof IWorkerAction)
			{
				$action->setJob($job);
			    $action->run();
			}
			else throw new CException(Yii::t(
				"worker",
				"Action is not instance of IWorkerAction"
			));
		}
		catch(Exception $e)
		{
			$job->sendException($e);
		    throw $e;
		}
	}
	/**
	 * Displays the captured PHP error.
	 * This method displays the error in console mode when there is
	 * no active error handler.
	 * @param integer $code error code
	 * @param string $message error message
	 * @param string $file error file
	 * @param string $line error line
	 */
	public function displayError($code,$message,$file,$line)
	{
		echo "PHP Error[$code]: $message\n";
		echo "    in file $file at line $line\n";
		$trace=debug_backtrace();
		// skip the first 4 stacks as they do not tell the error position
		if(count($trace)>4)
			$trace=array_slice($trace,4);
		foreach($trace as $i=>$t)
		{
			if(!isset($t['file']))
				$t['file']='unknown';
			if(!isset($t['line']))
				$t['line']=0;
			if(!isset($t['function']))
				$t['function']='unknown';
			echo "#$i {$t['file']}({$t['line']}): ";
			if(isset($t['object']) && is_object($t['object']))
				echo get_class($t['object']).'->';
			echo "{$t['function']}()\n";
		}
	}
	/**
	 * Displays the uncaught PHP exception.
	 * This method displays the exception in console mode when there is
	 * no active error handler.
	 * @param Exception $exception the uncaught exception
	 */
	public function displayException($exception)
	{
		if(YII_DEBUG)
		{
			echo get_class($exception) . "\n";
			echo $exception->getMessage() . ' (' . $exception->getFile() . ' : ' . $exception->getLine() . "\n";
			echo $exception->getTraceAsString() . "\n";
		}
		else
		{
			echo get_class($exception) . "\n";
			echo $exception->getMessage() . "\n";
		}
	}
	/**
	 * Registers the core application components.
	 * This method overrides the parent implementation by registering additional core components.
	 * @see setComponents
	 */
	protected function registerCoreComponents()
	{
		parent::registerCoreComponents();

		$components = array(
			'worker' => array(
				'class' => 'WorkerDaemon',
			),
			'router' => array(
				'class' => 'WorkerRouter',
			),
		);

		$this->setComponents($components);
	}
	/**
	 * Parse contoller id string and return controller class instance.
	 * 
	 * @param string $controllerId
	 * @return IWorkerController
	 */
	protected function createController($controllerId)
	{
		$controllerId = trim($controllerId);
		
		if(!strlen($controllerId))
		    throw new InvalidArgumentException(Yii::t("worker", "Invalid controller id"));

	    $path = null;
	    $className = null;
	    $classFile = null;
	    if(strpos($controllerId, '.'))
	    {
		    $lastDot = strrpos($controllerId, '.');
			$path = substr($controllerId, 0, $lastDot);
	        $className = $controllerId = substr($controllerId, $lastDot+1);
	    }
		else
			$className = $controllerId;

	    if(!strpos($className, "Controller"))
	    {
		    $className = ucfirst($className) . "Controller";
	    }
		if($path)
	        $classFile = $path . '.' . $className;
		else
			$classFile = $className;

	    if(!class_exists($className, false))
	    {
		    Yii::import($classFile, true);
	    }

	    if(class_exists($className,false))
		{
			if(is_subclass_of($className,'CController'))
			{
				return new $className($controllerId);
			}
		    else
			    throw new CException(Yii::t(
				    "worker",
				    "Class \"{class}\" is not subclass of CController",
				    array("{class}" => $className)
			    ));
		}
	    else
		    throw new CException(Yii::t(
			    "worker",
			    "Class \"{class}\" is not found",
			    array("{class}" => $className)
		    ));
	}
}
