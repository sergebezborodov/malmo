<?php

/**
 * Base controller for application controllers
 * Adds addition methods
 *
 * @property string $flash
 * @property string $errorFlash
 */
class MController extends CController
{
    /**
     * Default model id GET param name for edit actions
     *
     * @var string
     */
    protected $modelIdParam = 'id';

    /**
     * Return true if current is main page
     * NOTE: if your application has different from SiteController
     * main controller you must override this method
     *
     * @return bool
     */
    public function getIsMainPage()
    {
        return $this->id == 'site' && $this->action->id == 'index';
    }


    /**
     * Sets user flash
     *
     * @param string $message
     * @param string $type flash type, default is 'success'
     */
    public function setFlash($message, $type = 'success')
    {
        Yii::app()->getComponent('user')->setFlash($type, $message);
    }

    /**
     * Sets error user flash
     *
     * @param string $message
     */
    public function setErrorFlash($message)
    {
        $this->setFlash($message, 'error');
    }

    /**
     * Loads model by it id or throw http exception if it not found
     *
     * @param string $modelClass
     * @param int $modelId
     * @return MActiveRecord
     */
    protected function loadModel($modelClass, $modelId = null)
    {
        if ($modelId === null && empty($_GET[$this->modelIdParam])) {
            throw new CHttpException(400, 'Не указан id модели');
        }
        $id  = $modelId === null ? $_GET[$this->modelIdParam] : $modelId;
        $model = MActiveRecord::model($modelClass)->findByPk($id);
        if ($model == null) {
            throw new CHttpException(404, "Model '{$model}' record #{$id} was not found");
        }
        return $model;
    }

    /**
     * Load model if exists id or create new instance
     *
     * @param string $modelClass
     * @param null|int $modelId
     * @return MActiveRecord
     */
    protected function loadOrCreateModel($modelClass, $modelId = null)
    {
        if ($modelId === null && empty($_GET['id'])) {
            return new $modelClass;
        }
        return $this->loadModel($modelClass, $modelId);
    }
}
