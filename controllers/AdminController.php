<?php

namespace mickey\menuItem\controllers;

use yii\web\Controller;

//use yii\web\Controller;
use Yii;
use mickey\menuItem\models\MenuItem;
use mickey\menuItem\models\search\MenuItemSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
/**
 * Default controller for the `menuItem` module
 */
class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions' => ['view', 'search', 'index', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }
    /**
     * Renders the index view for the module
     * @return string
     */

    public function actionTest(){
        echo ('1');
    }
    public function actionIndex()
    {
//        $searchModel = new MenuItemSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
            'menuItems' => MenuItem::find()->root()->all(),
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate($root_id=null)
    {
        $model = new MenuItem();
        if (isset($root_id)){
            $rooted = $this->findModel($root_id);
        }

        if ($model->load(Yii::$app->request->post())) {

            if (empty($model->parent_id))
                $model->parent_id = $rooted->id;

            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'message' => Yii::t('MenuItem','Пункт меню добавлен'),
                ]);
                return $this->redirect(['update', 'id' => $model->id]);
            } else {
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'success',
                    'message' => Yii::t('MenuItem','Пункт меню не добавлен'),
                ]);
            }
        }

        return $this->render('create', array(
            'model' => $model,
            'rooted' => $rooted,
        ));

    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $rooted = $model->getRooted();

        if ($model->load(Yii::$app->request->post())) {

            if (empty($model->parent_id))
                $model->parent_id = $rooted->id;

            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'message' => Yii::t('MenuItem','Изменения сохранены'),
                ]);
                return $this->redirect(['update', 'id' => $model->id]);
            } else {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'message' => Yii::t('MenuItem','Изменения не сохранены'),
                ]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'rooted' => $rooted,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = MenuItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSaveOrder()
    {
        if (isset($_POST['MenuItem_id'])) {
            $menuItems = MenuItem::find(['id' => $_POST['MenuItem_id']])->all();
            foreach ($menuItems as $menuItem) {
                $menuItem->position = array_search($menuItem->id, $_POST['MenuItem_id']);
                $menuItem->save();
            }
        } else
            throw new \yii\web\HttpException(404, 'Invalid request. Please do not repeat this request again.');
    }
}
