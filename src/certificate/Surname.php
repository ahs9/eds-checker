<?php

namespace Ahs9\EdsChecker\certificate;

class Surname extends CertificateItem
{
    /**
     * @inheritdoc
     */
    public static function getOid()
    {
        return self::OID_SURNAME;
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
        return 'Surname';
    }
}