<?php

/**
 * Interface of worker abstract action component.
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @package ext.worker
 * @version 0.2
 * @since 0.2
 * @see AbstractWorkerAction
 */
interface IWorkerAction extends IAction
{
	/**
	 * @abstract
	 * @param IWorkerJob $job
	 */
	public function setJob($job);
	/**
	 * @abstract
	 * @return IWorkerJob
	 */
	public function getJob();
}
