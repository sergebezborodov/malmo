<?php

/**
 * Interface of worker router route rule.
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @package ext.worker
 * @version 0.2
 * @since 0.2
 * @see WorkerRoute
 */
interface IWorkerRoute
{
	/**
	 * @abstract
	 * @param string $commandName
	 * @param string $controllerId
	 * @param string $actionId
	 */
	public function __construct($commandName, $controllerId, $actionId);
	/**
	 * @abstract
	 * @return string
	 */
	public function getCommandName();
	/**
	 * @abstract
	 * @return string
	 */
	public function getControllerId();
	/**
	 * @abstract
	 * @return string
	 */
	public function getActionId();
}
