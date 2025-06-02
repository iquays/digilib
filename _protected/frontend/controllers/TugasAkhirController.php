<?php

namespace frontend\controllers;

use yii\helpers\Url;
use Yii;
use common\models\TugasAkhir;
use common\models\TugasAkhirSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TugasAkhirController implements the CRUD actions for TugasAkhir model.
 */
class TugasAkhirController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'httpCache' => [
                'class' => 'yii\filters\HttpCache',
                'only' => ['index'],
                'lastModified' => function ($action, $params) {
                    $q = new \yii\db\Query();
                    return strtotime($q->from('tugas_akhir')->max('updated_at'));
                },
                'sessionCacheLimiter' => 'public',
                'cacheControlHeader' => 'public, max-age=7200',
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TugasAkhir models.
     * @return mixed
     */

    public function actionIndex()
    {
        $minimumAge = 3;
        $currentDate = date('Y-m-d');
        $latestDate = strtotime(($currentDate . ' -' . $minimumAge . ' years'));

        $searchModel = new TugasAkhirSearch();
//        $searchModel->latestDate = date('Y-m-d', $latestDate);
        $searchModel->latestYear = substr(date('Y-m-d', $latestDate), 0, 4);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $tahuns = $searchModel->searchTahuns(Yii::$app->request->queryParams);

//        if (Yii::$app->request->isPjax) {
//            return $this->renderPartial('index', [
//                'searchModel' => $searchModel,
//                'dataProvider' => $dataProvider,
//                'tahuns' => $tahuns,
//            ]);
//        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tahuns' => $tahuns,
        ]);
    }

    /**
     * Displays a single TugasAkhir model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single Status model.
     * @param string $slug
     * @return mixed
     */
    public function actionSlug($slug)
    {
        $journal_title = "PENS Final Project";
        $publisher = "Politeknik Elektronika Negeri Surabaya";

        $model = TugasAkhir::find()->where(['slug' => $slug])->one();
        if (null !== $model) {
            $view = Yii::$app->view;

            $view->registerMetaTag([
                'name' => 'citation_title',
                'content' => $model->judul_id
            ]);
            $view->registerMetaTag([
                'name' => 'citation_author',
                'content' => $model->mahasiswa->nama
            ]);
            foreach ($model->dibimbings as $dibimbing) {
                $view->registerMetaTag([
                    'name' => 'citation_author',
                    'content' => $dibimbing->dosen->nama
                ]);
            }
            $view->registerMetaTag([
                'name' => 'citation_publication_date',
                'content' => substr($model->created_at, 0, 4) . '/' . substr($model->created_at, 5, 2) . '/' . substr($model->created_at, 8, 2)
            ]);
            $view->registerMetaTag([
                'name' => 'citation_journal_title',
                'content' => $journal_title
            ]);
            $view->registerMetaTag([
                'name' => 'citation_pdf_url',
                'content' => Url::to(null, true) . 'file/' . $model->file_abstrak_id
            ]);

            $view->registerMetaTag([
                'name' => 'DC.title',
                'content' => $model->judul_id
            ]);
            $view->registerMetaTag([
                'name' => 'DC.creator',
                'content' => $model->mahasiswa->nama
            ]);
            foreach ($model->dibimbings as $dibimbing) {
                $view->registerMetaTag([
                    'name' => 'DC.creator',
                    'content' => $dibimbing->dosen->nama
                ]);
            }
            // Need Modification
            $view->registerMetaTag([
                'name' => 'DC.subject',
                'content' => 'Computer Science'
            ]);
            $view->registerMetaTag([
                'name' => 'DC.subject',
                'content' => 'Technology'
            ]);
            // End of Need Modification

            $view->registerMetaTag([
                'name' => 'DC.description',
                'content' => $model->abstrak_id
            ]);
            $view->registerMetaTag([
                'name' => 'DC.publisher',
                'content' => $publisher
            ]);
            $view->registerMetaTag([
                'name' => 'DC.date',
                'content' => substr($model->created_at, 0, 10)
            ]);
            $view->registerMetaTag([
                'name' => 'DC.type',
                'content' => 'Thesis'
            ]);
            $view->registerMetaTag([
                'name' => 'DC.type',
                'content' => 'PeerReviewed'
            ]);
            $view->registerMetaTag([
                'name' => 'DC.format',
                'content' => 'application/pdf'
            ]);
            $view->registerMetaTag([
                'name' => 'DC.identifier',
                'content' => Url::to(null, true) . 'file/' . $model->file_abstrak_id
            ]);
            $view->registerMetaTag([
                'name' => 'DC.relation',
                'content' => Url::to('tugas-akhir', true) . '/' . $model->slug
            ]);


            return $this->render('view', [
                'model' => $model,
            ]);
        }
        return $this->redirect(' /tugas-akhir/index');

    }


    /**
     * Finds the TugasAkhir model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TugasAkhir the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TugasAkhir::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist . ');
    }
}
