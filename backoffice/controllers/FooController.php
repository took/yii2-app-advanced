<?php

namespace backoffice\controllers;

use common\models\Foo;
use backoffice\models\FooSearch;
use Throwable;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * FooController implements the CRUD actions for Foo model.
 */
class FooController extends Controller
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
     * Lists all Foo models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new FooSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Foo model.
     * @param int $id_foo ID Foo
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id_foo): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id_foo),
        ]);
    }

    /**
     * Creates a new Foo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate(): Response|string
    {
        $model = new Foo();

        // Check for id_fnord parameter from query string
        $idFnord = $this->request->get('id_fnord');

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_foo' => $model->id_foo]);
            }
        } else {
            $model->loadDefaultValues();

            // Prepopulate id_fnord if provided and valid
            if ($idFnord !== null && \common\models\Fnord::findOne($idFnord)) {
                $model->id_fnord = $idFnord;
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Foo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_foo ID Foo
     * @return string|Response
     * @throws NotFoundHttpException|Exception if the model cannot be found
     */
    public function actionUpdate(int $id_foo): Response|string
    {
        $model = $this->findModel($id_foo);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_foo' => $model->id_foo]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Foo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_foo ID Foo
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     */
    public function actionDelete(int $id_foo): Response
    {
        $this->findModel($id_foo)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Foo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_foo ID Foo
     * @return Foo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id_foo): Foo
    {
        if (($model = Foo::findOne(['id_foo' => $id_foo])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
