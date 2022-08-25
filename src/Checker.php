<?php

namespace Ahs9\EdsChecker;

use Ahs9\EdsChecker\certificate\CertificateItem;

class Checker
{
    protected $certificateData;
    protected $userData;

    protected $errors = [];

    /**
     * @param ComparedData $userData
     * @param ComparedData $certificateData
     */
    public function __construct(ComparedData $userData, ComparedData $certificateData)
    {
        $this->setUserData($userData);
        $this->setCertificateData($certificateData);
    }

    /**
     * @param ComparedData $userData
     * @return void
     */
    public function setUserData(ComparedData $userData)
    {
        $this->userData = $userData;
    }

    /**
     * @param ComparedData $certificateData
     * @return void
     */
    public function setCertificateData(ComparedData $certificateData)
    {
        $this->certificateData = $certificateData;
    }

    /**
     * @return bool
     */
    public function compare()
    {
        foreach (CertificateItem::getAllOid() as $oid) {
            $itemClass = CertificateItem::getClassByOid($oid);
            $item = new $itemClass();
            $certificateItem = $this->certificateData->getItemByOid($oid);
            $userItem = $this->userData->getItemByOid($oid);

            $certificateValue = $certificateItem !== null ? $certificateItem->getValue() : null;
            $userValue = $userItem !== null ? $userItem->getValue() : null;

            if (null === $userValue) {
                $this->addError(
                    $item::getLabel(),
                    'Отсутствуют данные пользователя.'
                );
                continue;
            }

            if (null === $certificateValue) {
                $this->addError(
                    $item::getLabel(),
                    'Отсутствуют данные в сертификате.'
                );
                continue;
            }

            if ($certificateValue !== $userValue) {
                $this->addError(
                    $item::getLabel(),
                    'Данные пользователя: ' . $userValue
                    . '. Данные сертификата: ' . $certificateValue
                );
            }
        }

        return $this->errors === [];
    }

    /**
     * @param string $label
     * @param string $message
     * @return void
     */
    protected function addError($label, $message)
    {
        $this->errors[$label] = $message;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}