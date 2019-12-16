<?php

namespace yubundle\user\api\v1\controllers;

use yii2rails\domain\data\GetParams;
use yii2rails\domain\data\Query;
use yubundle\account\domain\v2\entities\LoginEntity;
use domain\news\v1\services\NewsService;
use yii2rails\extension\web\helpers\Behavior;
use yii2lab\rest\domain\rest\Controller as Controller;
use yubundle\user\domain\v1\entities\PersonEntity;

/**
 * Class NewsController
 * @package api\v1\modules\news\controllers
 *
 * @property NewsService $service
 */
class PersonController extends Controller
{

    public $service = 'user.person';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'cors' => Behavior::cors(),
            'authenticator' => Behavior::auth(['self']),
        ];
    }

    public function actions() {
        return [
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }

    private function getQueryFromRequest($queryParams = null) {
        if($queryParams === null) {
            $queryParams = \Yii::$app->request->get();
        }
        $getParams = new GetParams();
        return $getParams->getAllParams($queryParams);
    }

    public function actionView()
    {
        $query = $this->getQueryFromRequest();
        return \App::$domain->user->person->oneSelf($query);
    }

    public function actionUpdate()
    {
        $post = \Yii::$app->request->post();
        $personEntity = new PersonEntity;
        $personEntity->load($post);
        return \App::$domain->user->person->updateSelf($personEntity);
    }

}