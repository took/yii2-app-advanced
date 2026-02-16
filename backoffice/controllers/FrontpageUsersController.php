<?php

namespace backoffice\controllers;

use common\models\User;
use common\models\UserSearch;
use Throwable;
use Yii;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * FrontpageUsersController implements the CRUD actions for User model.
 *
 * This controller manages frontpage user accounts from the backoffice administrative interface.
 * It provides user invitation functionality by sending email verification emails to new
 * frontpage users, allowing them to access the frontpage application.
 *
 * Access Control:
 * - View, create, resend-invite: Require 'edit-frontpage-user' or 'invite-frontpage-user' role
 * - Update, delete: Require 'edit-frontpage-user' role
 */
class FrontpageUsersController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        // Allow only authenticated users, deny all others
                        [
                            'actions' => [
                                'index',
                                'view',
                                'create',
                                'resend-invite-link'
                            ],
                            'allow' => true,
                            'roles' => ['edit-frontpage-user', 'invite-frontpage-user'],
                        ],
                        [
                            'actions' => [
                                'update',
                                'delete'
                            ],
                            'roles' => ['edit-frontpage-user'],
                            'allow' => true,
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                        'resend-invite-link' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     *
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): User
    {
        if (($model = User::findOne(['id_user' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate(): Response|string
    {
        $model = new User();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->password_hash = '';
                $model->generatePasswordResetToken();
                $model->generateAuthKey();
                $model->generateEmailVerificationToken();
                $model->created_at = time();
                $model->updated_at = time();
                if ($model->save()) {
                    $this->sendInviteEmail($model);
                    Yii::$app->session->setFlash('success', 'Invite mail sent.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        $model->status = User::STATUS_ACTIVE;
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Sends confirmation email to user
     *
     * @param User $user user model to with email should be sent
     * @return bool whether the email was sent
     */
    protected function sendInviteEmail(User $user): bool
    {
        $token = $user->password_reset_token;
        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = $timestamp + Yii::$app->params['user.passwordResetTokenExpire'];
        $valid_until = date('Y-m-d H:i', $expire);

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'invite-html', 'text' => 'invite-text'],
                [
                    'user' => $user,
                    'valid_until' => $valid_until,
                    'resetLink' => Yii::$app->params['frontpageUrl'] . Url::to(['site/reset-password', 'token' => $user->password_reset_token]),
                    'requestPasswordResetLink' => Yii::$app->params['frontpageUrl'] . Url::to(['site/request-password-reset']),
                ]
            )
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setTo($user->email)
            ->setSubject('Invite to ' . Yii::$app->params['frontpageUrl'])
            ->send();
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\db\Exception
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->updated_at = time();
            if ($model->save(true, ['username', 'email', 'status', 'updated_at'])) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Resends the invite link to the user.
     *
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionResendInviteLink(int $id): Response
    {
        $model = $this->findModel($id);
        if (!User::isPasswordResetTokenValid($model->password_reset_token)) {
            $model->generatePasswordResetToken();
            if (!$model->save()) {
                Yii::$app->session->setFlash('error', 'Could not send invite mail.');
                return $this->redirect(['index']);
            }
        }
        $this->sendInviteEmail($model);
        Yii::$app->session->setFlash('success', 'Invite mail sent.');
        return $this->redirect(['index']);
    }
}
