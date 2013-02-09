<?php

/**
 * Interface of worker controller.
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @package ext.worker
 * @version 0.2
 * @since 0.2
 * @see AbstractWorkerController
 */
interface IWorkerController
{
	/**
	 * @abstract
	 * @param string $actionId
	 * @return IWorkerAction
	 */
	public function createAction($actionId);
}
