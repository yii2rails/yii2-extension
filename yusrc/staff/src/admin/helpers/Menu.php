<?php

namespace yubundle\staff\admin\helpers;

use App;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yii2rails\extension\menu\interfaces\MenuInterface;

class Menu implements MenuInterface
{

    /**
     * TODO: Изменить иконку
     * TODO: Дописать управление подразделениями
     */

    public function toArray()
    {
        $companyId = App::$domain->account->auth->identity->company_id;
        if ($companyId != null) {
            $postQuery = new Query;
            $postQuery->andWhere([
                'entity' => 'posts_' .  $companyId,
                'owner_id' => $companyId,
            ]);

            try {
                /* @var $bookEntity \yubundle\reference\domain\entities\BookEntity */
                $bookEntity = App::$domain->reference->book->repository->one($postQuery);
            } catch (NotFoundHttpException $e) {
                throw new NotFoundHttpException(\Yii::t('staff/book', 'company_posts_not_found'));
            }

            return [
                'label' => ['staff/company', 'menu_title'],
                'module' => 'staff',
                //'icon' => 'sliders',
                'items' => [
                    [
                        'label' => ['account/main', 'menu_title'],
                        'url' => 'account/user/index',
                    ],
                    [
                        'label' => ['staff/worker', 'title'],
                        'url' => 'staff/worker?expand=division,post,person',
                    ],
                    [
                        'label' => ['staff/company', 'division_manage'],
                        'url' => 'staff/division/index?parent_id={null}'
                    ],
                    [
                        'label' => ['staff/company', 'post_manage'],
                        'url' => 'reference/item/index?reference_book_id=' . $bookEntity->id . '&parent_id={null}',
                    ],
                ],
            ];
        }
    }
}