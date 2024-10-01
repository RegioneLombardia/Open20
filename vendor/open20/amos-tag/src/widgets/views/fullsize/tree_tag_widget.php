<?= $form->field($model, $name)->widget(\kartik\select2\Select2::class, [
    'data' => $data,
    'options' => $options,
    'pluginOptions' => [
        'allowClear' => true,
    ]
])
->label($label); 
