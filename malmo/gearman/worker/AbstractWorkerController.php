<?php
/**
 * File contains class AbstractWorkerController
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @link https://github.com/mitallast/yii-gearman
 * @copyright Alexey Korchevsky <mitallast@gmail.com> 2010-2011
 * @license https://github.com/mitallast/yii-gearman/blob/master/license
 */

/**
 * Class AbstractWorkerController is the customized base controller class for workers.
 * All worker controllers must extend from this class or implements interface IWorkerController.
 *
 * @abstract
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @package ext.worker
 * @version 0.2
 * @since 0.2
 */
abstract class AbstractWorkerController extends CController implements IWorkerController
{
	/**
	 * Creates the action instance based on the action name.
	 * The action can be either an inline action or an object.
	 * The latter is created by looking up the action map specified in {@link actions}.
	 *
	 * @param string $actionID ID of the action. If empty, the {@link defaultAction default action} will be used.
	 * @return IWorkerAction the action instance, null if the action does not exist.
	 * @see actions
	 */
	public function createAction($actionID)
	{
		if($actionID==='')
			$actionID=$this->defaultAction;
		if(method_exists($this,'action'.$actionID) && strcasecmp($actionID,'s')) // we have actions method
			return new WorkerInlineAction($this,$actionID);
		else
			return $this->createActionFromMap($this->actions(),$actionID,$actionID);
	}
}   