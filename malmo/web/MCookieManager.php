<?php

class MCookieManagerException extends MException {}

/**
 * Cookie Manager is component for manipulate all cookies in project.
 * Main task for it - all cookies configuration must be defined
 * in one place of project.
 *
 * @property array $cookies
 */
class MCookieManager extends CApplicationComponent implements ArrayAccess
{
    /**
     * @var array
     */
    protected $cookiesConfig = array();

    /**
     * Use md5 hash for cookie name
     *
     * @var bool
     */
    public $hashName = false;

    /**
     * Salt for hash
     *
     * @var string
     */
    public $hashSalt = 'wazzup';


    const NAME      = 'name';
    const VALUE     = 'value';
    const DOMAIN    = 'domain';
    const EXPIRE    = 'expire';
    const HTTP_ONLY = 'httpOnly';
    const PATH      = 'path';
    const SECURE    = 'secure';


    /**
     * Validate cookie
     *
     * @param string $alias
     */
    protected function validateCookie($alias)
    {
        if (!array_key_exists($alias, $this->cookiesConfig)) {
            throw new MCookieManagerException("Cookie with alias '{$alias}' doesn't found");
        }
        $cookieConfig = $this->cookiesConfig[$alias];

        if (empty($cookieConfig[self::NAME])) {
            throw new MCookieManagerException("Cookie with alias '{$alias}' doesn't have name");
        }
    }

    /**
     * Return cookie real name by alias
     *
     * @param string $alias
     * @return string
     */
    protected function getCookieNameByAlias($alias)
    {
        $name =  $this->cookiesConfig[$alias][self::NAME];
        return $this->hashName ? $this->hashName($this->hashSalt.$name) : $name;
    }

    /**
     * Hash cookie name
     *
     * @param string $name
     * @return string
     */
    protected function hashName($name)
    {
        return md5($name);
    }

    /**
     * Find cookie by alias and return it actual value
     *
     * @param string $alias
     * @return string
     */
    public function getCookie($alias)
    {
        $this->validateCookie($alias);

        /** @var CHttpCookie $cookie */
        $cookie = Yii::app()->getRequest()->getCookies()->itemAt($this->getCookieNameByAlias($alias));

        return $cookie ? $cookie->value : null;
    }

    /**
     * Sets cookie value by it alias
     *
     * @param string $alias
     * @param mixed $value
     */
    public function setCookie($alias, $value)
    {
        $this->validateCookie($alias);
        $cookie = new CHttpCookie($this->getCookieNameByAlias($alias), $value);
        $cookieConfig = $this->cookiesConfig[$alias];

        $attrs = array(self::DOMAIN, self::HTTP_ONLY, self::PATH, self::SECURE);
        foreach ($attrs as $attr) {
            if (array_key_exists($attr, $cookieConfig)) {
                $cookie->$attr = $cookieConfig[$attr];
            }
        }
        if (array_key_exists(self::EXPIRE, $cookieConfig)) {
            $cookie->expire = strtotime($cookieConfig[self::EXPIRE]);
        }

        Yii::app()->getRequest()->getCookies()->add($cookie->name, $cookie);
    }

    /**
     * Deletes cookie by alias
     * It's sets new cookie with life in past
     *
     * @param string $alias
     */
    public function removeCookie($alias)
    {
        $this->validateCookie($alias);
        Yii::app()->getRequest()->getCookies()->remove($this->getCookieNameByAlias($alias));
    }


    /**
     * Setter for config
     *
     * @param array $config
     */
    public function setCookies($config)
    {
        $this->cookiesConfig = $config;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return $this->getCookie($offset) != null;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->getCookie($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->setCookie($offset, $value);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->removeCookie($offset);
    }
}
