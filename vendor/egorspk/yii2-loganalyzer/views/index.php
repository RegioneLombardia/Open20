<?php
namespace spk\yii2loganalyzer;
/* @var $model LogAnalyzerWidget */
/* @var $log array */
?>

<div class="loganalyzer">
    <div class="row-fluid log-actions-bar">
        <a href="<?php echo $model->getClearUrl(); ?>" class="clear"><span
                class="label">Очистить лог</span></a>

        <span class="sep"></span>

        Фильтр:
        <a href="#" class="filter-log" rel='all'><span
                class="label label-inverse">Все</span></a>
        <a href="#" class="filter-log" rel='error'><span class="label label-important">[error]</span></a>
        <a href="#" class="filter-log" rel='warning'><span class="label label-warning">[warning]</span></a>
        <a href="#" class="filter-log" rel='info'><span class="label label-info">[info]</span></a>

        <span class="sep"></span>

        Stack Trace:
        <a href="#" id="stack-showall"><span
                class="label">Показать все</span></a>
        <a href="#" id="stack-collapseall"><span
                class="label">Скрыть все</span></a>
        <hr>
    </div>


    <div class="row-fluid log-list" style="word-wrap: break-word;">
        <?php
        $flag = false;
        foreach ($log as $logLine):
            if ($model->filterLog($logLine)):
                $status = $model->showStatus($logLine);
                ?>
                <div class="line <?php echo $status['status'] ?>-line">
                    <span class="label label-info"><?php echo $model->showDate($logLine); ?></span>
                    <span class="label <?php echo $status['class'] ?>">[<?php echo $status['status']; ?>]</span>
                    <a href="#" class="stack-btn"><span
                            class="label label-inverse">Показать Stack Trace</span></a>

                    <pre><?php echo $model->showError($logLine); ?></pre>
                    <pre class="stack-pre" style="display:none;"><?php echo$model->showStack($logLine); ?></pre>
                </div>
                <?php
            endif;
        endforeach;
        ?>
    </div>
</div>