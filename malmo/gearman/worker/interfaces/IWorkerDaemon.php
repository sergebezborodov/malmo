<?php

/**
 * Interface of worker daemon component.
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @package ext.worker
 * @version 0.2
 * @since 0.2
 * @see WorkerDaemon
 */
interface IWorkerDaemon extends IApplicationComponent
{
	/**
	 * @abstract
	 */
	public function run();
	/**
	 * Set daemon activity. If it started, this method is stopped it after cycle complete.
	 *
	 * @abstract
	 * @param bool $active
	 */
	public function setActive($active);
	/**
	 * @abstract
	 * @param string $commandName
	 * @param mixed $callback
	 */
	public function setCommand($commandName, $callback);
	/**
	 * @abstract
	 * @param  $commandName
	 * @return void
	 */
	public function removeCommand($commandName);
}
