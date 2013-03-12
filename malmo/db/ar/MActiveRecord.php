<?php

/**
 * Malmo base AR class
 * adds additional helpers
 */
class MActiveRecord extends CActiveRecord
{
    /**
     * Name of created date column
     * If your project has field name convention
     * set value for this in project based AR class
     *
     * @var string
     */
    protected $createdField = 'date_created';

    /**
     * Name of updated date column
     * If your project has field name convention
     * set value for this in project based AR class
     *
     * @var string
     */
    protected $updatedField = 'date_updated';

    /**
     * Use automatic update date values for fields
     * createdField, updatedField
     *
     * @var bool
     */
    protected $updateDates = true;


    /**
     * Automatic set null value into string fields
     * when string value is empty
     *
     * @var bool
     */
    protected $ensureNull = true;

    /**
     * Automatic set null value on update record
     * into string fields when string value is empty
     *
     * @var bool
     */
    protected $ensureNullOnUpdate = true;

    /**
     * Update created and update fields for actual values
     */
    private function updateDates()
    {
        if (isset($this->getMetaData()->tableSchema->columns[$this->updatedField])) {
            $this->{$this->updatedField} = new CDbExpression('NOW()');
        }

        if ($this->isNewRecord
            && !empty($this->{$this->createdField})
            && isset($this->getMetaData()->tableSchema->columns[$this->createdField])) {

            $this->{$this->createdField} = new CDbExpression('NOW()');
        }
    }

    /**
     * Ensure null for string fields
     */
    private function ensureNull()
    {
        foreach ($this->getTableSchema()->columns as $column) {
            if ($column->allowNull && trim($this->getAttribute($column->name)) === '') {
                $this->setAttribute($column->name, null);
            }
        }
    }

    /**
     * Before record save
     *
     * @return bool
     */
    public function beforeSave()
    {
        if (!parent::beforeSave()) {
            return false;
        }

        if ($this->updateDates) {
            $this->updateDates();
        }

        if ($this->ensureNull && (!$this->ensureNullOnUpdate && !$this->getIsNewRecord())) {
            $this->ensureNull();
        }

        return true;
    }
}
