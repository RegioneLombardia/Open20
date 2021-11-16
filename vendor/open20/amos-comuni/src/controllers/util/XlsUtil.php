<?php

namespace open20\amos\comuni\controllers\util;

use PHPExcel_IOFactory;
use yii\base\Exception;

class XlsUtil {

    public static $objPHPExcel = null;
    public static $phpExcelReader = null;

    /**
     * carica il file inviato in input e genera l'oggetto PHPExcel
     *
     * @param: file input: path completo
     * @return boolean
     */
    public static function createReader($readerType = null) {
        // $this->objPHPExcelLocation = $inputFileName;
        try {
            return PHPExcel_IOFactory::createReader($readerType);
        } catch (Exception $ex) {
            pr($ex->getMessage(), 'Errore nel load del file Excel');
        }
        return false;
    }

    /**
     * carica il file inviato in input e genera l'oggetto PHPExcel
     *
     * @param: file input: path completo
     * @return boolean
     */
    public static function load($inputFileName = null, $delimiter = ',') {
        // $this->objPHPExcelLocation = $inputFileName;
        try {
            //libxml_use_internal_errors(true);

            $reader = PHPExcel_IOFactory::createReaderForFile($inputFileName);
            //$this->phpExcelReader = $this->createReader($readerType);
            if (preg_match('/csv/i', get_class($reader))) {
                $reader->setDelimiter($delimiter);
            }
            $objPHPExcel = $reader->load($inputFileName);

            self::setObjPHPExcel($objPHPExcel);

            return true;
        } catch (Exception $ex) {
            pr($ex->getMessage(), 'Errore nel load del file Excel');
        }
        return false;
    }

    /**
     * restituisce tutte le celle di un determinato numero di righe a partire dalla prima
     * @param $howManyRows - numero di righe (0 = infinito)
     * @param $pIndex - indice della scheda di lavoro attiva
     *
     * @return array - righe dell'excel
     */
    public static function getRows($howManyRows = 0, $pIndex = 0) {
        if (!is_numeric($howManyRows)) {
            return array();
        }

        if ($howManyRows == 0) {
            return self::toArray($pIndex);
        }
        $highestColumn = self::getHighestColumn();
        return self::rangeToArray('A1:' . $highestColumn . $howManyRows, $pIndex);
    }

    /**
     * restituisce tutte le celle della scheda di lavoro selezionata
     * @param $pIndex - indice della scheda di lavoro attiva
     *
     * @return array - righe dell'excel
     */
    public static function toArray($pIndex = 0) {
        self::getObjPHPExcel()->setActiveSheetIndex($pIndex);
        return self::getObjPHPExcel()->getActiveSheet()->toArray();
    }

    /**
     * restituisce un range di celle della scheda di lavoro selezionata
     * @param $range - rage di celle richieste in formato excel (a1:b2)
     * @param $pIndex - indice della scheda di lavoro attiva
     *
     * @return array - righe dell'excel
     */
    public static function rangeToArray($range, $pIndex = 0) {
        self::getObjPHPExcel()->setActiveSheetIndex($pIndex);
        return self::getObjPHPExcel()->getActiveSheet()->rangeToArray($range);
    }

    public static function save($objPHPExcel, $objPHPExcelLocation) {
        if (empty($objPHPExcelLocation))
            throw new Exception("Error Processing Request", 1);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($objPHPExcelLocation);
        return true;
    }

    /**
     * legge i valori sui campi mappati nell'array di input, calcolati e formattati
     * @param: oggetto PHPExcel
     * @param: $cells array di celle $v; coordinate della cella da leggere
     * @param: nome della worksheet
     *
     * @return array $k=>$v delle celle lette
     */
    public static function formulaReader($objPHPExcel = null, $cells = array(), $sheetName = null) {

        $cellValues = array();
        try {
            $objPHPExcel->setActiveSheetIndexByName($sheetName);

            foreach ($cells as $cell) {
                $cell = strtoupper($cell);
                $cellValues[$cell] = $objPHPExcel->getActiveSheet()->getCell($cell)->getFormattedValue();
            }
            return $cellValues;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * scrive i valori sui campi mappati nell'array di input
     *
     * @param: $cells array di celle $k => $v; coordinate => nuovo valore
     * @param: nome della worksheet
     */
    public static function formulaWriter($objPHPExcel = null, $cells = array(), $sheetName = null) {
        self::disableCalculationCache();
        try {
            $objPHPExcel->setActiveSheetIndexByName($sheetName);
            foreach ($cells as $cell => $value) {
                $objPHPExcel->getActiveSheet()->setCellValue(strtoupper($cell), $value);
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function disableCalculationCache() {
        //DISABILITA LA CACHE per poter operare più volte in scrittura sulla stessa cella
        //se disabiliti la CACHE ci mette due ore a fare tutti i calcoli
        PHPExcel_Calculation::getInstance(self::getObjPHPExcel())->disableCalculationCache();
    }

    public static function getHighestColumn() {
        return self::getObjPHPExcel()->getSheet()->getHighestColumn();
    }

    public static function getObjPHPExcel() {
        if (is_null(self::$objPHPExcel)) {
            throw new Exception("objPHPExcel cannot be null", 1);
        }
        return self::$objPHPExcel;
    }

    protected static function setObjPHPExcel($objPHPExcel) {
        if (is_null($objPHPExcel)) {
            throw new Exception("objPHPExcel cannot be null", 1);
        }
        self::$objPHPExcel = $objPHPExcel;
    }

    public static function cleanFile($inputFileName) {
        try {
            // Create an array to hold the data
            $arrData = array();

            // Create a variable to hold the header information
            $header = NULL;

            $content_file = file_get_contents($inputFileName);
            if (preg_match('/<.*?>/', $content_file, $res)) {
                $first_html_tag = $res[0];
                $str_point = strpos($content_file, $first_html_tag);

                $new_content = str_replace(substr($content_file, $str_point), "", $content_file);
                file_put_contents($inputFileName, $new_content);
            }
//pr($new_content);
            return;
            /* if (preg_match('/<.*?>([^\<]*)<\/.*?>/', $content_file)) {
              $content_file = preg_replace('/<.*?>([^\<]*)<\/.*?>/', '', $content_file);
              pr("rimuovo");
              } */

            // If the file can be opened as readable, bind a named resource
            if (($handle = fopen($inputFileName, 'r')) !== FALSE) {
                // Loop through each row
                while (($row = fgetcsv($handle)) !== FALSE) {
                    pr($row, "row");
//pr($header, "header");
                    // Loop through each field
                    foreach ($row as &$field) {
                        $tmp_field = $field;
                        // Remove any invalid or hidden characters
                        if (preg_match('%<.*?>.*</.*?>\r%', $field)) {
                            $field = preg_replace('%<.*?>.*</.*?>\r%', '', $field);
                            pr($tmp_field, "rimuovo");
                        }
                    }

                    /* // If the header has been stored
                      if ($header){
                      // Create an associative array with the data
                      $arrData[] = array_combine($header, $row);
                      }
                      // Else the header has not been stored
                      else {
                      // Store the current row as the header
                      $header = $row;
                      } */
                }

                // Close the file pointer
                fclose($handle);
            }
        } catch (Exception $ex) {
            pr($ex->getMessage(), 'Errore Clean File');
        }
    }

}
