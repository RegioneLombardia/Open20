<?php

namespace app\modules\uikit\blocks;

use app\modules\uikit\BaseUikitFormBlock;
use app\modules\uikit\Module;
use luya\cms\frontend\blockgroups\MainGroup;
use luya\helpers\Inflector;
use luya\helpers\Url;
use PHPExcel;
use PHPExcel_IOFactory;
use trk\uikit\Uikit;
use Yii;
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
        return MainGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'file_download';
    }

    public function admin()
    {
        if (count($this->getVarValue('items', []))) {
            return $this->frontend();
        } else {
            return '<div><span class="block__empty-text">'.Module::t('no_content').'</span></div>';
        }
    }

    public function frontend()
    {
        if ($this->request->isPost) {
            $configs = $this->getValues();
            $data    = Uikit::configs($configs);
            $post    = $this->request->post();

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

    /**
     *
     * @return type
     * @throws ErrorException
     */
    private function export($configs, $data)
    {
        $type      = "xlsx";
        $mime      = 'application/vnd.ms-excel';
        $extension = 'xlsx';

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
        //inizializza l'oggetto excel
        $objPHPExcel = new PHPExcel();
        //li pone nella tab attuale del file xls
        $objPHPExcel->getActiveSheet()->fromArray($store, NULL, 'A1');
        $objWriter   = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
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