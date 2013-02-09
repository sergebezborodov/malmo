<?php
/**
 * File contains class WorkerInlineAction
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @link https://github.com/mitallast/yii-gearman
 * @copyright Alexey Korchevsky <mitallast@gmail.com> 2010-2011
 * @license https://github.com/mitallast/yii-gearman/blob/master/license
 */

/**
 * Class WorkerInlineAction represents an action that is defined as a controller method.
 *
 * The method name is like 'actionXYZ' where 'XYZ' stands for the action name.
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @package ext.worker
 * @version 0.2
 * @since 0.2
 */
class WorkerInlineAction extends AbstractWorkerAction
{
	/**
	 * Runs worker action.
	 *
	 * @throws CException
	 * @return array hash array(controllerId, actionName)
	 */
	public function run()
	{
		if(!($this->getJob() instanceof IWorkerJob))
		{
			throw new CException(Yii::t("worker", "Gearman job object not setted to controller action"));
		}

		$controller=$this->getController();
		$methodName='action'.$this->getId();
		$method=new ReflectionMethod($controller,$methodName);
		if($method->getNumberOfParameters() == 1)
		{

			return $method->invokeArgs($controller,array(
				$this->getJob()
			));
		}
		else
			throw new CException("Controller action must contains 1 parameter");
	}
}