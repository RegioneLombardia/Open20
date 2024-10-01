<?php

namespace app\modules\uikit\blocks;

use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;
use app\modules\uikit\BaseUikitFormBlock;
use app\modules\uikit\Module;
use PHPExcel;
use PHPExcel_IOFactory;
use Yii;
use luya\helpers\Inflector;
use luya\helpers\Url;
use trk\uikit\Uikit;
use yii\base\ErrorException;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class ExportExcelBlock extends BaseUikitFormBlock
{
    public $cacheEnabled = false;

    /**
     * @inheritdoc
     */
    protected $component = "exportexcel";

    /**
     *
     * @return string
     */
    public function name()
    {
        return Module::t('Export Excel');
    }

    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return LegacyGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'file_download';
    }

    public function admin(array $params = array())
    {
        if (count($this->getVarValue('items', []))) {
            return $this->frontend();
        } else {
            return '<div><span class="block__empty-text">'.Module::t('no_content').'</span></div>';
        }
    }

    public function frontend(array $params = array())
    {
        $configs = $this->getValues();
        $data    = Uikit::configs($configs);
        $post    = $this->request->post();

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
    private function export($configs, $data)
    {
        $mime      = 'application/vnd.ms-excel';
        $extension = 'xlsx';

        $tableSchema = \Yii::$app->db->schema->getTableSchema($data['table']);
        if(!empty($tableSchema)){
            $query = new Query();
            $query->from($data['table']);
            $key   = uniqid('ngre', true);

            $store = $query->all();
            if ($store && count($store)) {
                $head = [];
                foreach ($store[0] as $column => $value) {
                    $head[0][$column] = $column;
                }
                $store = ArrayHelper::merge($head, $store);
            }
        } else {
            \Yii::$app->session->addFlash('warning', Module::t('Nessun dato presente.'));
            return Url::toRoute(\yii\helpers\Url::current());
        }
        //inizializza l'oggetto excel
        $objPHPExcel = new PHPExcel();
        //li pone nella tab attuale del file xls
        $objPHPExcel->getActiveSheet()->fromArray($store, NULL, 'A1');
        $objWriter   = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(\Yii::getAlias('@runtime').'/'.$key.'.tmp');
        $objPHPExcel->disconnectWorksheets();
        unset($objWriter, $objPHPExcel);
        $route       = "api/1/export-download";

        if ($store) {
            Yii::$app->session->set('tempNgRestFileName',
                Inflector::slug($data['table']).'-export-'.date("Y-m-d-H-i").'.'.$extension);
            Yii::$app->session->set('tempNgRestFileMime', $mime);
            Yii::$app->session->set('tempNgRestFileKey', $key);
            return Url::toRoute(['/'.$route, 'key' => base64_encode($key)]);
        }
    }
}