<?php
/**
 * 
 */

namespace amos\newsletter\utility;

use Yii;

class MailupUtility
{

    /**
     *
     * @param string $functionName
     * @param array $functionParameters [idMessage, param2, ..., [optional $params]]
     * @param boolean $lastFunctionParameterIsParams if you use filters or other params in functionParameters, set tris to true
     * @return array
     */
    public static function getAllDataFromFunction($functionName, $functionParameters = [], $lastFunctionParameterIsParams = false) 
    {
        $pageSize = 20;
        $pageNumber = 0;

        $newsletterModule = Yii::$app->getModule('newsletter');
        $mailServiceClassname = $newsletterModule->mail_service_driver;
        $mailService = new $mailServiceClassname();

        $params = [];
        if ($lastFunctionParameterIsParams) {
            $params = array_pop($functionParameters);
        }
        $params['pageSize'] = $pageSize;
        $params['pageNumber'] = $pageNumber;

        array_push($functionParameters, $params);

        $retObj = call_user_func_array([$mailService, $functionName], $functionParameters);
        $items = self::getArrayItems($retObj);
        if (!empty($retObj)) {
            $totalElementsCount = $retObj->TotalElementsCount;
        } else {
            $totalElementsCount = 0;
        }

        // calculate numeber of pages to call
        $numberOfPage = floor($totalElementsCount / $pageSize);
        if (($totalElementsCount % $pageSize) > 0){
            $numberOfPage++;
        }

        if ($numberOfPage > 1) {
            // first page already catched, now other pages
            for ($i=1; $i < $numberOfPage; $i++) { 
                $functionParameters = self::setPageSizeNumber($functionParameters, $pageSize, $i);
                $retObj = call_user_func_array([$mailService, $functionName], $functionParameters);
                $items = array_merge($items, self::getArrayItems($retObj));
            }
        }
        
        return $items;
    }


    /**
     * @param stdClass $stdObj
     * @return array
     */
    private static function getArrayItems($stdObj) {
        $toret = [];
        if (!empty($stdObj)) {
            foreach ($stdObj->Items as $objElem) {
                array_push($toret, get_object_vars($objElem));
            }
        }
        return $toret;
    }


    /**
     * @param array $functionParameters last element must be params parameter
     * @param int $pageSize
     * @param int $pageNumber
     * @return array
     */
    private static function setPageSizeNumber($functionParameters, $pageSize, $pageNumber)
    {
        $params = array_pop($functionParameters);
        $params['pageSize'] = $pageSize;
        $params['pageNumber'] = $pageNumber;
        array_push($functionParameters, $params);
        return $functionParameters;
    }

}