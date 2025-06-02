<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\TugasAkhir */

$this->title = ucwords(strtolower($model->judul_id);
$this->params['breadcrumbs'][] = ['label' => 'Tugas Akhir', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tugas-akhir-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'judul_id',
            'judul_en',
            'abstrak_id:ntext',
            'abstrak_en:ntext',
            'keyword_id',
            'keyword_en',
            'tahun',

        ],
    ]) ?>

    <p><a href="<?= Yii::$app->homeUrl . 'file/' . $model->file_abstrak_id ?>"><i
                    class="glyphicon glyphicon-download-alt"></i>Download Abstract</a></p>


</div>
