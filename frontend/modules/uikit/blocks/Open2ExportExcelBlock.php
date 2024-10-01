<?php

namespace app\modules\uikit\blocks;

use app\modules\uikit\BaseUikitFormBlock;
use app\modules\uikit\Module;
use app\modules\backendobjects\frontend\blockgroups\ElementiAvanzatiGroup;
use luya\helpers\Inflector;
use luya\helpers\Url;
use PHPExcel;
use PHPExcel_IOFactory;
use trk\uikit\Uikit;
use Yii;
use yii\base\ErrorException;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use app\modules\uikit\BaseUikitBlock;

class Open2ExportExcelBlock extends BaseUikitFormBlock {

    public $cacheEnabled = false;

    /**
     * @inheritdoc
     */
    protected $component = "open2exportexcel";

    /**
     *
     * @return string
     */
    public function name() {
        return Yii::t('backendobjects', 'block_module_backend_export_excel');
    }

    public function disable() {
        return 1;
    }

    /**
     * @inheritdoc
     */
    public function blockGroup() {
        return ElementiAvanzatiGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function icon() {
        return 'file_download';
    }

    public function admin(array $params = array()) {
        if (count($this->getVarValue('items', []))) {
            return $this->frontend();
        } else {
            return '<div><span class="block__empty-text">' . Module::t('no_content') . '</span></div>';
        }
    }

    public function frontend(array $params = array()) {
        $configs = $this->getValues();
        $data = Uikit::configs($configs);
        $post = $this->request->post();

        $render = isset($data['for_logged']) && $data['for_logged'] ? !\Yii::$app->user->isGuest : true;
        if ($render) {
            if ($this->request->isPost) {
                if (isset($post[self::FORM_ID_FILED_NAME]) && $data['id'] == $post[self::FORM_ID_FILED_NAME]) {
                    Yii::$app->response->redirect($this->export($configs, $data));
                    return Yii::$app->end();
                } else {
                    return parent::frontend();
                }
            } else {
                return parent::frontend();
            }
        }
        return '';
    }

    /**
     *
     * @return type
     * @throws ErrorException
     */
    private function export($configs, $data) {
        $mime = 'application/vnd.ms-excel';
        $extension = 'xlsx';

        $head = [];
        $dati = [];

        $query = new Query();
        $query->from($data['table']);
        $store = $query->all();
        $key = uniqid('ngre', true);
        $columns = Yii::$app->db->schema->getTableSchema($data['table'])->getColumnNames();

        foreach ($columns as $col) {
            if ($col != 'recaptcha') {
                $head[0][] = $col;
            }
        }

        if ($store && count($store)) {
            foreach ($store as $sr) {
                //controllo se è presente la chiave recaptcha dentro l'array
                if (array_key_exists('recaptcha', $sr)) {
                    unset($sr['recaptcha']);
                }
                $dati[] = $sr;
            }
        }

        $store = ArrayHelper::merge($head, $dati);

        //inizializza l'oggetto excel
        $objPHPExcel = new PHPExcel();
        //li pone nella tab attuale del file xls
        $objPHPExcel->getActiveSheet()->fromArray($store, NULL, 'A1');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(\Yii::getAlias('@runtime') . '/' . $key . '.tmp');
        $objPHPExcel->disconnectWorksheets();
        unset($objWriter, $objPHPExcel);
        $route = "api/1/export-download";
        Yii::$app->session->set('tempNgRestFileName',
                Inflector::slug($data['table']) . '-export-' . date("Y-m-d-H-i") . '.' . $extension);
        Yii::$app->session->set('tempNgRestFileMime', $mime);
        Yii::$app->session->set('tempNgRestFileKey', $key);
        return Url::toRoute(['/' . $route, 'key' => base64_encode($key)]);
    }

    public function config() {
        $configs = [
            'vars' => [
                [
                    'var' => 'visibility',
                    'label' => 'Visibilità del blocco',
                    'description' => 'Imposta la visibilità della sezione.',
                    'initvalue' => '',
                    'type' => 'zaa-select', 'options' => [
                        ['value' => '', 'label' => 'Visibile a tutti'],
                        ['value' => 'guest', 'label' => 'Visibile solo ai non loggati'],
                        ['value' => 'logged', 'label' => 'Visibile solo ai loggati'],
                    ],
                ],
                [
                    'var' => 'addclass',
                    'label' => 'Visibilità per profilo',
                    'description' => 'Imposta la visibilità della sezione in base al profilo dell\'utente loggato',
                    'type' => 'zaa-multiple-inputs',
                    'options' => [
                        [
                            'var' => 'class',
                            'type' => 'zaa-select',
                            'initvalue' => '',
                            'options' => BaseUikitBlock::getClasses(),
                        ]
                    ],
                ],
            ],
        ];

        return ArrayHelper::merge(parent::config(), $configs);
    }

}
