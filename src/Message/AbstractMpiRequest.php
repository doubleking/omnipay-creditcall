<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Common\Message\AbstractRequest;
use SimpleXMLElement;

/**
 * Creditcall Abstract 3D Secure Request
 * @method Abstrampsend()
 */
abstract class AbstractMpiRequest extends AbstractRequest
{

    protected $liveEndpoint = 'https://mpi.cardeasexml.com';
    protected $testEndpoint = 'https://testmpi.cardeasexml.com';

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getAcquirer()
    {
        return $this->getParameter('acquirer');
    }

    public function setAcquirer($value)
    {
        return $this->setParameter('acquirer', $value);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    protected function getBaseData()
    {
        $data = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Request/>');

        return $data;
    }

    public function sendData($data)
    {
        $headers = array(
            'Content-Type' => 'text/xml',
        );

        $httpResponse = $this->httpClient
            ->post(
                $this->getEndpoint(),
                $headers,
                $data->asXML(),
                $this->getOptions()
            )
            ->send();
        return $this->createResponse($httpResponse->xml());
    }

    public function getEndpoint()
    {
        if ($this->getTestMode()) {
            return $this->testEndpoint;
        }

        return $this->liveEndpoint;
    }

    protected function getOptions()
    {
        $options = array();

        if ($this->getTestMode()) {
            $options['verify'] = false;
        }

        return $options;
    }

    abstract protected function createResponse($data);
}
