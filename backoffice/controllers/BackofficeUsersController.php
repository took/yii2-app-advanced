<?php

namespace backoffice\controllers;

use common\models\BackofficeUser;
use common\models\BackofficeUserSearch;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * BackofficeUsersController implements the CRUD actions for BackofficeUser model.
 *
 * This controller manages backoffice administrator accounts with role-based permissions.
 * It provides user invitation functionality by sending password reset emails to new
 * admin users, allowing them to set their initial password.
 *
 * Access Control:
 * - All actions require 'edit-backoffice-user' role
 */
class BackofficeUsersController extends Controller
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
                            'allow' => true,
                            'roles' => ['edit-backoffice-user'],
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
     * Lists all BackofficeUser models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new BackofficeUserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BackofficeUser model.
     *
     * @param int $id_backoffice_user
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id_backoffice_user): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id_backoffice_user),
        ]);
    }

    /**
     * Finds the BackofficeUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id_backoffice_user
     * @return BackofficeUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id_backoffice_user): BackofficeUser
    {
        if (($model = BackofficeUser::findOne(['id_backoffice_user' => $id_backoffice_user])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new BackofficeUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return string|Response
     * @throws Exception|\yii\base\Exception
     */
    public function actionCreate(): Response|string
    {
        $model = new BackofficeUser();

        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $model->password_hash = '';
                $model->generatePasswordResetToken();
                $model->generateAuthKey();
                $model->generateEmailVerificationToken();
                $roles = $post['roles'] ?? [];
                $selection = [];
                foreach (Yii::$app->params['backofficeRoles'] as $role => $description) {
                    if (in_array($role, $roles)) {
                        $selection[] = $role;
                    }
                }
                $model->roles = implode(',', $selection);
                $model->created_at = time();
                $model->updated_at = time();
                if ($model->save()) {
                    $this->sendInviteEmail($model);
                    Yii::$app->session->setFlash('success', 'Invite mail sent.');
                    return $this->redirect(['view', 'id_backoffice_user' => $model->id_backoffice_user]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        $model->status = BackofficeUser::STATUS_ACTIVE;
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Sends confirmation email to user
     *
     * @param BackofficeUser $user user model to with email should be sent
     * @return bool whether the email was sent
     */
    protected function sendInviteEmail(BackofficeUser $user): bool
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
                    'resetLink' => Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $token]),
                    'requestPasswordResetLink' => Yii::$app->urlManager->createAbsoluteUrl(['/site/request-password-reset']),
                ]
            )
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setTo($user->email)
            ->setSubject('Invite to ' . Yii::$app->name)
            ->send();
    }

    /**
     * Updates an existing BackofficeUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id_backoffice_user
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Exception
     */
    public function actionUpdate(int $id_backoffice_user): Response|string
    {
        $model = $this->findModel($id_backoffice_user);

        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $roles = $post['roles'] ?? [];
                $selection = [];
                foreach (Yii::$app->params['backofficeRoles'] as $role => $description) {
                    if (in_array($role, $roles)) {
                        $selection[] = $role;
                    }
                }
                // -- Prevent removing role "edit-backoffice-user" from own user
                if (Yii::$app->user->id == $model->id_backoffice_user) {
                    $role_edit_backoffice_user = 'edit-backoffice-user';
                    if (!in_array($role_edit_backoffice_user, $selection)) {
                        $selection[] = $role_edit_backoffice_user;
                    }
                }
                $model->roles = implode(',', $selection);
                $model->updated_at = time();
                if ($model->save(true, ['username', 'email', 'status', 'roles', 'updated_at'])) {
                    return $this->redirect(['view', 'id_backoffice_user' => $model->id_backoffice_user]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BackofficeUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id_backoffice_user
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     */
    public function actionDelete(int $id_backoffice_user): Response
    {
        $model = $this->findModel($id_backoffice_user);

        // -- Prevent user from deleting himself
        if (Yii::$app->user->id == $model->id_backoffice_user) {
            Yii::$app->session->setFlash('error', 'You cannot delete yourself.');
            return $this->redirect(['view', 'id_backoffice_user' => $model->id_backoffice_user]);
        }

        $this->findModel($id_backoffice_user)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Resends the invite link to the user.
     *
     * @param int $id_backoffice_user
     * @return Response
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionResendInviteLink(int $id_backoffice_user): Response
    {
        $model = $this->findModel($id_backoffice_user);
        if (!BackofficeUser::isPasswordResetTokenValid($model->password_reset_token)) {
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
