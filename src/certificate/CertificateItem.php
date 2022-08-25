<?php

namespace Ahs9\EdsChecker\certificate;

abstract class CertificateItem
{
    const OID_INN = '1.2.643.3.131.1.1';
    const OID_SURNAME = '2.5.4.4';
    const OID_COMMON_NAME = '2.5.4.3';
    const OID_GIVEN_NAME = '2.5.4.42';
    const OID_ORGANIZATION_NAME = '2.5.4.10';
    const OID_OGRN = '1.2.643.100.1';

    protected $value;

    /**
     * @return array
     */
    public static function getAllClasses()
    {
        return [
            self::OID_INN => Inn::class,
            self::OID_SURNAME => Surname::class,
            self::OID_COMMON_NAME => CommonName::class,
            self::OID_GIVEN_NAME => GivenName::class,
            self::OID_ORGANIZATION_NAME => OrganizationName::class,
            self::OID_OGRN => Ogrn::class,
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