<?php

namespace Ahs9\EdsChecker\certificate;

class CommonName extends CertificateItem
{
    /**
     * @inheritdoc
     */
    public static function getOid()
    {
        return self::OID_COMMON_NAME;
    }

    /**
     * @inheritdoc
     */
    public function getFormatValue()
    {
        return mb_strtolower(trim(htmlspecialchars_decode($this->value)));
    }

    /**
     * @inheritdoc
     */
    public static function getLabel()
    {
        return 'CommonName';
    }
}