<?php

namespace open20\onlyoffice\models;

use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\attachments\models\File;
use Yii;
use yii\helpers\ArrayHelper;
use open20\amos\admin\models\UserProfile;
use yii\base\InvalidArgumentException;
use yii\base\InvalidValueException;

/**
 * This is the model class for table "onlyoffice_files".
 *
 * @property File $attach
 * @property UserProfile $autore
 * @property UserProfile $creatoreFile
 */
class OnlyofficeFiles extends \open20\onlyoffice\models\base\OnlyofficeFiles
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPLOAD = 'upload';

    /**
     * Nome da assegnare al file
     * @var string
     */
    public $nome_file;
    /**
     * Tipo di file (equivale all'estensione senza il '.')
     * @var string
     */
    public $tipo_file;


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'fileBehavior' => [
                    'class' => FileBehavior::className()
                ],
            ]
        );
    }

    public function representingColumn()
    {
        return [
//inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules()
    {
        $modulo = Yii::$app->getModule('onlyoffice');
        if (empty($modulo))
        {
            throw new InvalidConfigException('Modulo di OnlyOffice non'
                . ' presente');
        }
        /** @var \open20\onlyoffice\Module $modulo */

        return ArrayHelper::merge(parent::rules(), [
            ['file', 'file', 'extensions' => $modulo->getAllExtension()],
            [['nome_file', 'tipo_file'], 'string'],
            [['tipo_file'], 'required', 'on' => [self::SCENARIO_CREATE,
                self::SCENARIO_UPLOAD]],
            [['file'], 'required', 'on' => [self::SCENARIO_UPLOAD]],
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'file' => 'File',
            'nome_file' => 'Nome del file (senza estensione)',
            'tipo_file' => 'Tipo',
        ]);
    }

    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'attach_file_id',
                'label' => $labels['attach_file_id'],
                'type' => 'integer'
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();

        //scenario di creazione di un nuovo file
        $scenarios[self::SCENARIO_CREATE] = ['nome_file', 'tipo_file'];
        
        //scenario di caricamento 'manuale' di un nuovo file
        $scenarios[self::SCENARIO_UPLOAD] = ['nome_file', /*'tipo_file',*/
            'file'];

        return $scenarios;
    }

    /**
     * @return string marker path
     */
    public function getIconMarker()
    {
        return null; //TODO
    }

    /**
     * If events are more than one, set 'array' => true in the calendarView in the index.
     * @return array events
     */
    public function getEvents()
    {
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent()
    {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event 
     */
    public function getColorEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttach()
    {
        return $this->hasOne(File::class, ['id' => 'attach_file_id']);
    }

    /**
     * Recupera il model per la gestione dell'allegato
     * @return \open20\amos\attachments\models\File|boolean Restituisce
     * il model per la gestione dell'allegato o false nel caso non sia presente
     */
    public function getModelFile()
    {
        if (!empty($this->file))
        {
            return $this->file;
        }
        if (empty($this->attach_file_id))
        {
            return false;
        }
        return $this->attach;
    }

    /**
     * Recupera i dati inerenti ad OnlyOffice a partire dai dati del file ad
     * esso associato (sia in maniera diretta, che indiretta)
     * @param File $model Model dei dati del file di cui recuperare i dati di
     * OnlyOffice
     * @return OnlyofficeFiles|boolean Restituisce i dati di OnlyOffice o false
     * se non e' possibile recuperarli
     */
    public static function searchByModelFile($model)
    {
        if (empty($model))
        {
            return false;
        }
        if (!($model instanceof File))
        {
            return false;
        }
        if (empty($model->id))
        {
            return false;
        }
        $modelO = self::find()
            ->andWhere(['attach_file_id' => $model->id])
            ->one();
        return $modelO;
    }

    /**
     * Associa un file gia' precedentemente presente nell'applicativo ai dati di
     * OnlyOffice
     * @param File $model Model del file da associare
     * @param boolean $checkIfExists Se true verra' verificato se l'associazione
     * esiste gia' (e nel caso viene restituito il model trovato), mentre se
     * false non verra' eseguito questo tipo di controllo
     * @return OnlyofficeFiles|boolean Restituisce i dati di OnlyOffice
     * associati al file o false nel caso non sia possibile o si verifica un
     * errore
     */
    public static function associa($model, $checkIfExists = false)
    {
        if (empty($model))
        {
            return false;
        }
        if (!($model instanceof File))
        {
            return false;
        }
        if (empty($model->id))
        {
            return false;
        }
        if (!empty($checkIfExists))
        {
            $modelO = $this->searchByModelFile($model);
            if (!empty($modelO))
            {
                /**
                 * non eseguo l'associazione in quanto esiste gia' e considero
                 * questo come un esito positivo dell'operazione
                 */
                return $modelO;
            }
        }
        $modelO = new self();
        $modelO->attach_file_id = $model->id;
        $modelO->link_diretto = 0;
        $modelO->creato_il = $modelO->dataCaricamentoDaFile($model);
        $modelPropietario = $modelO->queryProprietarioDaFile($model)
            ->select(UserProfile::tableName() . '.user_id')
            ->asArray()
            ->one();
        $modelO->creato_da = ((!empty($modelPropietario))
            ? $modelPropietario['user_id'] : null);
        if (!$modelO->save())
        {
            return false;
        }
        return $modelO;
    }

    /**
     * Recupare le informazioni sul proprietario dai dati di un file (model
     * File) passati al metodo
     * @return \yii\db\ActiveQuery Restituisce la query per recuperare i dati
     * (UserProfile) del proprietario
     */
    protected function queryProprietarioDaFile($model)
    {
        if (empty($model))
        {
            return UserProfile::find()
                ->andWhere('1 = 0');
        }
        if (!($model instanceof File))
        {
            return UserProfile::find()
                ->andWhere('1 = 0');
        }
        if (empty($model->id))
        {
            return UserProfile::find()
                ->andWhere('1 = 0');
        }
        if (!class_exists($model->model))
        {
            return UserProfile::find()
                ->andWhere('1 = 0');
        }
        $modelOwnerAttach = $model->owner;
        if (empty($modelOwnerAttach))
        {
            return UserProfile::find()
                ->andWhere('1 = 0');
        }
        if (!$modelOwnerAttach->hasProperty('created_by'))
        {
            return UserProfile::find()
                ->andWhere('1 = 0');
        }
//        return UserProfile::find()
//            ->andWhere(['user_id' => $modelOwnerAttach->created_by]);
        return $modelOwnerAttach->hasOne(UserProfile::class,
                ['user_id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutore()
    {
        if (!empty($this->link_diretto))
        {
            return $this->hasOne(UserProfile::class,
                ['user_id' => 'created_by']);
        }
        if (!empty($this->creato_da))
        {
            return $this->getCreatoreFile();
        }
        /**
         * recupero il model dell'attach, da cui recuperero' il proprietario e
         * da questo il creatore (se possibile)
         */
        $modelAttach = $this->attach;
        if (empty($modelAttach))
        {
            return UserProfile::find()
                ->andWhere('1 = 0');
        }
        return $this->queryProprietarioDaFile($modelAttach);
    }

    /**
     * Recupare la data di caricamento dai dati di un file (model File) passati
     * al metodo
     * @param File $model Dati da cui recuperare la data di caricamento
     * @return string|null Restituisce la data di caricamento estrapolata dai
     * dati (in formato database) o null se non presente o se il recupero non e'
     * stato possibile
     */
    protected function dataCaricamentoDaFile($model)
    {
        if (empty($model))
        {
            return null;
        }
        if (!($model instanceof File))
        {
            return null;
        }
        if (empty($model->id))
        {
            return null;
        }
        if (empty($model->date_upload))
        {
            return null;
        }
        if (preg_match('/^[0-9]+$/', $model->date_upload) === 1)
        {
            return date('Y-m-d H:i:s', $model->date_upload);
        }
        return $model->date_upload;
    }
    
    /**
     * Recupera la data di creazione del documento
     * @return string|null Restituisce la data di creazine del docuemnto (in
     * formato database) o null se non presente 
     */
    public function getDataCreazione()
    {
        if (!empty($this->link_diretto))
        {
            return $this->created_at;
        }
        if (!empty($this->creato_il))
        {
            return $this->creato_il;
        }
        /**
         * recupero il model dell'attach, da cui recuperero' la data di
         * caricamento
         */
        $modelAttach = $this->attach;
        if (empty($modelAttach))
        {
            return null;
        }
        return $this->dataCaricamentoDaFile($modelAttach);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatoreFile()
    {
        return $this->hasOne(UserProfile::class,
            ['user_id' => 'creato_da']);
    }
    
    /**
     * Calcola la chiave univoca da passare all'editor di OnlyOffice
     * @return string Chiave univoca
     * @throws InvalidValueException Se i dati di OnlyOffice non sono ancora
     * salvati nel database o se ai dati non e' collegato un file valido
     */
    public function getKeyFile()
    {
        if ((empty($this->moduleObj))
            || (!($this->moduleObj instanceof \open20\onlyoffice\Module)))
        {
            throw new InvalidValueException('Modulo del modello non corretto');
        }
        if (empty($this->moduleObj->keyFilePrefixName))
        {
            throw new InvalidValueException('Modulo non configurato'
                . ' correttamente');
        }
        if (empty($this->id))
        {
//            return null;
            throw new InvalidValueException('I dati di OnlyOffice non sono'
                . ' salvati nel database');
        }
        if (empty($this->attach))
        {
            throw new InvalidValueException('File non collegato ai dati di'
                . ' OnlyOffice');
        }
        return $this->moduleObj->keyFilePrefixName . '_' . $this->id . '_'
            . $this->attach_file_id;
    }

    /**
     * Data una chiave (generata col metodo 'getKeyFile') ne estrae l'id del
     * record dei dati di OnlyOffice a cui si riferisce
     * @param string $key Chiave univoca da cui estrarre l'id
     * @return integer Restitusice l'id del record
     * @throws InvalidArgumentException Se la chiave non e' presente o e' mal
     * formata
     */
    public static function extractIdFromKey($key)
    {
        if (empty($key))
        {
            throw new InvalidArgumentException('Chiave non presente');
        }
        if (strpos($key, '_') === false)
        {
            throw new InvalidArgumentException('Chiave non valida');
        }
        list($prefix, $id, $attFileId) = explode('_', $key);
        if ((empty($prefix)) || (empty($id)) || (empty($attFileId)))
        {
            throw new InvalidArgumentException('Chiave mal formata');
        }
        return intval($id);
    }
}