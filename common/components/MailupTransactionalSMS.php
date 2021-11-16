<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\utility
 * @category   CategoryName
 */

namespace common\components;

use Exception;
use Yii;
use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\httpclient\Client;

/**
 * Class MailupTransactionalSMS
 * @package common\components
 */
class MailupTransactionalSMS extends Component
{

    private $content = null;
    private $listGuid = null;
    private $listSecret = null;
    private $recipient = null;
    private $campaignCode = null;
    private $dynamicFields = [];
    private $isUnicode = 0;
    private $sender = null;
    private $accountId = null;
    private $listId = null;
    private $endpoint = null;

    public function init()
    {
        $this->checkParams();

        parent::init();
    }

    /**
     *
     * @return void
     */
    private function checkParams()
    {
        if (isset(Yii::$app->params['mailup']['transactional-sms']['endpoint'])) {
            $this->endpoint = Yii::$app->params['mailup']['transactional-sms']['endpoint'];
        } else {
            throw new InvalidArgumentException('Define param for [mailup][transactional-sms][endpoint]');
        }

        if (isset(Yii::$app->params['mailup']['transactional-sms']['ListGuid'])) {
            $this->listGuid = Yii::$app->params['mailup']['transactional-sms']['ListGuid'];
        } else {
            throw new InvalidArgumentException('Define param for [mailup][transactional-sms][ListGuid]');
        }

        if (isset(Yii::$app->params['mailup']['transactional-sms']['ListSecret'])) {
            $this->listSecret = Yii::$app->params['mailup']['transactional-sms']['ListSecret'];
        } else {
            throw new InvalidArgumentException('Define param for [mailup][transactional-sms][ListSecret]');
        }

        if (isset(Yii::$app->params['mailup']['transactional-sms']['account-id'])) {
            $this->accountId = Yii::$app->params['mailup']['transactional-sms']['account-id'];
        } else {
            throw new InvalidArgumentException('Define param for [mailup][transactional-sms][account-id]');
        }

        if (isset(Yii::$app->params['mailup']['transactional-sms']['list-id'])) {
            $this->listId = Yii::$app->params['mailup']['transactional-sms']['list-id'];
        } else {
            throw new InvalidArgumentException('Define param for [mailup][transactional-sms][list-id]');
        }

        // sender is optional...
        if (isset(Yii::$app->params['mailup']['transactional-sms']['Sender'])) {
            $this->sender = Yii::$app->params['mailup']['transactional-sms']['Sender'];
        } 

    }

    /**
     * Undocumented function
     *
     * @return Object
     */
    public function sendSms()
    {

        $this->checkParams();
        $client = new Client(['baseUrl' => $this->getEndpoint()]);

        // check
        if (is_null($this->getIsUnicode())) {
            throw new InvalidArgumentException('Define isUnicode');
        }

        if (is_null($this->getContent())) {
            throw new InvalidArgumentException('Define content');
        }

        if (is_null($this->getRepient())) {
            throw new InvalidArgumentException('Define recipient');
        }

        if (is_null($this->getCampaignCode())) {
            throw new InvalidArgumentException('Define campaign code');
        }

        $dataArray = [
            'Content' => $this->getContent(),
            'ListGuid' => $this->getListGuid(),
            'ListSecret' => $this->getListSecret(),
            'Recipient' => $this->getRepient(),
            'CampaignCode' => $this->getCampaignCode(),
            'DynamicFields' => $this->getDynamicFields(),
            'isUnicode' => $this->getIsUnicode(),
            'Sender' => $this->getSender(),
        ];

        $response = $client->createRequest()
            ->setUrl('/api/v2.0/sms/' . $this->getAccountId() . '/' . $this->getListId())->setMethod('POST')
            ->setData($dataArray)
            ->send();

            // VarDumper::dump( $dataArray, $depth = 10, $highlight = true); 
            // VarDumper::dump( $response->content, $depth = 10, $highlight = true); 

        if (in_array($response->getStatusCode(),['200', '400', '403', '500'])) {
            $resultArray = Json::decode($response->getContent(), $asArray = true);
            
            if ($resultArray['Code'] == '0') {
                return $resultArray;
            } else {
                throw new Exception( $resultArray['State'] . '('. $resultArray['Code'] .'): ' . $resultArray['Description'] );
            }
            return Json::decode($response->getContent(), $asArray = true);
        } else {
            throw new Exception('Errore nell\'invio SMS...');
        }
                
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function setContent($content)
    {
        $this->content = trim($content);
    }

    public function getListGuid()
    {
        return $this->listGuid;
    }

    public function getListSecret()
    {
        return $this->listSecret;
    }

    public function getAccountId()
    {
        return $this->accountId;
    }

    public function getListId()
    {
        return $this->listId;
    }

    public function getRepient()
    {
        return $this->recipient;
    }

    public function setRecipient($recipient)
    {
        $this->recipient = trim($recipient);
    }

    public function getCampaignCode()
    {
        return $this->campaignCode;
    }

    public function setCampaignCode($campaignCode)
    {
        $this->campaignCode = trim($campaignCode);
    }

    public function getDynamicFields()
    {
        return $this->dynamicFields;
    }

    /**
     * pair nameField -> value
     *
     * @param array $dynamicFields
     * @return void
     */
    public function setDynamicFields($dynamicFields)
    {
        $this->dynamicFields = $dynamicFields;
    }

    public function getIsUnicode()
    {
        return $this->isUnicode;
    }

    public function setIsUnicode($isUnicode)
    {
        $this->isUnicode = $isUnicode;
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function setSenmder($sender)
    {
        $this->sender = $sender;
    }



}
