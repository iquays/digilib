<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ta".
 *
 * @property string $id
 * @property string $nrp
 * @property string $nama
 * @property string $jurusan
 * @property string $email
 * @property string $telpon
 * @property string $pembimbing1
 * @property string $nip1
 * @property string $pembimbing2
 * @property string $nip2
 * @property string $pembimbing3
 * @property string $nip3
 * @property string $pembimbing4
 * @property string $nip4
 * @property string $tahun
 * @property string $judul_id
 * @property string $judul_en
 * @property string $abstrak_id
 * @property string $abstrak_en
 * @property string $keyword_id
 * @property string $keyword_en
 * @property string $file_buku
 * @property string $file_cover
 * @property string $file_pengesahan
 * @property string $file_abstrak_id
 * @property string $file_abstrak_en
 * @property string $file_kata_pengantar
 * @property string $file_daftar_isi
 * @property string $file_bab1
 * @property string $file_bab2
 * @property string $file_bab3
 * @property string $file_bab4
 * @property string $file_bab5
 * @property string $file_lampiran
 * @property string $file_biodata
 * @property string $file_paper
 * @property string $file_presentasi
 * @property int $status_isian
 * @property int $status_buku
 * @property int $status_cover
 * @property int $status_pengesahan
 * @property int $status_abstrak_id
 * @property int $status_abstrak_en
 * @property int $status_kata_pengantar
 * @property int $status_daftar_isi
 * @property int $status_bab1
 * @property int $status_bab2
 * @property int $status_bab3
 * @property int $status_bab4
 * @property int $status_bab5
 * @property int $status_lampiran
 * @property int $status_biodata
 * @property int $status_paper
 * @property int $status_presentasi
 * @property int $status_all
 * @property string $status_isian_admin
 * @property string $status_buku_admin
 * @property string $status_cover_admin
 * @property string $status_pengesahan_admin
 * @property string $status_abstrak_id_admin
 * @property string $status_abstrak_en_admin
 * @property string $status_kata_pengantar_admin
 * @property string $status_daftar_isi_admin
 * @property string $status_bab1_admin
 * @property string $status_bab2_admin
 * @property string $status_bab3_admin
 * @property string $status_bab4_admin
 * @property string $status_bab5_admin
 * @property string $status_lampiran_admin
 * @property string $status_biodata_admin
 * @property string $status_paper_admin
 * @property string $status_presentasi_admin
 * @property string $status_all_admin
 */
class Ta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nrp', 'nama', 'jurusan', 'email', 'telpon', 'pembimbing1', 'nip1', 'pembimbing2', 'nip2', 'pembimbing3', 'nip3', 'pembimbing4', 'nip4', 'tahun', 'judul_id', 'judul_en', 'abstrak_id', 'abstrak_en', 'keyword_id', 'keyword_en', 'file_buku', 'file_cover', 'file_pengesahan', 'file_abstrak_id', 'file_abstrak_en', 'file_kata_pengantar', 'file_daftar_isi', 'file_bab1', 'file_bab2', 'file_bab3', 'file_bab4', 'file_bab5', 'file_lampiran', 'file_biodata', 'file_paper', 'file_presentasi', 'status_isian', 'status_buku', 'status_cover', 'status_pengesahan', 'status_abstrak_id', 'status_abstrak_en', 'status_kata_pengantar', 'status_daftar_isi', 'status_bab1', 'status_bab2', 'status_bab3', 'status_bab4', 'status_bab5', 'status_lampiran', 'status_biodata', 'status_paper', 'status_presentasi', 'status_all', 'status_isian_admin', 'status_buku_admin', 'status_cover_admin', 'status_pengesahan_admin', 'status_abstrak_id_admin', 'status_abstrak_en_admin', 'status_kata_pengantar_admin', 'status_daftar_isi_admin', 'status_bab1_admin', 'status_bab2_admin', 'status_bab3_admin', 'status_bab4_admin', 'status_bab5_admin', 'status_lampiran_admin', 'status_biodata_admin', 'status_paper_admin', 'status_presentasi_admin', 'status_all_admin'], 'required'],
            [['abstrak_id', 'abstrak_en'], 'string'],
            [['status_isian', 'status_buku', 'status_cover', 'status_pengesahan', 'status_abstrak_id', 'status_abstrak_en', 'status_kata_pengantar', 'status_daftar_isi', 'status_bab1', 'status_bab2', 'status_bab3', 'status_bab4', 'status_bab5', 'status_lampiran', 'status_biodata', 'status_paper', 'status_presentasi', 'status_all'], 'integer'],
            [['id'], 'string', 'max' => 50],
            [['nrp'], 'string', 'max' => 15],
            [['nama', 'email', 'pembimbing1', 'pembimbing2', 'pembimbing3', 'pembimbing4'], 'string', 'max' => 100],
            [['jurusan', 'file_buku', 'file_cover', 'file_pengesahan', 'file_abstrak_id', 'file_abstrak_en', 'file_kata_pengantar', 'file_daftar_isi', 'file_bab1', 'file_bab2', 'file_bab3', 'file_bab4', 'file_bab5', 'file_lampiran', 'file_biodata', 'file_paper', 'file_presentasi'], 'string', 'max' => 40],
            [['telpon', 'status_isian_admin', 'status_buku_admin', 'status_cover_admin', 'status_pengesahan_admin', 'status_abstrak_id_admin', 'status_abstrak_en_admin', 'status_kata_pengantar_admin', 'status_daftar_isi_admin', 'status_bab1_admin', 'status_bab2_admin', 'status_bab3_admin', 'status_bab4_admin', 'status_bab5_admin', 'status_lampiran_admin', 'status_biodata_admin', 'status_paper_admin', 'status_presentasi_admin', 'status_all_admin'], 'string', 'max' => 30],
            [['nip1', 'nip2', 'nip3', 'nip4'], 'string', 'max' => 20],
            [['tahun'], 'string', 'max' => 4],
            [['judul_id', 'judul_en'], 'string', 'max' => 300],
            [['keyword_id', 'keyword_en'], 'string', 'max' => 200],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nrp' => 'Nrp',
            'nama' => 'Nama',
            'jurusan' => 'Jurusan',
            'email' => 'Email',
            'telpon' => 'Telpon',
            'pembimbing1' => 'Pembimbing1',
            'nip1' => 'Nip1',
            'pembimbing2' => 'Pembimbing2',
            'nip2' => 'Nip2',
            'pembimbing3' => 'Pembimbing3',
            'nip3' => 'Nip3',
            'pembimbing4' => 'Pembimbing4',
            'nip4' => 'Nip4',
            'tahun' => 'Tahun',
            'judul_id' => 'Judul ID',
            'judul_en' => 'Judul En',
            'abstrak_id' => 'Abstrak ID',
            'abstrak_en' => 'Abstrak En',
            'keyword_id' => 'Keyword ID',
            'keyword_en' => 'Keyword En',
            'file_buku' => 'File Buku',
            'file_cover' => 'File Cover',
            'file_pengesahan' => 'File Pengesahan',
            'file_abstrak_id' => 'File Abstrak ID',
            'file_abstrak_en' => 'File Abstrak En',
            'file_kata_pengantar' => 'File Kata Pengantar',
            'file_daftar_isi' => 'File Daftar Isi',
            'file_bab1' => 'File Bab1',
            'file_bab2' => 'File Bab2',
            'file_bab3' => 'File Bab3',
            'file_bab4' => 'File Bab4',
            'file_bab5' => 'File Bab5',
            'file_lampiran' => 'File Lampiran',
            'file_biodata' => 'File Biodata',
            'file_paper' => 'File Paper',
            'file_presentasi' => 'File Presentasi',
            'status_isian' => 'Status Isian',
            'status_buku' => 'Status Buku',
            'status_cover' => 'Status Cover',
            'status_pengesahan' => 'Status Pengesahan',
            'status_abstrak_id' => 'Status Abstrak ID',
            'status_abstrak_en' => 'Status Abstrak En',
            'status_kata_pengantar' => 'Status Kata Pengantar',
            'status_daftar_isi' => 'Status Daftar Isi',
            'status_bab1' => 'Status Bab1',
            'status_bab2' => 'Status Bab2',
            'status_bab3' => 'Status Bab3',
            'status_bab4' => 'Status Bab4',
            'status_bab5' => 'Status Bab5',
            'status_lampiran' => 'Status Lampiran',
            'status_biodata' => 'Status Biodata',
            'status_paper' => 'Status Paper',
            'status_presentasi' => 'Status Presentasi',
            'status_all' => 'Status All',
            'status_isian_admin' => 'Status Isian Admin',
            'status_buku_admin' => 'Status Buku Admin',
            'status_cover_admin' => 'Status Cover Admin',
            'status_pengesahan_admin' => 'Status Pengesahan Admin',
            'status_abstrak_id_admin' => 'Status Abstrak Id Admin',
            'status_abstrak_en_admin' => 'Status Abstrak En Admin',
            'status_kata_pengantar_admin' => 'Status Kata Pengantar Admin',
            'status_daftar_isi_admin' => 'Status Daftar Isi Admin',
            'status_bab1_admin' => 'Status Bab1 Admin',
            'status_bab2_admin' => 'Status Bab2 Admin',
            'status_bab3_admin' => 'Status Bab3 Admin',
            'status_bab4_admin' => 'Status Bab4 Admin',
            'status_bab5_admin' => 'Status Bab5 Admin',
            'status_lampiran_admin' => 'Status Lampiran Admin',
            'status_biodata_admin' => 'Status Biodata Admin',
            'status_paper_admin' => 'Status Paper Admin',
            'status_presentasi_admin' => 'Status Presentasi Admin',
            'status_all_admin' => 'Status All Admin',
        ];
    }
}
