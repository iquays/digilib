<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dosen".
 *
 * @property string $nip
 * @property string $nama
 * @property int $id_jurusan
 *
 * @property Dibimbing[] $dibimbings
 */
class Dosen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dosen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nip', 'nama'], 'required'],
            [['id_jurusan'], 'integer'],
            [['nip'], 'string', 'max' => 18],
            [['nama'], 'string', 'max' => 100],
            [['nip'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nip' => 'Nip',
            'nama' => 'Nama',
            'id_jurusan' => 'Id Jurusan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDibimbings()
    {
        return $this->hasMany(Dibimbing::className(), ['nip_dosen' => 'nip']);
    }
}
