<?php


/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\seo
 * @category   CategoryName
 */

use open20\amos\seo\assets\AnalyzerAsset;
use open20\amos\seo\assets\SeoAsset;
use open20\amos\seo\AmosSeo;

$bundle = SeoAsset::register($this);
$analyzerBundle = AnalyzerAsset::register($this);
$workerUrl = \Yii::$app->assetManager->getAssetUrl($analyzerBundle, 'js/yoast-worker.js');
$jsonFields = json_encode($fields);
$jsAutoUpdate = $autoUpdate ? 'true' : 'false';

$js = <<< JS
const fields = {$jsonFields};
const parser = new DOMParser();
const component = document.getElementById('{$id}');

function debounce(func, timeout = 300) {
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => {
      func.apply(this, args);
    }, timeout);
  };
}

function stripTags(text) {
  const parser = document.createElement('div');
  parser.innerHTML = text;
  const result = parser.innerText;
  parser.remove();
  return result;
}

function loadResults(analysis) {
    const getQualitativeScore = function(value) {
        if (!value) return 'suggestion';
        if (value <= 4) return 'poor';
        if (value <= 7) return 'fair';
        return 'good';
    }

    document.getElementById('{$id}-results').style.display = 'block';

    document.getElementById('{$id}-readability-analysis').textContent = ''; // empties node
    document.getElementById('{$id}-seo-analysis').textContent = '';

    for (const result of analysis?.result?.readability?.results) {
        if (result.text.length) {
          const container = document.createElement("div");
          container.classList.add(getQualitativeScore(result.score));
          const scoreContainer = document.createElement("div");
          container.appendChild(scoreContainer);
          const scoreSpan = document.createElement("span");
          scoreSpan.classList.add("result-seo");
          scoreSpan.textContent = result.score;
          scoreContainer.appendChild(scoreSpan);
          const textSpan = document.createElement("span");
          textSpan.innerHTML = result.text;
          container.appendChild(textSpan);
          document.getElementById('{$id}-readability-analysis').appendChild(container);
        }



        `<div class='\${getQualitativeScore(result.score)}'><div><span class="result-seo">\${result.score}</span></div><span>\${result.text}</span></div>`
    }
    for (const keyword in analysis?.result?.seo) {
        for (const result of analysis?.result?.seo?.[keyword]?.results) {
          if (result.text.length) {
            const container = document.createElement("div");
            container.classList.add(getQualitativeScore(result.score));
            const scoreContainer = document.createElement("div");
            container.appendChild(scoreContainer);
            const scoreSpan = document.createElement("span");
            scoreSpan.classList.add("result-seo");
            scoreSpan.textContent = result.score;
            scoreContainer.appendChild(scoreSpan);
            const textSpan = document.createElement("span");
            textSpan.innerHTML = result.text;
            container.appendChild(textSpan);
            document.getElementById('{$id}-seo-analysis').appendChild(container);
          }
        }
    }
}

function checkUrl(urlString) {
  let url;
  try {
    url = new URL(urlString);
  } catch (_) {
    return false;
  }
  return url.protocol === "http:" || url.protocol === "https:";
}

const analyzer = new Yoast.Analyzer({locale: '{$locale}', workerUrl: '{$workerUrl}'}).then((analyzer) => {

  const addToAnalyzer = (field) => {
    const fieldValue = document.querySelector(fields[field].selector)?.value;
    parseField(field, fieldValue);
  }

  const lookup = (event) => {
    debounce(() => component.dispatchEvent(new Event('analyze')))();
  }

  const parseField = (field, fieldValue) => {
    if (field == 'text')
        analyzer.text = fieldValue;
    else if (field == 'title') {
      analyzer.options[field] = fieldValue;
      analyzer.options['titleWidth'] = fieldValue.length * 8;
    }
    else if (field == 'permalink') {
        const url = fieldValue;
        if (url && url.length > 0) {
          if (checkUrl(url))
              analyzer.options[field] = url;
          else if (checkUrl('{$baseUrl}/' + url))
              analyzer.options[field] = '{$baseUrl}/' + url;
        }
    }
    else if (field == 'locale' && !fieldValue)
      analyzer.options[field] = '{$locale}';
    else if (analyzer.options.hasOwnProperty(field))
        analyzer.options[field] = fieldValue;
  }

  if ({$jsAutoUpdate})
    for (const field in fields) {
      const data = { field };
      const fieldDom = document.querySelector(fields[field].selector);
      if (fieldDom)
        document.addEventListener(fields[field].event || 'input', function (e) {
          if (e.target.closest(fields[field].selector)) {
            lookup(e);
          }
        });
    }

  component.addEventListener('analyze', function () {
    document.querySelector('#{$id}-results').style.display = 'block';
    document.querySelector('#{$id}-no-text').style.display = 'none';

    for (const field in fields) {
      addToAnalyzer(field);
    }

    if (!stripTags(analyzer.text)) {
      document.querySelector('#{$id}-results').style.display = 'none';
      document.querySelector('#{$id}-no-text').style.display = 'block';
      return;
    }

    analyzer.analyze().then(loadResults).catch((error) => {
      document.getElementById('{$id}-readability-analysis').textContent = ''; // empties node
      document.getElementById('{$id}-seo-analysis').textContent = '';
      console.error(error);
    });

  });

  component.addEventListener('updatedata', function (event) {
    const {field, content} = event.detail;
    parseField(field, content);
  });

  component.dispatchEvent(new CustomEvent('analyze'));

});
JS;

$this->registerJs($js, \yii\web\View::POS_READY);

?>
<div id="<?=$id?>" class="content-seo-analyzer<?= !empty($options["class"]) ? (' ' . $options["class"]) : '';?>">
  <p><?=AmosSeo::t('amosseo', '#analyzer_keyphrase_description')?></p>
  <div class="form-group">
    <label for="<?=$id?>-keyphrase"><?=AmosSeo::t('amosseo', '#analyzer_keyphrase')?></label>
    <input class="form-control" id="<?=$id?>-keyphrase" placeholder="<?=AmosSeo::t('amosseo', '#analyzer_keyphrase')?>">
  </div>
  <div class="form-group">
    <label for="<?=$id?>-synonyms"><?=AmosSeo::t('amosseo', '#analyzer_synonyms')?></label>
    <input class="form-control" id="<?=$id?>-synonyms" placeholder="<?=AmosSeo::t('amosseo', '#analyzer_synonyms')?>">
  </div>
  <h4><?=AmosSeo::t('amosseo', '#analyzer_title')?></h4>
  <div id="<?=$id?>-results" class="analyzer-results">
    <h6 class="text-uppercase"><?=AmosSeo::t('amosseo', '#analyzer_readability')?></h6>
    <div class="m-b-20 analyzer-readability-analysis" id="<?=$id?>-readability-analysis">
    </div>
    <h6 class="text-uppercase"><?=AmosSeo::t('amosseo', '#analyzer_seo')?></h6>
    <div class="m-b-20 analyzer-readability-analysis" id="<?=$id?>-seo-analysis">
    </div>
  </div>
  <div id="<?=$id?>-no-text" class="analyzer-no-text">
    <?=AmosSeo::t('amosseo', '#analyzer_no_text')?>
  </div>
</div>
