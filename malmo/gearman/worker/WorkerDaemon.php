<?php
/**
 * File contains class WorkerDaemon
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @link https://github.com/mitallast/yii-gearman
 * @copyright Alexey Korchevsky <mitallast@gmail.com> 2010-2011
 * @license https://github.com/mitallast/yii-gearman/blob/master/license
 */

/**
 * Class WorkerDaemon represent API of asynchronous workers.
 * For use component, you can register it in Yii application config:
 * <code>
 * "components" => array(
 *     "worker" => array(
 *         "class" => "WorkerDaemon",
 *         "servers" => array(
 *             "gearman.loc",  // simple, by address
 *             "127.0.0.33", // simple, by ip and default port
 *             array("127.0.0.12",4345), // with custom port
 *         ),
 *     ),
 * ),
 * </code>
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @package ext.worker
 * @version 0.2
 * @since 0.2
 */
class WorkerDaemon extends CApplicationComponent implements IWorkerDaemon
{
	private $_worker;
	private $_callbackHash = array();
	private $_active = false;
	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->_worker = new GearmanWorker();
	}
	/**
	 * Start run worker. Before run, method automatically set active to true.
	 *
	 * If need stop worker, code must call setActive(false), and worker stops after work cycle end.
	 */
	public function run()
	{
		/** @var $worker GearmanWorker */
		$worker = $this->_worker;
		$this->setActive(true);

		while($worker->work() && $this->getActive())
		{
			if ($worker->returnCode() != GEARMAN_SUCCESS)
			{
				echo "return_code: " . $worker->returnCode() . "\n";
				break;
			}
		}
	}
	/**
	 * Set daemon activity. If it started, this method is stopped it after cycle complete.
	 *
	 * @param bool $active
	 */
	public function setActive($active)
	{
		$this->_active = (bool)$active;
	}
	/**
	 * Check is daemon in running.
	 *
	 * @return bool
	 */
	public function getActive()
	{
		return $this->_active;
	}
	/**
	 * Magic caller to worker function router.
	 * Routes callbacks in daemon.
	 *
	 * @param string $functionName
	 * @param array $params
	 */
	public function __call($functionName, $params)
	{
		if(isset($this->_callbackHash[strtolower($functionName)]))
		{
			$callback = $this->_callbackHash[strtolower($functionName)];
		    $job = new WorkerJob($params[0]);
			return call_user_func($callback, $job);
		}
	    else
		    throw new CException(Yii::t(
			    'worker',
			    'Call to undefined method "{method}"',
			    array('{method}' => $functionName)
		    ));
	}
	/**
	 * Register command callback in worker daemon.
	 *
	 * @param string $commandName
	 * @param mixed $callback
	 */
	public function setCommand($commandName, $callback)
	{
		$this->_callbackHash[strtolower($commandName)] = $callback;
		$this->_worker->addFunction($commandName,array($this, $commandName));
	}
	/**
	 * Unregister command in daemon by name and remove callback.
	 *
	 * @param string $commandName
	 * @return void
	 */
	public function removeCommand($commandName)
	{
		$this->_worker->unregister($commandName);
		unset($this->_callbackHash[strtolower($commandName)]);
	}
	/**
	 * Set server config list.
	 *
	 * @example
	 * <code>
	 * array(
	 *     "127.0.0.1", // simple address
	 *     array("127.0.0.12",4345), // with port
	 * )
	 * </code>
	 * @param array $servers list of servers
	 * @return void
	 */
	public function setServers(array $servers)
	{
		$worker = $this->_worker;
		foreach($servers as $server)
		{
			if(is_string($server))
				$worker->addServer($server);
			elseif(is_array($server))
			{
				if(count($server)==1)
					$worker->addServer($server[0]);
				elseif(count($server)>1)
					$worker->addServer($server[0],$server[1]);
			}
		    else
			    throw new Exception("Error add server");
		}
	}
	/**
	 * Adds a job server to this worker. This goes into a list of servers than can be
     * used to run jobs. No socket I/O happens here.
     *
     * @link http://php.net/manual/en/gearmanworker.addserver.php
     * @param string $host
     * @param int $port
	 */
	public function addServer($host, $port = null)
	{
		$this->_worker->addServer($host, $port);
	}
	/**
	 * Sets one or more options to the supplied value.
     *
     * @link http://php.net/manual/en/gearmanworker.setoptions.php
     * @param int $options The options to be set
	 */
	public function setOptions($options)
	{
		$options = (int)$options;
	    $this->_worker->setOptions($options);
	}
	/**
	 * Unset one or more options.
	 *
	 * @link http://php.net/manual/en/gearmanworker.removeoptions.php
	 * @param int $options
	 * @return void
	 */
	public function removeOptions($options)
	{
		$options = (int)$options;
	    $this->_worker->removeOptions($options);
	}
	/**
	 * Sets the interval of time to wait for socket I/O activity.
     *
     * @link http://php.net/manual/en/gearmanworker.settimeout.php
     * @param int $timeout An interval of time in milliseconds.
	 * A negative value indicates an infinite timeout
	 */
	public function setTimeout($timeout)
	{
		$timeout = (int)$timeout;
		$this->_worker->setTimeout($timeout);
	}
}
