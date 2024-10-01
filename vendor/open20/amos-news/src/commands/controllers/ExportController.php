<?php

namespace open20\amos\news\commands\controllers;

use open20\amos\news\AmosNews;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use open20\amos\news\models\News;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use open20\amos\taskmanager\models\TaskManagerJob;


/**
 * Export controller
 */
class ExportController extends \yii\console\Controller
{
    /**
     * @var string $filename
     */
    public $filename = 'news_export.xlsx';

    /**
     * @var array $data
     */
    public $data = [];

    /**
     * @var string[] $passedOptions
     */
    public $passedOptions = [
        'filename',
        'data'
    ];

    /**
     * @param $actionID
     * @return array|string[]
     */
    public function options($actionID)
    {
        return ArrayHelper::merge(parent::options($actionID), $this->passedOptions);
    }

    /**
     * @return void
     */
    public function init()
    {
        $this->module = AmosNews::instance();
        parent::init();
    }

    /**
     * @return void
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     * @throws \yii\base\InvalidConfigException
     * @throws Exception
     */
    public function actionExportNews()
    {
        $filename = $this->filename;

        // Base news query
        $rawQuery = htmlspecialchars_decode($this->data['query']);
        $rawQuery .= ' ORDER BY news.created_at DESC';
        $newsQuery = \Yii::$app->db->createCommand($rawQuery);

        $newsModels = $newsQuery->queryAll();

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();

        // Excel header columns
        $sheet->setCellValue('A1', 'Titolo');
        $sheet->setCellValue('B1', 'Stato');
        $sheet->setCellValue('C1', 'Creatore');
        $sheet->setCellValue('D1', 'Ultima modifica');
        $sheet->setCellValue('E1', 'Data ultima modifica');
        $sheet->setCellValue('F1', 'Inizio pubblicazione');
        $sheet->setCellValue('G1', 'Fine pubblicazione');
        $sheet->setCellValue('H1', 'Categoria');

        // Populate rows
        $row = 2;
        foreach ($newsModels as $news) {
            $model = News::findOne($news['id']);
            if ($model->hasWorkflowStatus()) {
                $statusLabel = $model->getWorkflowStatus()->getLabel();
            } else {
                $statusLabel = '--';
            }
            $createdBy = $model->createdUserProfile->nomeCognome;
            $updatedBy = $model->updatedUserProfile->nomeCognome;
            if (is_null($model->data_pubblicazione)) {
                $dataPubblicazione = AmosNews::t('amosnews', 'Immediata');
            } else {
                $dataPubblicazione = Yii::$app->formatter->asDatetime($model->data_pubblicazione, 'php:d/m/Y H:i');
            }
            if (is_null($model->data_rimozione) || $model->data_rimozione == '9999-12-31 00:00:00') {
                $dataRimozione = AmosNews::t('amosnews', 'Mai');
            } else {
                $dataRimozione = Yii::$app->formatter->asDatetime($model->data_rimozione, 'php:d/m/Y H:i');
            }

            $sheet->setCellValue('A' . $row, $model->titolo);
            $sheet->setCellValue('B' . $row, $statusLabel);
            $sheet->setCellValue('C' . $row, $createdBy);
            $sheet->setCellValue('D' . $row, $updatedBy);
            $sheet->setCellValue('E' . $row, \Yii::$app->formatter->asDatetime($model->updated_at, 'php:d/m/Y H:i'));
            $sheet->setCellValue('F' . $row, $dataPubblicazione);
            $sheet->setCellValue('G' . $row, $dataRimozione);
            $sheet->setCellValue('H' . $row, $model->newsCategorie->titolo);

            $row++;
        }

        // Set columns alignment
        $sheet->getStyle('A1:H' . ($row - 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        // Set sheet title
        $sheet->setTitle('News');

        // Save file
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($filename);

        Console::stdout("News exported successfully.\n");
    }

}