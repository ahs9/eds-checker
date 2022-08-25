<?php

namespace Ahs9\EdsChecker\certificate;

class Ogrn extends CertificateItem
{
    /**
     * @inheritdoc
     */
    public static function getOid()
    {
        return self::OID_OGRN;
    }

    /**
     * @inheritdoc
     */
    public function getFormatValue()
    {
        return ltrim(trim((string)$this->value), '0');
    }

    /**
     * @inheritdoc
     */
    public static function getLabel()
    {
        return 'OGRN';
    }
}