<?php

namespace open20\gamification\models\base;

use open20\amos\core\record\Record;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "punteggi_gamif".
 *
 *
 * @property integer $id
 * @property integer $gamification_id
 * @property integer $content_id
 * @property string $datetime_attivita
 * @property integer $user_profile_id
 * @property string $username
 * @property string $nome
 * @property string $cognome
 * @property string $email
 * @property integer $attivita
 * @property integer $punteggio
 *
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $deleted_by
 * @property string $deleted_at
 */
class PunteggiUtenti extends Record
{
    public $isSearch = false;

    const ARRAY_CAMPI = [
        1 => ['utente_iscrizione', 'profilo'],
        2 => ['utente_foto', 'profilo'],
        3 => ['utente_colleg', 'profilo'],
        4 => ['community_creazione', 'community'],
        5 => ['community_iscrizione', 'community'],
        6 => ['notizie_creazione', 'notizie'],
        7 => ['notizie_commento', 'notizie'],
        8 => ['notizie_like', 'notizie'],
        9 => ['documenti_creazione', 'documenti'],
        10 => ['discussioni_creazione', 'discussioni'],
        11 => ['discussioni_commento', 'discussioni'],
        12 => ['discussioni_like', 'discussioni' ],
        13 => ['sondaggi_partec', 'sondaggi']
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'punteggi_gamif';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = ArrayHelper::merge(
            parent::rules(),
            [
                [['username', 'nome', 'cognome', 'email'], 'string', 'max' => 255],
                [['username', 'nome', 'cognome', 'email', 'attivita', 'punteggio'], 'required'],
                [['gamification_id', 'content_id', 'user_profile_id', 'punteggio', 'attivita', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
                [['gamification_id', 'content_id', 'user_profile_id', 'datetime_attivita', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            ]
        );

        return $rules;
    }

    public function afterFind()
    {
        return parent::afterFind();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('punteggi_gamif', 'ID'),
            'gamification_id' => Yii::t('punteggi_gamif', 'ID Gamification'),
            'user_profile_id' => Yii::t('punteggi_gamif', 'ID Profilo Utente'),
            'datetime_attivita' => Yii::t('punteggi_gamif', 'Data attività'),
            'content_id' => Yii::t('punteggi_gamif', 'ID Contenuto'),
            'username' => Yii::t('punteggi_gamif','Username'),
            'nome' => Yii::t('punteggi_gamif','Nome utente'),
            'cognome' => Yii::t('punteggi_gamif','Cognome utente'),
            'email' => Yii::t('punteggi_gamif','Email utente'),
            'attivita' => Yii::t('punteggi_gamif','Tipo attività'),
            'punteggio' => Yii::t('punteggi_gamif',"Punteggio assegnato per l'attività"),

            'created_by' => Yii::t('punteggi_gamif', 'Creato da'),
            'created_at' => Yii::t('punteggi_gamif', 'Creato il'),
            'updated_by' => Yii::t('punteggi_gamif', 'Aggiornato da'),
            'updated_at' => Yii::t('punteggi_gamif', 'Aggiornato il'),
            'deleted_by' => Yii::t('punteggi_gamif', 'Eliminato da'),
            'deleted_at' => Yii::t('punteggi_gamif', 'Aggiornato il'),
        ];
    }

    /**
     *
     * @param array $dataDownload
     */
    public static function generateExcellFromFile($dataDownload)
    {
        $nameFile = 'Report_utente.xls';
        //array per il file
        $xlsData = [];
        $xlsData[] = ['Data attivita', 'Username', 'Nome', 'Cognome', 'Email', 'Attivita', 'Punteggio'];

        foreach ($dataDownload as $singleData) {
            $attivita = self::ARRAY_CAMPI[$singleData['attivita']][0];
            $xlsData[] = [
                $singleData['datetime_attivita'],
                $singleData['username'],
                $singleData['nome'],
                $singleData['cognome'],
                $singleData['email'],
                $attivita,
                $singleData['punteggio']
            ];
        }
        //inizializza l'oggetto excel
        $objPHPExcel = new \PHPExcel();

        //li pone nella tab attuale del file xls
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

        $objPHPExcel->getActiveSheet()->fromArray($xlsData, NULL, 'A1');
        $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray(
            [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,],
                'borders' => [
                    'top' => [
                        'style' => \PHPExcel_Style_Border::BORDER_DOUBLE
                    ]
                ],
                'fill' => [
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => [
                        'rgb' => 'cccccc'
                    ],
                    'endcolor' => [
                        'rgb' => 'cccccc'
                    ]
                ]
            ]
        );

        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $nameFile . '"');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}
