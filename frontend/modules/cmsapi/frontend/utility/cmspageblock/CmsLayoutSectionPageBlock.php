<?php

namespace app\modules\cmsapi\frontend\utility\cmspageblock;

use app\modules\cmsapi\frontend\models\PostCmsCreatePage;
use app\modules\cmsapi\frontend\Module;
use app\modules\cmsapi\frontend\utility\CmsBlocksBuilder;
use Yii;
use yii\helpers\BaseFileHelper;
use yii\helpers\Json;

class CmsLayoutSectionPageBlock extends CmsPageBlock
{

    public function buildValues(PostCmsCreatePage $postCmsPage)
    {
        $values = Json::decode($this->json_config_values);
        if (is_null($values)) {
            $values = [];
        }
        if (!empty($postCmsPage->event_data->url_image)) {
            $image = $this->createCmsImage($postCmsPage);
            if ($this->evaluateCss($values)) {
                $values['image'] = $image->id;
            }
        }
        $this->json_config_values = Json::encode($values);
    }

    /**
     *
     * @param array $values
     * @return boolean
     */
    private function evaluateCss(array $values)
    {
        $ret    = false;
        $module = Module::getInstance();
        if (!is_null($module)) {
            $search = $module->getCss_layoutsection_with_image();
            if (isset($values['class'])) {
                if (strpos($values['class'], $search) !== false) {
                    $ret = true;
                }
            }
        }
        return $ret;
    }

    /**
     *
     * @param PostCmsCreatePage $postCmsPage
     * @return type
     */
    protected function createCmsImage(PostCmsCreatePage $postCmsPage)
    {
        $storage                   = Yii::$app->storage;
        $storage->secureFileUpload = false;
        $tmpPath                   = Yii::getAlias($storage->getModuleAttachments()->tempPath);
        $fileName                  = $this->generateFileName($postCmsPage->event_data->url_image);
        $outPutFilePath            = $tmpPath.DIRECTORY_SEPARATOR.$fileName;
        $this->save_image($postCmsPage->event_data->url_image, $outPutFilePath);
        $file                      = $storage->addFile($outPutFilePath,
            $fileName);
        $image                     = $storage->addImage($file->id);
        if (!is_null($image)) {
            unlink($outPutFilePath);
        }
        return $image;
    }

    /**
     *
     * @param PostCmsCreatePage $postCmsPage
     * @return type
     */
    protected function generateFileName(string $urlFile)
    {
        $file = getimagesize($urlFile);
        $mime = $file['mime'];
        $exts = BaseFileHelper::getExtensionsByMimeType($mime);
        if ($exts) {
            if (in_array("jpg", $exts)) {
                $ext = 'jpg';
            } else {
                if (in_array("png", $exts)) {
                    $ext = 'png';
                } else {
                    $ext = $exts[0];
                }
            }
        }
        return Yii::$app->security->generateRandomString().".{$ext}";
    }

    /**
     *
     * @param type $inPath
     * @param type $outPath
     */
    protected function save_image($inPath, $outPath)
    { //Download images from remote server
        $in    = fopen($inPath, "rb");
        $out   = fopen($outPath, "wb");
        while ($chunk = fread($in, 8192)) {
            fwrite($out, $chunk, 8192);
        }
        fclose($in);
        fclose($out);
    }

    /**
     *
     * @param integer $nav_item_page_id
     * @return 
     */
    public static function findBlocks($nav_item_page_id)
    {
        $id_block = static::findBlock(CmsBlocksBuilder::LAYOUTSECTION);
        $blocks   = static::find()->
            andWhere(['nav_item_page_id' => $nav_item_page_id])->
            andWhere(['block_id' => $id_block->id])
            ->all();
        return $blocks;
    }
}