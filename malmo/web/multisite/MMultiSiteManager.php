<?php

class MMultiSiteManagerException extends MException {}

/**
 * Multi site detector component
 *
 * @property array $units
 * @property array $multisites
 * @property MMultiSite $active
 */
class MMultiSiteManager extends CApplicationComponent
{
    /**
     * @var array
     */
    protected $unitsConfig = array();

    /**
     * @var array
     */
    protected $_units;

    /**
     * @var array
     */
    protected $multisitesConfig;

    /**
     * @var array
     */
    protected $_multisites;

    /**
     * @var MMultiSite
     */
    protected $_active;

    const UNIT_CLASS = 'MMultiSiteUnit';

    const MULTISITE_CLASS = 'MMultiSite';

    /**
     * Init component
     */
    public function init()
    {}

    /**
     * Creates and return units instances
     *
     * @return array
     */
    public function getUnits()
    {
        if ($this->_units !== null) {
            return $this->_units;
        }

        $ins = array();
        foreach ($this->unitsConfig as $type => $items) {
            foreach ($items as $name => $config) {
                if (is_numeric($name) && is_string($config)) {
                    $name = $config;
                    $config = array();
                }
                if (empty($config['class'])) {
                    $config['class'] = self::UNIT_CLASS;
                }
                /** @var MMultiSiteUnit $unit */
                $unit = Yii::createComponent($config);
                $unit->type = $type;
                $unit->name = $name;
                $unit->init();

                $ins[$type][$name] = $unit;
            }
        }

        return $this->_units = $ins;
    }

    /**
     * Sets units config
     *
     * @param array $units
     */
    public function setUnits($units)
    {
        $this->_units = null;
        $this->unitsConfig = $units;
    }

    /**
     * Find and returns units by it type
     * If type doesn't found, return empty array
     *
     * @param string $type
     * @return MMultiSiteUnit[]
     */
    public function findUnitsByType($type)
    {
        $units = $this->getUnits();
        if (isset($units[$type])) {
            return $units[$type];
        }
        return array();
    }

    /**
     * Find and return ONE unit by id or name with type
     * If nothing found, return null
     *
     * @param string|int $idOrName
     * @param string $type
     * @return MMultiSiteUnit
     */
    public function findUnitByIdOrName($idOrName, $type)
    {
        if (($units = $this->findUnitsByType($type)) == array()) {
            return null;
        }
        foreach ($units as $unit) {
            if ($unit->id == $idOrName || $unit->name == $idOrName) {
                return $unit;
            }
        }
        return null;
    }


    /**
     * Creates and return multisites instances
     *
     * @return MMultiSite[]
     */
    public function getMultiSites()
    {
        if ($this->_multisites !== null) {
            return $this->_multisites;
        }

        $items = array();
        foreach ($this->multisitesConfig as $name => $config) {
            if (empty($config['class'])) {
                $config['class'] = self::MULTISITE_CLASS;
            }

            /** @var MMultiSite $ms */
            $ms = Yii::createComponent($config, $this);
            $ms->name = $name;
            $ms->init();

            $items[$name] = $ms;
        }

        return $this->_multisites = $items;
    }

    /**
     * Set's multisites config
     *
     * @param array $items
     */
    public function setMultiSites($items)
    {
        $this->_multisites = null;
        $this->multisitesConfig = $items;
    }

    /**
     * Detects and return active multisite instance
     *
     * @param bool $refresh
     * @return MMultiSite
     */
    public function getActive($refresh = false)
    {
        if ($this->_active === null || $refresh) {
            foreach ($this->getMultiSites() as $multiSite) {
                if ($multiSite->getIsCurrent()) {
                    $this->_active = $multiSite;
                    break;
                }
            }
        }
        return $this->_active;
    }

    /**
     * Sets active multisite by it id or name
     * not instance
     *
     * @param int|string $idOrName
     */
    public function setActive($idOrName)
    {
        $ms = $this->findMultiSite($idOrName);
        if (!$ms) {
            throw new MMultiSiteManagerException("Multisite with key '{$idOrName}' not found!");
        }
        $this->_active = $ms;
    }

    /**
     * Find and return multisite instance by it id or name
     * Method doen't change internal component state
     *
     * @param int|string $idOrName
     * @return MMultiSite
     */
    public function findMultiSite($idOrName)
    {
        foreach ($this->getMultiSites() as $multisite) {
            if ($multisite->id == $idOrName || $multisite->name == $idOrName) {
                return $multisite;
            }
        }
        return null;
    }
}
