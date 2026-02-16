<?php

namespace backoffice\controllers;

use backoffice\models\LoginForm;
use backoffice\models\PasswordResetRequestForm;
use backoffice\models\ResetPasswordForm;
use common\models\BackofficeUser;
use common\models\KeyValue;
use common\models\User;
use Yii;
use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return ['access' => ['class' => AccessControl::class, 'rules' => [['actions' => ['login', 'error', 'request-password-reset', 'reset-password', // 'verify-email',
            // 'resend-verification-email',
        ], 'allow' => true,], ['actions' => ['logout', 'index'], 'allow' => true, 'roles' => ['@'],],],], 'verbs' => ['class' => VerbFilter::class, 'actions' => ['logout' => ['post'],],],];
    }

    /**
     * Displays error page
     * Returns JSON for Ajax requests, HTML for normal requests
     *
     * @return array|string
     */
    public function actionError(): array|string
    {
        $exception = Yii::$app->errorHandler->exception;

        if ($exception !== null) {
            // Handle Ajax requests with JSON response
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                $statusCode = $exception->statusCode ?? 500;
                Yii::$app->response->statusCode = $statusCode;

                // Customize message based on error type
                $message = $exception->getMessage();
                if ($statusCode === 403) {
                    $message = 'Permission denied. You do not have access to perform this action.';
                }

                return ['success' => false, 'message' => $message, 'errors' => [],];
            }

            // Normal HTML error page
            return $this->render('error', ['exception' => $exception]);
        }

        return $this->render('error', ['exception' => null]);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        // Get current user's roles
        $currentUser = Yii::$app->user->identity;
        $roles = !empty($currentUser->roles) ? explode(',', $currentUser->roles) : [];

        // Count frontpage users by status
        $frontpageUsers = ['active' => User::find()->where(['status' => User::STATUS_ACTIVE])->count(), 'inactive' => User::find()->where(['status' => User::STATUS_INACTIVE])->count(), 'deleted' => User::find()->where(['status' => User::STATUS_DELETED])->count(),];

        // Count backoffice users by status
        $backofficeUsers = ['active' => BackofficeUser::find()->where(['status' => BackofficeUser::STATUS_ACTIVE])->count(), 'inactive' => BackofficeUser::find()->where(['status' => BackofficeUser::STATUS_INACTIVE])->count(), 'deleted' => BackofficeUser::find()->where(['status' => BackofficeUser::STATUS_DELETED])->count(),];

        // Count key-value pairs
        $keyValueCount = KeyValue::find()->count();

        return $this->render('index', ['roles' => $roles, 'backofficeRoles' => Yii::$app->params['backofficeRoles'], 'frontpageUsers' => $frontpageUsers, 'backofficeUsers' => $backofficeUsers, 'keyValueCount' => $keyValueCount,]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', ['model' => $model,]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Requests password reset.
     *
     * @return string|Response
     * @throws Exception
     */
    public function actionRequestPasswordReset(): string|Response
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->redirect('/site/login');
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', ['model' => $model,]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return string|Response
     * @throws BadRequestHttpException
     */
    public function actionResetPassword(string $token): string|Response
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', ['model' => $model,]);
    }
}
