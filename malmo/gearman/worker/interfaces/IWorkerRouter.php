<?php

/**
 * Interface of worker router component.
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @package ext.worker
 * @version 0.2
 * @since 0.2
 * @see WorkerRouter
 */
interface IWorkerRouter extends IApplicationComponent
{
	/**
	 * @abstract
	 * @param array $routes
	 */
	public function setRoutes(array $routes);
	/**
	 * @abstract
	 * @param string $commandName
	 * @param array|IWorkerRoute $route
	 * @return void
	 */
	public function setRoute($commandName, $route);
	/**
	 * @abstract
	 * @return IWorkerRoute[]
	 */
	public function getRoutes();
	/**
	 * @param IWorkerJob|string $command
	 * @return IWorkerRoute
	 */
	public function getRoute($command);
}
