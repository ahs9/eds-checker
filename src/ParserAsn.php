<?php

namespace Ahs9\EdsChecker;

use Exception;
use FG\ASN1\ASNObject;
use FG\ASN1\Universal\ObjectIdentifier;
use FG\ASN1\Universal\Sequence;

class ParserAsn
{
    const TEMPLATE_RESULT = 'result';
    const TEMPLATE_SEQUENCE = 'sequence';
    const TEMPLATE_ARRAY = 'array';

    protected $pathTemplate;
    protected $certificate;
    protected $asn;
    protected $keys;

    protected $result;

    protected $splitedAsn;
    protected $splitedTemplate;

    /**
     * @param string $certificate
     * @param array $keys
     * @param array $pathTemplate
     */
    public function __construct(string $certificate, $keys, array $pathTemplate = [])
    {
        $this->certificate = $certificate;
        $this->pathTemplate = $pathTemplate;
        $this->result = new ComparedData($keys);
        $this->keys = $this->result->getOidList();
    }

    /**
     * @return ComparedData
     * @throws \FG\ASN1\Exception\ParserException
     * @throws Exception
     */
    public function getComparedData()
    {
        $this->setAsn();
        $this->splitedAsn = $this->asn;
        $this->splitedTemplate = $this->pathTemplate;

        $this->splitAsnByTemplate();
        $this->parseByOidKeys();

        return $this->result;
    }

    /**
     * Returns ASN object for template-debugging.
     * 
     * @return mixed
     */
    public function getSplitedAsn()
    {
        $this->setAsn();
        $this->splitedAsn = $this->asn;
        $this->splitedTemplate = $this->pathTemplate;

        $this->splitAsnByTemplate();

        return $this->splitedAsn;
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function parseByOidKeys()
    {
        $this->parseAsn($this->splitedAsn);
    }

    /**
     * @param $data
     * @return void
     * @throws Exception
     */
    protected function parseAsn($data)
    {
        foreach ($data as $child) {
            $content = $child->getContent();
            if (!($child instanceof Sequence)) {
                $this->parseAsn($content);
                continue;
            }

            if (!is_array($content)) {
                continue;
            }

            if (!($content[0] instanceof ObjectIdentifier)) {
                $this->parseAsn($content);
            } elseif (in_array($content[0]->getContent(), $this->keys)) {
                $this->addToResult($content);
            } else {
                $this->parseAsn($content);
            }
        }
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    protected function addToResult(array $data)
    {
        $key = $data[0]->getContent();
        $value = $data[1]->getContent();

        if ($this->result->getItemByOid($key)->getValue() !== null)
            throw new Exception('OID ' . $key . ' is not unique in certificate.');

        if (is_array($value)) {
            $this->result->setValueByOid($key, $this->parseArray($value));
        } else {
            $this->result->setValueByOid($key, $value);
        }
    }

    /**
     * @param array $array
     * @return mixed|void
     */
    protected function parseArray(array $array)
    {
        foreach ($array as $item) {
            $content = $item->getContent();
            if (is_array($content)) {
                $this->parseArray($content);
            } else {
                return $content;
            }
        }
    }

    /**
     * @param int|string $key
     * @return bool
     */
    protected function need($key): bool
    {
        return in_array($key, $this->keys);
    }

    /**
     * @return void
     * @throws \FG\ASN1\Exception\ParserException
     */
    protected function setAsn()
    {
        $binary = base64_decode($this->certificate);
        $this->asn = ASNObject::fromBinary($binary);
    }

    /**
     * @return void
     */
    protected function splitAsnByTemplate()
    {
        if (!is_array($this->splitedTemplate)) {
            return;
        }

        foreach ($this->splitedTemplate as $k => $v) {
            $this->splitedTemplate = $v;
            if ($v === self::TEMPLATE_RESULT) {
                $this->splitedAsn = $this->splitedAsn->getContent();
            }
            elseif ($k === self::TEMPLATE_SEQUENCE) {
                $this->splitedAsn = $this->splitedAsn->getContent();
                $this->splitAsnByTemplate();

            }
            elseif ($k === self::TEMPLATE_ARRAY) {
                $this->splitArray();
                $this->splitAsnByTemplate();
            }

        }
    }

    /**
     * @return void
     */
    protected function splitArray()
    {
        foreach ($this->splitedTemplate as $k => $v) {
            if ($v === null)
                continue;

            $this->splitedAsn = $this->splitedAsn[$k];
            $this->splitedTemplate = $v;
            return;
        }
    }
}