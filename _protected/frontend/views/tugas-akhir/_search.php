<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TugasAkhirSearch */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="tugas-akhir-search">

    <?php \yii\widgets\Pjax::begin(['id' => 'tugas-akhir-search']) ?>
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['data-pjax' => true]
    ]); ?>


    <!--    --><?php //echo $form->field($model, 'judul_id') ?>
    <!---->
    <!--    --><?php //echo $form->field($model, 'judul_en') ?>

    <div class="row">
        <div class="col-xs-12 col-sm-3">
            <?php echo $form->field($model, 'keyword_id') ?>
        </div>
        <div class="col-xs-12 col-sm-5">
            <?php echo $form->field($model, 'judul_id') ?>
        </div>

        <div class="col-xs-12 col-sm-4">
            <?php echo $form->field($model, 'authorName') ?>
        </div>
        <!--        --><?php //echo $form->field($model, 'tahuns[]')->checkboxList(['2013' => '2013', '2014' => '2014', '2015' => '2015']); ?>
        <!--        --><?php //echo $form->field($model, 'tahuns[]')->hiddenInput()->label(false) ?>

        <!--        --><?php //$model->tahuns = ['2013', '2014'] ?>

        <!--    --><?php //echo $form->field($model, 'keyword_id') ?>
        <!--        --><?php //echo $form->field($model, 'tahun') ?>

        <?php // echo $form->field($model, 'keyword_en') ?>

        <?php // echo $form->field($model, 'abstrak_id') ?>

        <?php // echo $form->field($model, 'abstrak_en') ?>

    </div>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php \yii\widgets\Pjax::end() ?>

</div>

