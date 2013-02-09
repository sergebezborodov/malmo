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
     */
    public function addWhere($conditions, $params = array(), $operator = 'AND')
    {
        $operator = strtoupper($operator);
        if ($operator != 'OR' && $operator != 'AND') {
            throw new MDbCommandException('Invalid operator for command');
        }

        $where = $this->getWhere();
        $where .= ' ' . $operator . ' ' . $this->processConditions($conditions);
        $this->setWhere($where);

        foreach($params as $k => $v) {
            $this->params[$k] = $v;
        }

        return $this;
    }

    /**
     * Generates the condition string that will be put in the WHERE part
     * @param mixed $conditions the conditions that will be put in the WHERE part.
     * @return string the condition string to put in the WHERE part
     */
    private function processConditions($conditions)
    {
        if(!is_array($conditions))
            return $conditions;
        elseif($conditions===array())
            return '';
        $n=count($conditions);
        $operator=strtoupper($conditions[0]);
        if($operator==='OR' || $operator==='AND')
        {
            $parts=array();
            for($i=1;$i<$n;++$i)
            {
                $condition=$this->processConditions($conditions[$i]);
                if($condition!=='')
                    $parts[]='('.$condition.')';
            }
            return $parts===array() ? '' : implode(' '.$operator.' ', $parts);
        }

        if(!isset($conditions[1],$conditions[2]))
            return '';

        $column=$conditions[1];
        if(strpos($column,'(')===false)
            $column=$this->getConnection()->quoteColumnName($column);

        $values=$conditions[2];
        if(!is_array($values))
            $values=array($values);

        if($operator==='IN' || $operator==='NOT IN')
        {
            if($values===array())
                return $operator==='IN' ? '0=1' : '';
            foreach($values as $i=>$value)
            {
                if(is_string($value))
                    $values[$i]=$this->getConnection()->quoteValue($value);
                else
                    $values[$i]=(string)$value;
            }
            return $column.' '.$operator.' ('.implode(', ',$values).')';
        }

        if($operator==='LIKE' || $operator==='NOT LIKE' || $operator==='OR LIKE' || $operator==='OR NOT LIKE')
        {
            if($values===array())
                return $operator==='LIKE' || $operator==='OR LIKE' ? '0=1' : '';

            if($operator==='LIKE' || $operator==='NOT LIKE')
                $andor=' AND ';
            else
            {
                $andor=' OR ';
                $operator=$operator==='OR LIKE' ? 'LIKE' : 'NOT LIKE';
            }
            $expressions=array();
            foreach($values as $value)
                $expressions[]=$column.' '.$operator.' '.$this->getConnection()->quoteValue($value);
            return implode($andor,$expressions);
        }

        throw new CDbException(Yii::t('yii', 'Unknown operator "{operator}".', array('{operator}'=>$operator)));
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
                $items[array_shift($v)] = $v;
            }
        }

        return $items;
    }
}
