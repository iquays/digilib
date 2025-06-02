<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TugasAkhirSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $tahuns */

$this->title = 'Tugas Akhir';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tugas-akhir-index">

    <!--    --><?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php \yii\widgets\Pjax::begin([
        'id' => 'tugas-akhir-search',
        'timeout' => 5000,
    ]) ?>

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="row">
        <div class="col-xs-12 col-sm-3">
            <?php echo $form->field($searchModel, 'keyword_id') ?>
        </div>
        <div class="col-xs-12 col-sm-5">
            <?php echo $form->field($searchModel, 'judul_id') ?>
        </div>

        <div class="col-xs-12 col-sm-4">
            <?php echo $form->field($searchModel, 'authorName') ?>
        </div>

    </div>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-search"></i> Cari', ['class' => 'btn btn-danger btn-lg btn-fill btn-round']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php \yii\widgets\Pjax::end() ?>


    <div class="row">
        <div class="col-xs-12 col-sm-2">
            <div class="container">
                <div class="row">
                    <h4>Filter:</h4>
                </div>
                <div class="row">
                    <h5>Tahun</h5>
                </div>
                <div class="row">
                    <?php foreach ($tahuns as $tahun): ?>
                        <div class="col-xs-6 col-sm-12">
                            <!--                            <label>-->
                            <input type="checkbox" value="<?= $tahun ?>" id="checkbox-<?= $tahun ?>"
                                   class="pilihan-tahun"> <?= $tahun ?>
                            <!--                            </label>-->
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <?php \yii\widgets\Pjax::begin([
            'id' => 'tugas-akhir-index',
            'timeout' => 5000,
        ]) ?>

        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <h4 id="hasil-pencarian">Hasil Pencarian: <?php echo $dataProvider->totalCount ?> data</h4>
            </div>
            <?php
            $tugasAkhirs = $dataProvider->models;;
            $rowNum = 1 + $dataProvider->pagination->pageSize * $dataProvider->pagination->page;
            ?>
            <div class="row">
                <table class="table table-striped table-hover">
                    <?php foreach ($tugasAkhirs as $tugasAkhir): ?>
                        <tr>
                            <td>
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
                                <p><a href="<?= Yii::$app->homeUrl . 'file/' . $tugasAkhir->file_abstrak_id ?>"><i
                                                class="glyphicon glyphicon-download-alt"></i>Download
                                        Abstract</a>
                                </p>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="row">
                <?= LinkPager::widget([
                    'pagination' => $dataProvider->pagination,
                    'options' => [
                        'class' => 'pagination pagination-lg ct-red',
                    ],
                ]) ?>
            </div>
        </div>
        <?php \yii\widgets\Pjax::end() ?>
    </div>
</div>

<?php
$this->registerJs(
    '$("document").ready(function(){
        $.pjax.defaults.timeout = 5000;
        $("form").on("submit", function(e){
            $("#hasil-pencarian").text("Menghitung...");
        });
        $("#tugas-akhir-search").on("pjax:end", function(data, status, xhr, options) {
                $.pjax.reload({container:"#tugas-akhir-index"});
                $(".pilihan-tahun").each(function(){
                    if(this.checked) {
                        var el = "<input id=" + this.value +" type=hidden name=TugasAkhirSearch[tahuns][] value=" + this.value + ">";                
                        $("form > .row").append(el);
                    }
                });
        });
        
        $(".pilihan-tahun").change(function () {
            if (this.checked) {
                $("#hasil-pencarian").text("Menghitung...");
                var el = "<input id=" + this.value +" type=hidden name=TugasAkhirSearch[tahuns][] value=" + this.value + ">";                
                $("form > .row").append(el);
                $("#w0").submit();
            } else {
                $("#hasil-pencarian").text("Menghitung...");
                $("#" + this.value).remove();
                $("#w0").submit();
            }
        });
        
    });'
);

?>
