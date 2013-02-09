<?php
/**
 * File contains class WorkerJob
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @link https://github.com/mitallast/yii-gearman
 * @copyright Alexey Korchevsky <mitallast@gmail.com> 2010-2011
 * @license https://github.com/mitallast/yii-gearman/blob/master/license
 */

/**
 * Class WorkerJob implements worker job interface realisation for gearman {@link http://gearman.org/}.
 * Object is transfer data to controller. Data may be only string, transfer don't use php- or JSON-serialisation.
 * It's not recommended use language-specific serialization, because workers may be written at another languages,
 * like C, C++, Python or Ruby.
 *
 * This is a wrapper to gearman job object. It's need to use alternative no-gearman realisation.
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @package ext.worker
 * @version 0.2
 * @since 0.2
 */
class WorkerJob extends CComponent implements IWorkerJob
{
	private $job;
	/**
	 * Constructor.
	 *
	 * @param GearmanJob $job
	 */
	public function __construct(GearmanJob $job)
	{
		$this->job = $job;
	}
	/**
	 * Get worker API called command name.
	 *
	 * @return string
	 */
	public function getCommandName()
	{
		return $this->job->functionName();
	}

	/**
	 * Get stream identifier.
	 *
	 * @return string
	 */
	public function getIdentifier()
	{
		return $this->job->unique();
	}

	/**
	 * Get data sending by task creator.
	 *
	 * @return string
	 */
	public function getWorkload()
	{
		return $this->job->workload();
	}

	/**
	 * Sends result data and the complete status update for this job.
     *
     * @link http://php.net/manual/en/gearmanjob.sendcomplete.php
     * @param string $result Serialized result data
     * @return bool
	 */
	public function sendComplete($data)
	{
		return $this->job->sendComplete($data);
	}

	/**
	 * Send exception to server or client error message.
	 *
	 * @param Exception|string $exception
	 * @return bool
	 */
	public function sendException($exception)
	{
		return $this->job->sendException($exception);
	}

    public function sendStatus()
    {
        $this->job->sendStatus(1, 1);
    }
}
