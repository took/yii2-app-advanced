<?php

namespace backoffice\controllers;

use common\models\Fnord;
use backoffice\models\FnordSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * FnordController implements the CRUD actions for Fnord model.
 */
class FnordController extends Controller
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
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Fnord models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new FnordSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Fnord model.
     * @param int $id_fnord ID Fnord
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id_fnord): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id_fnord),
        ]);
    }

    /**
     * Creates a new Fnord model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Fnord();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_fnord' => $model->id_fnord]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Fnord model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_fnord ID Fnord
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id_fnord)
    {
        $model = $this->findModel($id_fnord);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_fnord' => $model->id_fnord]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Fnord model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_fnord ID Fnord
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id_fnord): \yii\web\Response
    {
        $this->findModel($id_fnord)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Fnord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_fnord ID Fnord
     * @return Fnord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id_fnord): Fnord
    {
        if (($model = Fnord::findOne(['id_fnord' => $id_fnord])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
