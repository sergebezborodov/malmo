<?php

class MDbCommandException extends MException {}

/**
 * Extended Db Command
 * Add helpfull methods for query bulding
 */
class MDbCommand extends CDbCommand
{

    /**
     * Add where query with specity operator
     *
     * @param mixed $conditions the conditions that should be put in the WHERE part.
     * @param array $params the parameters (name=>value) to be bound to the query
     * @param string $operator AND|OR
     * @return MDbCommand the command object itself
     * @deprecated
     */
    public function addWhere($conditions, $params = array(), $operator = 'AND')
    {
        $operator = strtolower($operator);
        if ($operator == 'and') {
            $this->andWhere($conditions, $params);
        } else {
            $this->orWhere($conditions, $params);
        }

        return $this;
    }



    /**
     * Executes the SQL statement and returns all rows.
     * If result has two columns, return result will be
     * array with first column as key, and second column as value
     *
     * If result has more than two columns, return array has
     * first column as key and other columns as subarray
     *
     * @param bool $fetchAssociative
     * @param array $params
     * @return array
     */
    public function queryAssoc($fetchAssociative = true, $params = array())
    {
        if (($result = $this->queryAll($fetchAssociative, $params)) === array()) {
            return array();
        }

        if (($columnsCount = count($result[0])) == 1) {
            throw new MDbCommandException('Result of query must have more than one column.');
        }

        $items = array();
        if ($columnsCount == 2) {
            foreach ($result as $v) {
                $items[array_shift($v)] = array_shift($v);
            }
        } else {
            foreach ($result as $v) {
                $items[reset($v)] = $v;
            }
        }

        return $items;
    }
}
