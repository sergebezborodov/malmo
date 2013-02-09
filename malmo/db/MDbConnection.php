<?php

class MDbConnectionException extends CDbException
{}

/**
 * Extended DbConnection class
 * Add addition functionality for transactions
 */
class MDbConnection extends CDbConnection
{
    /**
     * For db that has no support nested transaction
     * DbConnection will returns current transation from nested call method beginTransaction
     * instead throwing expection
     *
     * @var bool
     */
    public $emulateNestedTransaction = true;

    /**
     * @var MDbTransaction
     */
    protected $_transaction;

    /**
     * Starts a transaction.
     *
     * @return CDbTransaction the transaction initiated
     */
    public function beginTransaction()
    {
        if (!$this->emulateNestedTransaction) {
            return parent::beginTransaction();
        }

        if (($transaction = $this->getCurrentTransaction()) == null) {
            Yii::trace('Starting transaction', 'malmo.db.CDbConnection');
            $this->setActive(true);
            $this->getPdoInstance()->beginTransaction();

            $transaction = $this->_transaction = new MDbTransaction($this);
        } else {
            $transaction = new MDbTransaction($this);
        }

        return $transaction;
    }

    /**
     * Returns the currently active transaction.
     * @return CDbTransaction the currently active transaction. Null if no active transaction.
     */
    public function getCurrentTransaction()
    {
        if ($this->_transaction !== null) {
            if ($this->_transaction->getActive()) {
                return $this->_transaction;
            }
        }
        return null;
    }

    /**
     * Creates a command for execution.
     *
     * @param mixed $query the DB query to be executed. This can be either a string representing a SQL statement,
     * or an array representing different fragments of a SQL statement. Please refer to {@link CDbCommand::__construct}
     * for more details about how to pass an array as the query. If this parameter is not given,
     * you will have to call query builder methods of {@link CDbCommand} to build the DB query.
     *
     * @return CDbCommand the DB command
     */
    public function createCommand($query = null)
    {
        $this->setActive(true);
        return new MDbCommand($this, $query);
    }
}
