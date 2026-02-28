<?php

namespace backoffice\controllers;

use common\models\KeyValue;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * KeyValueController manages key-value configuration pairs
 *
 */
class KeyValueController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['view-key-value-pairs', 'edit-key-value-pairs'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['edit-key-value-pairs'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'create' => ['POST'],
                    'update' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all KeyValue models with inline editing capability
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => KeyValue::find()->orderBy(['key' => SORT_ASC]),
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => new KeyValue(),
        ]);
    }

    /**
     * Creates a new KeyValue model via Ajax
     *
     * @return array
     * @throws InvalidConfigException|Exception
     */
    public function actionCreate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new KeyValue();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return [
                'success' => true,
                'message' => 'Key-value pair created successfully.',
                'model' => [
                    'id_key_value' => $model->id_key_value,
                    'key' => $model->key,
                    'value' => $model->value,
                    'created_at' => Yii::$app->formatter->asDatetime($model->created_at),
                    'updated_at' => Yii::$app->formatter->asDatetime($model->updated_at),
                    'creator_username' => $model->creator ? $model->creator->username : '',
                    'updater_username' => $model->updater ? $model->updater->username : '',
                ],
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to create key-value pair.',
            'errors' => $model->errors,
        ];
    }

    /**
     * Updates an existing KeyValue model via Ajax
     *
     * @param int $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException|Exception
     */
    public function actionUpdate(int $id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return [
                'success' => true,
                'message' => 'Value updated successfully.',
                'model' => [
                    'id_key_value' => $model->id_key_value,
                    'key' => $model->key,
                    'value' => $model->value,
                    'updated_at' => Yii::$app->formatter->asDatetime($model->updated_at),
                    'updater_username' => $model->updater ? $model->updater->username : '',
                ],
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to update value.',
            'errors' => $model->errors,
        ];
    }

    /**
     * Finds the KeyValue model based on its primary key value
     *
     * @param int $id
     * @return KeyValue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): KeyValue
    {
        if (($model = KeyValue::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested key-value pair does not exist.');
    }

    /**
     * Deletes an existing KeyValue model via Ajax
     *
     * @param int $id
     * @return array
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDelete(int $id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $this->findModel($id)->delete();

        return [
            'success' => true,
            'message' => 'Key-value pair deleted successfully.',
        ];
    }
}
