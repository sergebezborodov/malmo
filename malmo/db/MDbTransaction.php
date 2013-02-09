<?php

/**
 * Extended transaction
 */
class MDbTransaction extends CDbTransaction
{
    /**
     * @var int nested transaction instances count
     */
    protected static $nestedCount = 0;

    /**
     * @var int transaction index in stack
     */
    protected $index = 0;

    /**
     * @param MDbConnection $connection
     */
    public function __construct(MDbConnection $connection)
    {
        parent::__construct($connection);

        $this->index = 0;
        if ($connection->emulateNestedTransaction) {
            $this->index = self::$nestedCount++;
        }
    }


    /**
     * Commits a transaction.
     * @throws CException if the transaction or the DB connection is not active.
     */
    public function commit()
    {
        /** @var MDbConnection $connection */
        $connection = $this->getConnection();
        if ($connection->emulateNestedTransaction) {
            self::$nestedCount--;
            if ($this->index == 0) {
                parent::commit();
            }
        } else {
            parent::commit();
        }
    }

    /**
     * Rolls back a transaction.
     * @throws CException if the transaction or the DB connection is not active.
     */
    public function rollback()
    {
        /** @var MDbConnection $connection */
        $connection = $this->getConnection();
        if ($connection->emulateNestedTransaction) {
            self::$nestedCount--;
            if ($this->index == 0) {
                parent::rollback();
            }
        } else {
            parent::rollback();
        }
    }
}
