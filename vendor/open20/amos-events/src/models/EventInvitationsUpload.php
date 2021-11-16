<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    openinnovation\organizations\controllers
 * @category   CategoryName
 */

namespace open20\amos\events\models;

use open20\amos\events\AmosEvents;
use yii\base\Model;
use yii\web\UploadedFile;

class EventInvitationsUpload extends Model
{

    public $excelFile;

    public function rules()
    {
        return [
            [['excelFile'], 'file', 'extensions' => 'xls, xlsx'],
        ];
    }
    
    public function parse()
    {
        $this->excelFile = UploadedFile::getInstance($this, 'excelFile');
        if ($this->validate()) {
            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($this->excelFile->tempName);
                $worksheet = $spreadsheet->getActiveSheet();
                $highestRow = $worksheet->getHighestDataRow();
                // $highestColumn = $worksheet->getHighestColumn();
                // $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
                for ($r = 2; $r <= $highestRow; ++$r) { // Skip first row
                    $rows[] = [
                        'email' => $worksheet->getCellByColumnAndRow(3, $r)->getValue(),
                        'fiscal_code' => $worksheet->getCellByColumnAndRow(4, $r)->getValue(),
                        'name' => $worksheet->getCellByColumnAndRow(1, $r)->getValue(),
                        'surname' => $worksheet->getCellByColumnAndRow(2, $r)->getValue(),
                    ];
                }
                return $rows;
            } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                return $e->getMessage();
            }
        } else {
            return false;
        }
    }

   public function attributeLabels()
    {
        return [
            'excelFile' => AmosEvents::txt('#invitations_excel_file'),
        ];
    }
}