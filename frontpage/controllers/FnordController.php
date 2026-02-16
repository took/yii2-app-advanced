<?php

namespace frontpage\controllers;

use common\models\Fnord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

/**
 * FnordController displays Fnord records for end-users.
 */
class FnordController extends Controller
{
    /**
     * Lists all Fnord models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Fnord::find()->with('foos'),
            'pagination' => [
                'pageSize' => 12,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id_fnord' => SORT_DESC,
                ],
            ],
        ]);

        return $this->render('index', [
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
        $model = $this->findModel($id_fnord);

        return $this->render('view', [
            'model' => $model,
        ]);
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
