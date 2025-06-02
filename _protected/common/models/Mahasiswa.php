<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mahasiswa".
 *
 * @property string $nrp
 * @property string $nama
 * @property string $email
 * @property string $telpon
 * @property int $id_jurusan
 *
 * @property Jurusan $jurusan
 * @property TugasAkhir[] $tugasAkhirs
 */
class Mahasiswa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mahasiswa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nrp', 'nama', 'id_jurusan'], 'required'],
            [['id_jurusan'], 'integer'],
            [['nrp'], 'string', 'max' => 10],
            [['nama', 'email'], 'string', 'max' => 100],
            [['telpon'], 'string', 'max' => 30],
            [['nrp'], 'unique'],
            [['id_jurusan'], 'exist', 'skipOnError' => true, 'targetClass' => Jurusan::className(), 'targetAttribute' => ['id_jurusan' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nrp' => 'Nrp',
            'nama' => 'Nama',
            'email' => 'Email',
            'telpon' => 'Telpon',
            'id_jurusan' => 'Id Jurusan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJurusan()
    {
        return $this->hasOne(Jurusan::className(), ['id' => 'id_jurusan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTugasAkhirs()
    {
        return $this->hasMany(TugasAkhir::className(), ['nrp_mahasiswa' => 'nrp']);
    }
}
