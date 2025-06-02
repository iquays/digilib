<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TugasAkhirSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $tahuns */

$this->title = 'Tugas Akhir';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tugas-akhir-index2">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php \yii\widgets\Pjax::begin(['id' => 'tugas-akhir-index2']) ?>

    <div class="row">
        <div class="col-xs-12 col-sm-2">
            <div class="container">
                <div class="row">
                    <h3>Filter:</h3>
                </div>
                <div class="row">
                    <h4>Tahun</h4>
                </div>
                <div class="row">
                    <?php foreach ($tahuns as $tahun): ?>
                        <div class="col-xs-6 col-sm-12">
                            <input type="checkbox" name="<?= $tahun ?>" value="<?= $tahun ?>"> <?= $tahun ?><br>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-10">
            <div class="container">
                <div class="row">
                    <h4>Hasil Pencarian: <?php echo $dataProvider->totalCount ?> data</h4>
                </div>
                <?php
                $tugasAkhirs = $dataProvider->models;;
                $rowNum = 1 + $dataProvider->pagination->pageSize * $dataProvider->pagination->page;
                ?>
                <?php foreach ($tugasAkhirs as $tugasAkhir): ?>
                    <div class="row">
                        <h5>
                            <a href="<?= Yii::$app->homeUrl . 'tugas-akhir/' . $tugasAkhir->slug ?>"> <?= $rowNum++ . '. ' . ucwords(strtolower($tugasAkhir->judul_id)) ?></a>
                        </h5>
                        <p>Authors:
                            <?php
                            echo $tugasAkhir->mahasiswa->nama;
                            foreach ($tugasAkhir->dibimbings as $author) {
                                echo ', ' . $author->dosen->nama;
                            }
                            ?>
                        </p>
                        <p><?= $tugasAkhir->created_at ?></p>
                        <p><a href="<?= Yii::$app->homeUrl . 'file/' . $tugasAkhir->file_abstrak_id ?>"><i
                                        class="glyphicon glyphicon-download-alt"></i>Download Abstract</a></p>
                    </div>
                <?php endforeach; ?>

                <div>
                    <?= LinkPager::widget(['pagination' => $dataProvider->pagination]) ?>
                </div>
            </div>
        </div>
    </div>
    <?php \yii\widgets\Pjax::end() ?>
</div>

<?php
//$this->registerJs(
//    '$("document").ready(function(){
//        $("input[type = \"checkbox\"").change(function () {
//            var years = [];
//            years.push("2013");
//            years.push("2014");
//            if (this.checked) {
//                $("#tugasakhirsearch-tahuns").val("[" + years + "]");
//                $("#w0").submit();
//            } else {
//                $("#tugasakhirsearch-tahun").val(null);
//            }
//        });
//    });'
//);
?>

