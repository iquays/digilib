<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TugasAkhir;

/**
 * TugasAkhirSearch represents the model behind the search form of `common\models\TugasAkhir`.
 */
class TugasAkhirSearch extends TugasAkhir
{
    public $latestDate;
    public $latestYear;
    public $authorName;
    public $tahuns;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status_isian', 'status_buku', 'status_cover', 'status_pengesahan', 'status_abstrak_id', 'status_abstrak_en', 'status_kata_pengantar', 'status_daftar_isi', 'status_bab1', 'status_bab2', 'status_bab3', 'status_bab4', 'status_bab5', 'status_lampiran', 'status_biodata', 'status_paper', 'status_presentasi', 'status_all'], 'integer'],
            [['nrp_mahasiswa', 'tahun', 'judul_id', 'judul_en', 'abstrak_id', 'abstrak_en', 'keyword_id', 'keyword_en', 'file_buku', 'file_cover', 'file_pengesahan', 'file_abstrak_id', 'file_abstrak_en', 'file_kata_pengantar', 'file_daftar_isi', 'file_bab1', 'file_bab2', 'file_bab3', 'file_bab4', 'file_bab5', 'file_lampiran', 'file_biodata', 'file_paper', 'file_presentasi', 'status_isian_admin', 'status_buku_admin', 'status_cover_admin', 'status_pengesahan_admin', 'status_abstrak_id_admin', 'status_abstrak_en_admin', 'status_kata_pengantar_admin', 'status_daftar_isi_admin', 'status_bab1_admin', 'status_bab2_admin', 'status_bab3_admin', 'status_bab4_admin', 'status_bab5_admin', 'status_lampiran_admin', 'status_biodata_admin', 'status_paper_admin', 'status_presentasi_admin', 'status_all_admin', 'slug', 'created_at', 'updated_at', 'authorName', 'latestDate', 'tahuns'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TugasAkhir::find()->distinct();

        $query->innerJoinWith('mahasiswa', true);
        $query->innerJoinWith('dosens', true);

        // add conditions that should always apply here
//        $query->andFilterWhere(['<', 'created_at', $this->latestDate]);
        $query->andFilterWhere(['<=', 'tahun', $this->latestYear]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ],
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'nrp_mahasiswa', $this->nrp_mahasiswa])
            ->andFilterWhere(['like', 'tahun', $this->tahun])
            ->andFilterWhere(['like', 'judul_id', $this->judul_id])
            ->andFilterWhere(['like', 'judul_en', $this->judul_en])
            ->andFilterWhere(['like', 'abstrak_id', $this->abstrak_id])
            ->andFilterWhere(['like', 'abstrak_en', $this->abstrak_en])
            ->andFilterWhere(['like', 'keyword_id', $this->keyword_id])
            ->andFilterWhere(['like', 'keyword_en', $this->keyword_en]);

        $query->andFilterWhere(['or', ['like', 'mahasiswa.nama', $this->authorName], ['like', 'dosen.nama', $this->authorName]]);

        $query->andFilterWhere(['in', 'tahun', $this->tahuns]);

        return $dataProvider;
    }


    public function searchTahuns($params)
    {
        $query = TugasAkhir::find()->select(['tahun'])->distinct()->orderBy('tahun DESC');
        $query2 = TugasAkhir::find()->distinct();

        $query->innerJoinWith('mahasiswa', true);
        $query->innerJoinWith('dosens', true);
        $query2->innerJoinWith('mahasiswa', true);
        $query2->innerJoinWith('dosens', true);

        // add conditions that should always apply here
//        $query->andFilterWhere(['<', 'created_at', $this->latestDate]);
//        $query2->andFilterWhere(['<', 'created_at', $this->latestDate]);
        $query->andFilterWhere(['<=', 'tahun', $this->latestYear]);
        $query2->andFilterWhere(['<=', 'tahun', $this->latestYear]);


        $this->load($params);

        $query
            ->andFilterWhere(['like', 'judul_id', $this->judul_id])
            ->andFilterWhere(['like', 'keyword_id', $this->keyword_id])
            ->andFilterWhere(['like', 'keyword_en', $this->keyword_en]);

        $query2
            ->andFilterWhere(['like', 'judul_id', $this->judul_id])
            ->andFilterWhere(['like', 'keyword_id', $this->keyword_id])
            ->andFilterWhere(['like', 'keyword_en', $this->keyword_en]);

        $query->andFilterWhere(['or', ['like', 'mahasiswa.nama', $this->authorName], ['like', 'dosen.nama', $this->authorName]]);
        $query2->andFilterWhere(['or', ['like', 'mahasiswa.nama', $this->authorName], ['like', 'dosen.nama', $this->authorName]]);

        $models = $query->all();
        $years = [];
        foreach ($models as $t) {
            $jumlah = $query2->where(['tahun' => $t->tahun])->count();
            $years[] = [$t->tahun, $jumlah];
        }
        return $years;
    }
}
