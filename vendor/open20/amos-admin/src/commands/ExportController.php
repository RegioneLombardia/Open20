<?php

namespace open20\amos\admin\commands;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
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
    public $filename = 'user_profile_export.xlsx';

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
        $this->module =  \Yii::$app->getModule(AmosAdmin::getModuleName());
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
    public function actionExportUsers()
    {
        $filename = $this->filename;
        Console::stdout('start export' . PHP_EOL);
        // Base news query
        $rawQuery = htmlspecialchars_decode($this->data['query']);
        $rawQuery .= ' ORDER BY user_profile.created_at DESC';
        $userProfileQuery = \Yii::$app->db->createCommand($rawQuery);

        $profileModels = $userProfileQuery->queryAll();

        $emptModelUserProfile = new UserProfile();

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();


        // Excel header columns
        $sheet->setCellValue('A1', 'Id');
        $sheet->setCellValue('B1', 'Nome');
        $sheet->setCellValue('C1', 'Cognome');
        $sheet->setCellValue('D1', 'Stato');
        $sheet->setCellValue('E1', 'Email');
        $sheet->setCellValue('F1', $emptModelUserProfile->getAttributeLabel('prevalentPartnership'));
        $sheet->setCellValue('G1', $emptModelUserProfile->getAttributeLabel('facilitatore'));
        $sheet->setCellValue('H1', $emptModelUserProfile->getAttributeLabel('notify_from_editorial_staff'));
        $sheet->setCellValue('I1', 'Creato il');
        $sheet->setCellValue('J1', 'Aggiornato il');
        $sheet->setCellValue('J1', 'Ultimo accesso');

        // Populate rows
        $row = 2;
        foreach ($profileModels as $userProfile) {
            $model = UserProfile::findOne($userProfile['id']);
            if ($model->hasWorkflowStatus()) {
                $statusLabel = $model->getWorkflowStatus()->getLabel();
            } else {
                $statusLabel = '-';
            }

            $dataCreazione = '-';
            if (!is_null($model->created_at)) {
                    $dataCreazione = Yii::$app->formatter->asDatetime($model->created_at, 'php:d/m/Y H:i');
            }
            $dataAggiornamentp = '-';
            if (!is_null($model->updated_at)) {
                $dataAggiornamentp = Yii::$app->formatter->asDatetime($model->updated_at, 'php:d/m/Y H:i');
            }
            $ultimoAccesso = '-';
            if ($model->ultimo_accesso) {
                $ultimoAccesso = Yii::$app->formatter->asDatetime($model->ultimo_accesso, 'php:d/m/Y H:i');
            }

            $facilitatore = '-';
            if ($model->facilitatore) {
                $facilitatore = $model->facilitatore->nomeCognome;
            }
            $prevalentPartership = '-';
            if ($model->prevalentPartnership) {
                $prevalentPartership = $model->prevalentPartnership->name;
            }


            $sheet->setCellValue('A' . $row, $model->id);
            $sheet->setCellValue('B' . $row, $model->nome);
            $sheet->setCellValue('C' . $row, $model->cognome);
            $sheet->setCellValue('D' . $row, $statusLabel);
            $sheet->setCellValue('E' . $row, $model->user->email);
            $sheet->setCellValue('F' . $row, $prevalentPartership);
            $sheet->setCellValue('G' . $row, $facilitatore);
//            $sheet->setCellValue('E' . $row, \Yii::$app->formatter->asDatetime($model->updated_at, 'php:d/m/Y H:i'));
            $sheet->setCellValue('H' . $row, \Yii::$app->formatter->asBoolean($model->notify_from_editorial_staff));
            $sheet->setCellValue('I' . $row, $dataCreazione);
            $sheet->setCellValue('J' . $row, $dataAggiornamentp);
            $sheet->setCellValue('K' . $row, $ultimoAccesso);

            $row++;
        }

        // Set columns alignment
        $sheet->getStyle('A1:H' . ($row - 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        // Set sheet title
        $sheet->setTitle('Utenti');

        // Save file
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($filename);

        Console::stdout("Users exported successfully.\n");
    }

}