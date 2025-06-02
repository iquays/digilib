<?php

namespace frontend\controllers;

use common\models\Jurusan;
use common\models\Mahasiswa;
use common\models\Pembimbing;
use common\models\Ta;
use common\models\TugasAkhir;
use common\models\User;
use common\models\LoginForm;
use frontend\models\AccountActivation;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\helpers\Html;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Yii;

/**
 * Site controller.
 * It is responsible for displaying static pages, logging users in and out,
 * sign up and account activation, password reset.
 */
class SiteController extends Controller
{
    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'normalisasi'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['normalisasi'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Declares external actions for the controller.
     *
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

//------------------------------------------------------------------------------------------------//
// STATIC PAGES
//------------------------------------------------------------------------------------------------//

    /**
     * Displays the index (home) page.
     * Use it in case your home page contains static content.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays the about static page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Displays the contact static page and sends the contact email.
     *
     * @return string|\yii\web\Response
     */
    public function actionContact()
    {
        $model = new ContactForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->contact(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success',
                    'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

//------------------------------------------------------------------------------------------------//
// LOG IN / LOG OUT / PASSWORD RESET
//------------------------------------------------------------------------------------------------//

    /**
     * Logs in the user if his account is activated,
     * if not, displays appropriate message.
     *
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        // get setting value for 'Login With Email'
        $lwe = Yii::$app->params['lwe'];

        // if 'lwe' value is 'true' we instantiate LoginForm in 'lwe' scenario
        $model = $lwe ? new LoginForm(['scenario' => 'lwe']) : new LoginForm();

        // now we can try to log in the user
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } // user couldn't be logged in, because he has not activated his account
        elseif ($model->notActivated()) {
            // if his account is not activated, he will have to activate it first
            Yii::$app->session->setFlash('error',
                'You have to activate your account first. Please check your email.');

            return $this->refresh();
        } // account is activated, but some other errors have happened
        else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the user.
     *
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /*----------------*
     * PASSWORD RESET *
     *----------------*/

    /**
     * Sends email that contains link for password reset action.
     *
     * @return string|\yii\web\Response
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success',
                    'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error',
                    'Sorry, we are unable to reset password for email provided.');
            }
        } else {
            return $this->render('requestPasswordResetToken', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Resets password.
     *
     * @param  string $token Password reset token.
     * @return string|\yii\web\Response
     *
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post())
            && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        } else {
            return $this->render('resetPassword', [
                'model' => $model,
            ]);
        }
    }

//------------------------------------------------------------------------------------------------//
// SIGN UP / ACCOUNT ACTIVATION
//------------------------------------------------------------------------------------------------//

    /**
     * Signs up the user.
     * If user need to activate his account via email, we will display him
     * message with instructions and send him account activation email
     * ( with link containing account activation token ). If activation is not
     * necessary, we will log him in right after sign up process is complete.
     * NOTE: You can decide whether or not activation is necessary,
     * @see config/params.php
     *
     * @return string|\yii\web\Response
     */
    public function actionSignup()
    {
        // get setting value for 'Registration Needs Activation'
        $rna = Yii::$app->params['rna'];

        // if 'rna' value is 'true', we instantiate SignupForm in 'rna' scenario
        $model = $rna ? new SignupForm(['scenario' => 'rna']) : new SignupForm();

        // collect and validate user data
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // try to save user data in database
            if ($user = $model->signup()) {
                // if user is active he will be logged in automatically ( this will be first user )
                if ($user->status === User::STATUS_ACTIVE) {
                    if (Yii::$app->getUser()->login($user)) {
                        return $this->goHome();
                    }
                } // activation is needed, use signupWithActivation()
                else {
                    $this->signupWithActivation($model, $user);

                    return $this->refresh();
                }
            } // user could not be saved in database
            else {
                // display error message to user
                Yii::$app->session->setFlash('error',
                    "We couldn't sign you up, please contact us.");

                // log this error, so we can debug possible problem easier.
                Yii::error('Signup failed! 
                    User ' . Html::encode($user->username) . ' could not sign up.
                    Possible causes: something strange happened while saving user in database.');

                return $this->refresh();
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Sign up user with activation.
     * User will have to activate his account using activation link that we will
     * send him via email.
     *
     * @param $model
     * @param $user
     */
    private function signupWithActivation($model, $user)
    {
        // try to send account activation email
        if ($model->sendAccountActivationEmail($user)) {
            Yii::$app->session->setFlash('success',
                'Hello ' . Html::encode($user->username) . '. 
                To be able to log in, you need to confirm your registration. 
                Please check your email, we have sent you a message.');
        } // email could not be sent
        else {
            // display error message to user
            Yii::$app->session->setFlash('error',
                "We couldn't send you account activation email, please contact us.");

            // log this error, so we can debug possible problem easier.
            Yii::error('Signup failed! 
                User ' . Html::encode($user->username) . ' could not sign up.
                Possible causes: verification email could not be sent.');
        }
    }

    /*--------------------*
     * ACCOUNT ACTIVATION *
     *--------------------*/

    /**
     * Activates the user account so he can log in into system.
     *
     * @param  string $token
     * @return \yii\web\Response
     *
     * @throws BadRequestHttpException
     */
    public function actionActivateAccount($token)
    {
        try {
            $user = new AccountActivation($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($user->activateAccount()) {
            Yii::$app->session->setFlash('success',
                'Success! You can now log in. 
                Thank you ' . Html::encode($user->username) . ' for joining us!');
        } else {
            Yii::$app->session->setFlash('error',
                '' . Html::encode($user->username) . ' your account could not be activated, 
                please contact us!');
        }

        return $this->redirect('login');
    }

    public function actionNormalisasi()
    {
        echo "<h1>Mulai!!!</h1>";
        echo "<h1>.....</h1>";
//        $tas = Ta::find()->limit(1)->all();
        $tas = Ta::find()->orderBy('id')->all();
        foreach ($tas as $ta) {

            // Insert data mahasiswa
            $mahasiswa = new Mahasiswa();
            $mahasiswa->nrp = $ta->nrp;
            $mahasiswa->nama = $ta->nama;
            $mahasiswa->email = $ta->email;
            $mahasiswa->telpon = $ta->telpon;
            $mahasiswa->id_jurusan = Jurusan::findOne(['nama' => $ta->jurusan])->id;
            $mahasiswa->save();

            // Insert data TA
            $tugasAkhir = new TugasAkhir();
            $tugasAkhir->nrp_mahasiswa = $ta->nrp;
            $tugasAkhir->tahun = $ta->tahun;
            $tugasAkhir->judul_id = $ta->judul_id;
            $tugasAkhir->judul_en = $ta->judul_en;
            $tugasAkhir->abstrak_id = $ta->abstrak_id;
            $tugasAkhir->abstrak_en = $ta->abstrak_en;
            $tugasAkhir->keyword_id = $ta->keyword_id;
            $tugasAkhir->keyword_en = $ta->keyword_en;
            $tugasAkhir->file_buku = $ta->file_buku;
            $tugasAkhir->file_cover = $ta->file_cover;
            $tugasAkhir->file_pengesahan = $ta->file_pengesahan;
            $tugasAkhir->file_abstrak_id = $ta->file_abstrak_id;
            $tugasAkhir->file_abstrak_en = $ta->file_abstrak_en;
            $tugasAkhir->file_kata_pengantar = $ta->file_kata_pengantar;
            $tugasAkhir->file_daftar_isi = $ta->file_daftar_isi;
            $tugasAkhir->file_bab1 = $ta->file_bab1;
            $tugasAkhir->file_bab2 = $ta->file_bab2;
            $tugasAkhir->file_bab3 = $ta->file_bab3;
            $tugasAkhir->file_bab4 = $ta->file_bab4;
            $tugasAkhir->file_bab5 = $ta->file_bab5;
            $tugasAkhir->file_lampiran = $ta->file_lampiran;
            $tugasAkhir->file_biodata = $ta->file_biodata;
            $tugasAkhir->file_paper = $ta->file_paper;
            $tugasAkhir->file_presentasi = $ta->file_presentasi;
            $tugasAkhir->status_isian = $ta->status_isian;
            $tugasAkhir->status_buku = $ta->status_buku;
            $tugasAkhir->status_cover = $ta->status_cover;
            $tugasAkhir->status_pengesahan = $ta->status_pengesahan;
            $tugasAkhir->status_abstrak_id = $ta->status_abstrak_id;
            $tugasAkhir->status_abstrak_en = $ta->status_abstrak_en;
            $tugasAkhir->status_kata_pengantar = $ta->status_kata_pengantar;
            $tugasAkhir->status_daftar_isi = $ta->status_daftar_isi;
            $tugasAkhir->status_bab1 = $ta->status_bab1;
            $tugasAkhir->status_bab2 = $ta->status_bab2;
            $tugasAkhir->status_bab3 = $ta->status_bab3;
            $tugasAkhir->status_bab4 = $ta->status_bab4;
            $tugasAkhir->status_bab5 = $ta->status_bab5;
            $tugasAkhir->status_lampiran = $ta->status_lampiran;
            $tugasAkhir->status_biodata = $ta->status_biodata;
            $tugasAkhir->status_paper = $ta->status_paper;
            $tugasAkhir->status_presentasi = $ta->status_presentasi;
            $tugasAkhir->status_all = $ta->status_all;
            $tugasAkhir->status_isian_admin = $ta->status_isian_admin;
            $tugasAkhir->status_buku_admin = $ta->status_buku_admin;
            $tugasAkhir->status_cover_admin = $ta->status_cover_admin;
            $tugasAkhir->status_pengesahan_admin = $ta->status_pengesahan_admin;
            $tugasAkhir->status_abstrak_id_admin = $ta->status_abstrak_id_admin;
            $tugasAkhir->status_abstrak_en_admin = $ta->status_abstrak_en_admin;
            $tugasAkhir->status_kata_pengantar_admin = $ta->status_kata_pengantar_admin;
            $tugasAkhir->status_daftar_isi_admin = $ta->status_daftar_isi_admin;
            $tugasAkhir->status_bab1_admin = $ta->status_bab1_admin;
            $tugasAkhir->status_bab2_admin = $ta->status_bab2_admin;
            $tugasAkhir->status_bab3_admin = $ta->status_bab3_admin;
            $tugasAkhir->status_bab4_admin = $ta->status_bab4_admin;
            $tugasAkhir->status_bab5_admin = $ta->status_bab5_admin;
            $tugasAkhir->status_lampiran_admin = $ta->status_lampiran_admin;
            $tugasAkhir->status_biodata_admin = $ta->status_biodata_admin;
            $tugasAkhir->status_paper_admin = $ta->status_paper_admin;
            $tugasAkhir->status_presentasi_admin = $ta->status_presentasi_admin;
            $tugasAkhir->status_all_admin = $ta->status_all_admin;
            $tugasAkhir->created_at = substr($ta->id, 0, 4) . '-' . substr($ta->id, 4, 2) . '-' . substr($ta->id, 6, 2);
            $tugasAkhir->updated_at = $tugasAkhir->created_at;
            $nip1 = $ta->nip1;
            $nip2 = $ta->nip2;
            $nip3 = $ta->nip3;
            $nip4 = $ta->nip4;

            if ($tugasAkhir->save()) {
                $ta->delete();

                // Insert data pembimbing 1
                if (strlen($nip1) > 3) {
//                echo "<h2>Pembimbing Satu: " . $ta->pembimbing1 . "</h2>";
                    $pembimbing = new Pembimbing();
                    $pembimbing->id_tugas_akhir = $tugasAkhir->id;
                    $pembimbing->nip_dosen = $nip1;
                    $pembimbing->level = 1;
                    $pembimbing->save();
                }

                // Insert data pembimbing 2
                if (strlen($nip2) > 3) {
//                echo "<h2>Pembimbing Dua: " . $ta->pembimbing2 . "</h2>";
                    $pembimbing = new Pembimbing();
                    $pembimbing->id_tugas_akhir = $tugasAkhir->id;
                    $pembimbing->nip_dosen = $nip2;
                    $pembimbing->level = 2;
                    $pembimbing->save();
                }

                // Insert data pembimbing 3
                if (strlen($nip3) > 3) {
                    $pembimbing = new Pembimbing();
                    $pembimbing->id_tugas_akhir = $tugasAkhir->id;
                    $pembimbing->nip_dosen = $nip3;
                    $pembimbing->level = 3;
                    $pembimbing->save();
                }

                // Insert data pembimbing 4
                if (strlen($nip4) > 3) {
                    $pembimbing = new Pembimbing();
                    $pembimbing->id_tugas_akhir = $tugasAkhir->id;
                    $pembimbing->nip_dosen = $nip4;
                    $pembimbing->level = 4;
                    $pembimbing->save();
                }

            };


        }

        echo "<h1>Success!!!</h1>";

    }
}
