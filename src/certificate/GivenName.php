<?php

namespace Ahs9\EdsChecker\certificate;

class GivenName extends CertificateItem
{
    /**
     * @inheritdoc
     */
    public static function getOid()
    {
        return self::OID_GIVEN_NAME;
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
        return 'GivenName';
    }
}