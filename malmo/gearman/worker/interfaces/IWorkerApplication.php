<?php


/**
 * Interface of worker application.
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @package ext.worker
 * @version 0.2
 * @since 0.2
 * @see WorkerApplication
 */
interface IWorkerApplication
{
	/**
	 * @abstract
	 * @return IWorkerDaemon
	 */
	public function getWorker();
	/**
	 * @abstract
	 * @param IWorkerDaemon $worker
	 */
	public function setWorker($worker);
	/**
	 * @abstract
	 * @return IWorkerRouter
	 */
	public function getRouter();
	/**
	 * @abstract
	 * @param IWorkerRouter $router
	 */
	public function setRouter($router);
	/**
	 * @abstract
	 * @param IWorkerJob $command
	 */
	public function runCommand(IWorkerJob $command);
}
