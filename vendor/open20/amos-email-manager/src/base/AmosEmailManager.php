<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

namespace open20\amos\emailmanager\base;

use open20\amos\emailmanager\interfaces\ManagerInterface;
use open20\amos\emailmanager\interfaces\TransportInterface;
use open20\amos\emailmanager\models\EmailSpool;
use open20\amos\emailmanager\models\EmailTemplate;
use open20\amos\emailmanager\models\File;
use open20\amos\emailmanager\transports\YiiMailer;
use Mustache_Engine;
use Yii;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\log\Logger;


class AmosEmailManager implements ManagerInterface
{

    const PENDING = 'pending';
    const PROCESSING = 'processing';
    const ERROR = 'error';
    const EMAILED = 'emailed';


    /**
     * @var string Template type, can be "db" or "php".
     */
    public $templateType = "php";

    public $templatePath = "/emails/";

    /**
     * @var string The default layout to use for template emails.
     */
    public $defaultTemplate = "layout_default";
    public $defaultLayout = "layout_fancy";

    /**
     * @var array List of template parts that will be rendered.
     */
    public $templateFields = array('subject', 'heading', 'message');

    /**
     * @var string The default transport to use.
     */
    public $defaultTransport = 'mail';

    /** @var TransportInterface[] */
    public $transports = [];

    private $engine;


    public function __construct()
    {

        $this->transports[$this->defaultTransport] = new YiiMailer();
        $options = [
            'charset' => \Yii::$app->charset,
            'entity_flags' => ENT_QUOTES | ENT_SUBSTITUTE,
            'strict_callables' => true
        ];

        $this->engine = new Mustache_Engine($options);
    }

    /**
     *
     * @return type
     */
    public function getDefaultLayout()
    {
        return $this->defaultLayout;
    }

    /**
     *
     * @return type
     */
    public function getDefaultTemplate()
    {
        return $this->defaultTemplate;
    }

    /**
     *
     * @return type
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     *
     * @return type
     */
    public function getTemplateType()
    {
        return $this->templateType;
    }

    /**
     *
     * @param type $layout
     */
    public function setDefaultLayout($layout)
    {
        $this->defaultLayout = $layout;
    }

    /**
     *
     * @param type $tamplate
     */
    public function setDefaultTemplate($tamplate)
    {
        $this->defaultTemplate = $tamplate;
    }

    /**
     *
     * @param type $path
     */
    public function setTemplatePath($path)
    {
        $this->templatePath = $path;
    }

    /**
     *
     * @param type $templateType
     */
    public function setTemplateType($templateType)
    {
        $this->templateType = $templateType;
    }


    /**
     *
     * @param type $template
     * @param type $viewParams
     * @param type $layout
     * @return type
     */
    public function buildTemplateMessage($template, $viewParams = array(), $layout = null)
    {
        if ($layout === null) {
            $layout = $this->defaultTemplate;
        }
        $method = 'buildTemplateMessage_' . $this->templateType;
        if (!method_exists($this, $method)) {
            $this->templateType = 'php';
        }


        return call_user_func_array(array($this, $method), array($template, $viewParams, $layout));
    }

    /**
     * @param $template string
     * @param $viewParams array
     * @param string $layout
     * @return array
     */
    private function buildTemplateMessage_php($template, $viewParams = array(), $layout = null)
    {
        $message = array();
        try {
            $controller = Yii::$app->controller;
            foreach ($this->templateFields as $field) {
                $viewParams['contents'] = $controller->renderPartial($this->templatePath . DIRECTORY_SEPARATOR . $template . DIRECTORY_SEPARATOR . $field, $viewParams);
                if (!$layout) {
                    $viewParams[$field] = $message[$field] = $viewParams['contents'];
                } else {
                    $viewParams[$field] = $message[$field] = $controller->renderPartial($this->templatePath . DIRECTORY_SEPARATOR . $layout . DIRECTORY_SEPARATOR . $field, $viewParams);
                }
                unset($viewParams['contents']);
            }
        } catch (Exception $bex) {
            Yii::getLogger()->log($bex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $message;
    }

    /**
     *
     * @param type $template
     * @param type $viewParams
     * @param type $layout
     * @return type
     * @throws Exception
     */
    private function buildTemplateMessage_db($template, $viewParams = array(), $layout = null)
    {
        $message = array();

        try {
            // load template
            $emailTemplate = EmailTemplate::find()->andWhere(array('name' => $template))->one();
            if (!$emailTemplate) {
                throw new Exception('missing EmailTemplate - ' . $template);
            }
            // load layout
            $emailLayout = $layout ? EmailTemplate::find()->andWhere(array('name' => $layout))->one() : false;
            if ($layout && !$emailLayout) {
                throw new Exception('missing EmailTemplate - ' . $layout);
            }
            // parse template

            if ($this->engine) {
                foreach ($this->templateFields as $field) {
                    $viewParams['contents'] = $this->engine->render($emailTemplate->$field, $viewParams);
                    if (!$layout) {
                        $viewParams[$field] = $message[$field] = $viewParams['contents'];
                    } else {
                        $viewParams[$field] = $message[$field] = $this->engine->render($emailLayout->$field, $viewParams);
                    }
                    unset($viewParams['contents']);
                }
            }
        } catch (Exception $bex) {
            Yii::getLogger()->log($bex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $message;
    }


    /**
     * Sends email message queue using default transport
     *
     * @param string $from format accepted:
     *
     *   1) 'example@example.com'
     *   2) 'example@example.com alias' the method considers the email address up to the first space, everything that follows is considered alias.
     *
     * @param string $to
     * @param string $subject
     * @param string $text
     * @param integer $priority
     * @param array $files
     * @param array $bcc
     * @return bool
     */
    public function queue($from, $to, $subject, $text, array $files = [], array $bcc = [], $params = [], $priority = 0)
    {
        $retValue = false;

        try {
            $retValue = $this->saveQueue($from, $to, $subject, $text, $files, $bcc, $params, $priority, self::PENDING, $this->defaultTemplate);
        } catch (Exception $bex) {
            Yii::getLogger()->log($bex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $retValue;
    }

    /**
     * Sends email message immediately using default transport
     *
     * @param string $from format accepted:
     *
     *   1) 'example@example.com'
     *   2) 'example@example.com alias' the method considers the email address up to the first space, everything that follows is considered alias.
     *
     * @param string|array $to
     * @param string $subject
     * @param string $text
     * @param array $files
     * @param array|string $bcc
     * @param array|string $cc
     * @param array|string $replyTo
     * @return bool
     */
    public function send($from, $to, $subject, $text, array $files = [], array $bcc = [], $params = [], $save_in_queue = true, $cc = [], $replyTo = [])
    {
        $retValue = false;
        try {

            $files = $files === null ? [] : $files;
            $viewParams = ArrayHelper::merge(array(
                'subject' => $subject,
                'heading' => '',
                'message' => $text,
            ), $params);
            $message = $this->buildTemplateMessage($this->defaultTemplate, $viewParams, $this->defaultLayout);
            
            $retValue = $this->transports[$this->defaultTransport]->send($from, $to, $message['subject'], $message['message'], $this->loadFiles($files), $bcc,  $cc, $replyTo);

            if ($save_in_queue) {
                $this->saveQueue($from, $to, $subject, $text, $files, $bcc, $params, 0, ($retValue ? self::EMAILED : self::ERROR), $this->defaultTemplate);
            }
        } catch (Exception $bex) {
            Yii::getLogger()->log($bex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $retValue;
    }


    /**
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $text
     * @param array $files
     * @param array $bcc
     * @param array $params
     * @param integer $priority
     *
     */
    private function saveQueue($from, $to, $subject, $text, array $files = [], array $bcc = [], $params = [], $priority = 0, $status = self::PENDING, $template)
    {
        $retValue = false;

        try {
            $model = new EmailSpool();
            $model->from_address = $from;
            $model->to_address = $to;
            $model->subject = $subject;
            $model->message = $text;
            $model->priority = $priority;
            $model->transport = '';
            $model->template = $template;
            $model->status = $status;
            $model->model_name = '';
            $model->files = $this->loadFiles($files);
            $model->bcc = (empty($bcc) ? null : $bcc);
            $model->viewParams = $params;
            if ($status == self::EMAILED) {
                $model->sent = time();
            }

            $retValue = $model->save(false);
        } catch (\Exception $bex) {
            Yii::getLogger()->log($bex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $retValue;
    }

    /**
     *
     * @param int $limit
     * @return int
     */
    public function spool($limit = 1000)
    {
        $done = 0;
        try {
            // find all the spooled emails
            $emailSpools = EmailSpool::find()->where(array(
                'status' => self::PENDING))->orderBy(['priority' => SORT_DESC, 'created_at' => SORT_ASC])->limit($limit)->all();
            foreach ($emailSpools as $emailSpool) {

                // update status to processing
                $emailSpool->status = self::PROCESSING;
                $emailSpool->save(false);

                $viewParams = ArrayHelper::merge(array(
                    'subject' => $emailSpool->subject,
                    'heading' => '',
                    'message' => $emailSpool->message,
                ), $emailSpool->viewParams);
                $message = $this->buildTemplateMessage($emailSpool->template, $viewParams, $this->defaultLayout);
                // send the email
                $sent = $this->transports[$this->defaultTransport]->send($emailSpool->from_address, $emailSpool->to_address,
                    $message['subject'], $message['message'], $emailSpool->files, $emailSpool->bcc);

                // update status and save
                $emailSpool->status = $sent ? self::EMAILED : self::ERROR;
                $emailSpool->sent = time();
                $emailSpool->save(false);
                $done++;
            }
        } catch (Exception $bex) {
            Yii::getLogger()->log($bex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $done;
    }

    /**
     *
     * @param array $files
     * @return \open20\amos\emailmanager\base\File
     */
    private function loadFiles(array $files)
    {
        $loadedFiles = [];
        try {
            foreach ($files as $key => $filepath) {
                $file = new File();
                $file->content = file_get_contents($filepath);
                $file->name = preg_match('/[^\\\\]*\.(\w+)$/i', $key) ? $key : pathinfo($filepath, PATHINFO_FILENAME) . "." . pathinfo($filepath, PATHINFO_EXTENSION);
                $file->type = FileHelper::getMimeType($filepath); //pathinfo($filepath, PATHINFO_EXTENSION); 
                $loadedFiles[] = $file;
            }
        } catch (Exception $exc) {
            Yii::getLogger()->log($exc->getMessage(), Logger::LEVEL_ERROR);
        }
        return $loadedFiles;
    }
}
