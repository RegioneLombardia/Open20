<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\widgets
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\widgets;

use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\exceptions\NewsletterException;
use open20\amos\notificationmanager\models\Newsletter;
use yii\base\Widget;
use yii\web\View;

/**
 * Class SendNewsletterWidget
 * @package open20\amos\notificationmanager\widgets
 */
class SendNewsletterWidget extends Widget
{
    const BTN_SEND_TEST_NEWSLETTER = 1;
    const BTN_SEND_NEWSLETTER = 2;
    const BTN_STOP_SEND_NEWSLETTER = 3;
    const BTN_RE_SEND_NEWSLETTER = 4;
    
    /**
     * @var string $layout
     */
    public $layout = '{content}';
    
    /**
     * @var Newsletter $model
     */
    private $model;
    
    /**
     * @var int $buttonType
     */
    private $buttonType = 0;
    
    /**
     * @var bool $autoRegisterJavascript
     */
    private $autoRegisterJavascript = true;
    
    /**
     * @throws NewsletterException
     */
    public function init()
    {
        parent::init();
        
        if (is_null($this->model)) {
            throw new NewsletterException(AmosNotify::t('amosnotify', '#send_newsletter_widget_missing_model'));
        }
        
        if (!($this->model instanceof Newsletter)) {
            throw new NewsletterException(AmosNotify::t('amosnotify', '#send_newsletter_widget_model_not_newsletter'));
        }
        
        if (!$this->buttonType) {
            throw new NewsletterException(AmosNotify::t('amosnotify', '#send_newsletter_widget_missing_button_type'));
        }
    }
    
    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }
    
    /**
     * @return Newsletter
     */
    public function getModel()
    {
        return $this->model;
    }
    
    /**
     * @param Newsletter $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }
    
    /**
     * @return int
     */
    public function getButtonType()
    {
        return $this->buttonType;
    }
    
    /**
     * @param int $buttonType
     */
    public function setButtonType($buttonType)
    {
        $this->buttonType = $buttonType;
    }
    
    /**
     * @return bool
     */
    public function isAutoRegisterJavascript()
    {
        return $this->autoRegisterJavascript;
    }
    
    /**
     * @param bool $autoRegisterJavascript
     */
    public function setAutoRegisterJavascript($autoRegisterJavascript)
    {
        $this->autoRegisterJavascript = $autoRegisterJavascript;
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        $content = preg_replace_callback("/{\\w+}/", function ($matches) {
            $content = $this->renderSection($matches[0]);
            return $content === false ? $matches[0] : $content;
        }, $this->layout);
        return $content;
    }
    
    /**
     * Renders a section of the specified name.
     * If the named section is not supported, false will be returned.
     * @param string $name the section name, e.g., `{summary}`, `{items}`.
     * @return string|boolean the rendering result of the section, or false if the named section is not supported.
     * @throws \Exception
     */
    protected function renderSection($name)
    {
        switch ($name) {
            case '{content}':
                return $this->renderContent();
            default:
                return false;
        }
    }
    
    /**
     * Render the widget content.
     * @return string
     */
    protected function renderContent()
    {
        if ($this->buttonType == self::BTN_SEND_TEST_NEWSLETTER) {
            return $this->renderSendTestNewsletter();
        } else if ($this->buttonType == self::BTN_SEND_NEWSLETTER) {
            return $this->renderSendNewsletter();
        } else if ($this->buttonType == self::BTN_STOP_SEND_NEWSLETTER) {
            return $this->renderStopSendNewsletter();
        } else if ($this->buttonType == self::BTN_RE_SEND_NEWSLETTER) {
            return $this->renderReSendNewsletter();
        } else {
            return '';
        }
    }
    
    /**
     * This method creates the send test newsletter button and confirm modal.
     * @return string
     */
    protected function renderSendTestNewsletter()
    {
        $createUrlParams = [
            '/notify/newsletter/send-test-newsletter',
        ];
        
        // Message section
        $messageSection =
            Html::tag('div', '', ['class' => 'alert message-to-user-class hidden', 'role' => 'alert']) .
            Html::tag('div',
                Html::a(AmosNotify::t('amoscore', '#close'), null, [
                    'class' => 'btn btn-secondary success-modal-close-btn hidden',
                    'data-dismiss' => 'modal'
                ]),
                ['class' => 'm-15-0']
            );
        
        // Base modal content
        $textInputId = 'notify_textinput_id-' . $this->model->id;
        $baseModalContent =
            Html::hiddenInput('notify-newsletter-id', $this->model->id, ['id' => 'notify_newsletter_id-' . $this->model->id, 'class' => 'notify-newsletter-id-class']) .
            Html::tag('div',
                Html::tag('div',
                    Html::tag('div',
                        Html::label(AmosNotify::t('amosnotify', '#send_newsletter_widget_send_test_newsletter_modal_mail_input_label'), $textInputId, ['class' => 'control-label']),
                        ['class' => 'col-xs-12']
                    ) .
                    Html::tag('div',
                        Html::textInput('notify-mail', '', [
                            'id' => $textInputId,
                            'class' => 'form-control notify-textinput-class'
                        ]),
                        ['class' => 'col-xs-12']
                    ),
                    ['class' => 'row']
                ),
                ['class' => 'form-group']
            ) .
            Html::tag('div',
                Html::a(AmosNotify::t('amoscore', '#cancel'), null, ['class' => 'btn btn-secondary', 'data-dismiss' => 'modal']) .
                Html::a(AmosNotify::t('amoscore', '#confirm'), $createUrlParams, ['class' => 'btn btn-navigation-primary send-test-newsletter-modal-btn']),
                ['class' => 'pull-right m-15-0']
            );
        
        // Compose all modal content
        $content =
            $messageSection .
            Html::tag('div', $baseModalContent, ['class' => 'base-modal-content']) .
            Html::tag('div', '', ['class' => 'loading hidden m-t-30']);
        
        // Create modal
        $modalId = 'send-test-newsletter-modal-id-' . $this->model->id;
        ModalUtility::amosModal([
            'id' => $modalId,
            'headerText' => AmosNotify::t('amosnotify', '#send_test_newsletter_widget_modal_header_text'),
            'modalBodyContent' => $content,
            'containerOptions' => ['class' => 'modal-send-test-newsletter fade'],
            'disableFooter' => true,
            'headerOnlyText' => true,
        ]);
        
        // Create send test button
        $title = AmosNotify::t('amosnotify', '#send_test_newsletter_btn_text');
        $btn = Html::a(
            $title,
            $createUrlParams,
            [
                'data-toggle' => 'modal',
                'data-target' => '#' . $modalId,
                'title' => $title,
                'class' => 'btn btn-navigation-primary',
            ]
        );
        
        if ($this->autoRegisterJavascript) {
            static::registerWidgetJavascript();
        }
        
        return $btn;
    }
    
    /**
     * This method creates the send newsletter button and confirm modal.
     * @return string
     */
    protected function renderSendNewsletter()
    {
        $btnTitle = AmosNotify::txt('#send_newsletter_btn_text');
        return ModalUtility::addConfirmRejectWithModal([
            'modalId' => 'send-newsletter-modal-id-' . $this->model->id,
            'modalHeader' => AmosNotify::txt('#send_newsletter_widget_modal_header_text'),
            'modalDescriptionText' => AmosNotify::txt('#send_newsletter_modal_text'),
            'btnText' => $btnTitle,
            'btnLink' => ['/notify/newsletter/send-newsletter', 'id' => $this->model->id],
            'btnOptions' => [
                'title' => $btnTitle,
                'class' => 'btn btn-navigation-primary send-newsletter-btn-class'
            ],
            'containerOptions' => [
                'style' => 'white-space: normal;'
            ]
        ]);
    }
    
    /**
     * This method creates the stop send newsletter button and confirm modal.
     * @return string
     */
    protected function renderStopSendNewsletter()
    {
        $btnTitle = AmosNotify::txt('#stop_send_newsletter_btn_text');
        return ModalUtility::addConfirmRejectWithModal([
            'modalId' => 'stop-send-newsletter-modal-id-' . $this->model->id,
            'modalHeader' => AmosNotify::txt('#stop_send_newsletter_widget_modal_header_text'),
            'modalDescriptionText' => AmosNotify::txt('#stop_send_newsletter_modal_text'),
            'btnText' => $btnTitle,
            'btnLink' => ['/notify/newsletter/stop-send-newsletter', 'id' => $this->model->id],
            'btnOptions' => [
                'title' => $btnTitle,
                'class' => 'btn btn-navigation-primary stop-send-newsletter-btn-class'
            ]
        ]);
    }
    
    /**
     * This method creates the re send newsletter button and confirm modal.
     * @return string
     */
    protected function renderReSendNewsletter()
    {
        $btnTitle = AmosNotify::txt('#re_send_newsletter_btn_text');
        return ModalUtility::addConfirmRejectWithModal([
            'modalId' => 're-send-newsletter-modal-id-' . $this->model->id,
            'modalHeader' => AmosNotify::txt('#send_newsletter_widget_modal_header_text'),
            'modalDescriptionText' => AmosNotify::txt('#re_send_newsletter_modal_text'),
            'btnText' => $btnTitle,
            'btnLink' => ['/notify/newsletter/re-send-newsletter', 'id' => $this->model->id],
            'btnOptions' => [
                'title' => $btnTitle,
                'class' => 'btn btn-navigation-primary re-send-newsletter-btn-class'
            ]
        ]);
    }
    
    /**
     * This static method register the javascript and assets necessary to the widget.
     */
    public static function registerWidgetJavascript()
    {
        $js = <<<JS
        $('.send-test-newsletter-modal-btn').on('click', function (event) {
            event.preventDefault();
            var modal = $(this).parents('.modal-send-test-newsletter');
            var hiddenInput = modal.find('input[name="notify-newsletter-id"]');
            var newsletterId = hiddenInput.val();
            var textInput = modal.find('#notify_textinput_id-' + newsletterId);
            var notifyText = textInput.val();
            var dataArray = {
               id: newsletterId,
               testEmail: notifyText
            };
            $.ajax({
                url: "" + $(this).attr("href"),
                type: 'post',
                data: dataArray,
                dataType: 'json',
                beforeSend: function (xhr) {
                    modal.find('.loading').removeClass('hidden');
                },
                complete: function (xhr, status) {
                    modal.find('.loading').addClass('hidden');
                },
                success: function (result,status,xhr) {
                    modal.find('.message-to-user-class').html(result.message);
                    modal.find('.message-to-user-class').removeClass('hidden');
                    if (result.success === 1) {
                        textInput.val('');
                        modal.find('.message-to-user-class').addClass('alert-success');
                        modal.find('.message-to-user-class').removeClass('alert-danger');
                        modal.find('.base-modal-content').addClass('hidden');
                        modal.find('.success-modal-close-btn').removeClass('hidden');
                    } else if (result.success === 0) {
                        modal.find('.message-to-user-class').removeClass('alert-success');
                        modal.find('.message-to-user-class').addClass('alert-danger');
                    }
                },
                error: function(xhr,status,error) {
                    modal.find('.loading').addClass('hidden');
                }
            });
            return false;
        });
        
        $('.success-modal-close-btn').on('click', function (event) {
            var modal = $(this).parents('.modal-send-test-newsletter');
            modal.find('.message-to-user-class').addClass('hidden');
            modal.find('.success-modal-close-btn').addClass('hidden');
            modal.find('.base-modal-content').removeClass('hidden');
        });
JS;
        \Yii::$app->view->registerJs($js, View::POS_READY);
        
        $moduleL = \Yii::$app->getModule('layout');
        if (!is_null($moduleL)) {
            // Layout
            \open20\amos\layout\assets\SpinnerWaitAsset::register(\Yii::$app->view);
        } else {
            // Core
            \open20\amos\core\views\assets\SpinnerWaitAsset::register(\Yii::$app->view);
        }
    }
}
