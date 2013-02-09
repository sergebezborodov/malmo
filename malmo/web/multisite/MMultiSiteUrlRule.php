<?php

/**
 * Rule for url matching
 */
class MMultiSiteUrlRule extends MMultiSiteBaseRule
{
    /**
     * @var string url for matching
     */
    public $url;

    /**
     * @var bool check host url with subdomains
     */
    public $withSubdomains = false;

    /**
     * @return bool is current request math rule
     */
    public function getIsMath()
    {
        if (!isset($_SERVER['HTTP_HOST'])) {
            return false;
        }

        $host = $_SERVER['HTTP_HOST'];
        if (substr($host, 0, 4) == 'www.') {
            $host = substr($host, 4);
        }
        if (!$this->withSubdomains) {
            return strtolower($this->url) == strtolower($host);
        }

        return strpos(strtolower($host), strtolower($this->url)) !== false;
    }
}
