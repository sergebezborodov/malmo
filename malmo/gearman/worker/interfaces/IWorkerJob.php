<?php

/**
 * Interface of worker job object.
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @package ext.worker
 * @version 0.2
 * @since 0.2
 * @see WorkerJob
 */
interface IWorkerJob
{
	/**
	 * @abstract
	 * @return string
	 */
	public function getCommandName();
	/**
	 * @abstract
	 * @return string
	 */
	public function getIdentifier();
	/**
	 * @abstract
	 * @return string
	 */
	public function getWorkload();
	/**
	 * Sends result data and the complete status update for this job.
     *
     * @param string $result
     * @return bool
	 */
	public function sendComplete($data);
	/**
	 * @abstract
	 * @param Exception $exception
	 * @return void
	 */
	public function sendException($exception);
}
