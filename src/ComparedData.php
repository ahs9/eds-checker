<?php

namespace Ahs9\EdsChecker;

use Ahs9\EdsChecker\certificate\CertificateItem;

/**
 * Class describes the set of available for comparing data.
 */
class ComparedData
{
    protected $items = [];

    /**
     * @param $oidArray
     */
    public function __construct($oidArray)
    {
        foreach ($oidArray as $key => $value) {
            if (is_string($key)) {
                $this->addItemByOid($key, $value);
            } elseif (is_int($key)) {
                $this->addItemByOid($value, null);
            }
        }
    }

    /**
     * @param string $oid
     * @return CertificateItem|null
     */
    public function getItemByOid($oid)
    {
        foreach ($this->items as $item) {
            if ($item->getOid() === $oid)
                return $item;
        }
        return null;
    }

    /**
     * @param string $oid
     * @param $value
     * @return void
     */
    public function addItemByOid($oid, $value)
    {
        if (isset($this->items[$oid]) && $this->items[$oid] !== null)
            return;

        $class = CertificateItem::getClassByOid($oid);

        if ($class === null)
            return;

        $item = new $class();
        $item->setValue($value);
        $this->items[$oid] = $item;
    }

    /**
     * @param string $oid
     * @param $value
     * @return void
     */
    public function setValueByOid($oid, $value)
    {
        foreach ($this->items as $item) {
            if ($item->getOid() !== $oid)
                continue;

            $item->setValue($value);
        }
    }

    /**
     * @return array
     */
    public function getOidList()
    {
        $result = [];
        foreach ($this->items as $item) {
            $result[] = $item->getOid();
        }
        return $result;
    }
}