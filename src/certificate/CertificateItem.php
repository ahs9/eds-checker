<?php

namespace Ahs9\EdsChecker\certificate;

abstract class CertificateItem
{
    const OID_INN = '1.2.643.3.131.1.1';
    const OID_SURNAME = '2.5.4.4';

    protected $value;

    /**
     * @return array
     */
    public static function getAllClasses()
    {
        return [
            self::OID_INN => Inn::class,
            self::OID_SURNAME => Surname::class,
        ];
    }

    /**
     * @return string[]
     */
    public static function getAllOid()
    {
        return array_keys(self::getAllClasses());
    }

    /**
     * @param string $oid
     * @return string|null
     */
    public static function getClassByOid($oid)
    {
        $allClasses = self::getAllClasses();
        return $allClasses[$oid] ?? null;
    }

    /**
     * @return string
     */
    abstract public static function getOid();

    /**
     * @param $value
     * @return void
     */
    public function setValue($value) {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    abstract public function getFormatValue();

    /**
     * @return string
     */
    abstract public static function getLabel();
}