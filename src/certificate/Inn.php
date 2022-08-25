<?php

namespace Ahs9\EdsChecker\certificate;

class Inn extends CertificateItem
{
    /**
     * @inheritdoc
     */
    public static function getOid()
    {
        return self::OID_INN;
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
        return 'ИНН';
    }
}