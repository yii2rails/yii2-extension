<?php

/* @var $this yii\web\View */

/* @var $dataProvider \yii\data\BaseDataProvider */

use yii\grid\GridView;
use yii\helpers\Html;
use yii2rails\extension\web\helpers\ControllerHelper;
use yii2rails\extension\web\grid\ActionColumn;

$this->title = Yii::t('reference/main', 'positions');

/** TODO: Изменить заголовок страницы */

$baseUrl = ControllerHelper::getUrl();

$columns = [
    [
        'label' => Yii::t('reference/main', 'name'),
        'format' => 'raw',
        'value' => function (\yubundle\reference\domain\entities\ItemEntity $data) {
            $bookEntity = App::$domain->reference->book->repository->oneById($data->reference_book_id);
            if ($bookEntity->levels != 1) {
                return
                    Html::a(
                        $data->value,
                        ['/reference/item/index', 'reference_book_id' => $data->reference_book_id, 'parent_id' => $data->id]
                    );
            } else {
                return Html::a(
                    $data->value,
                    ['/reference/item/view', 'id' => $data->id]
                );
            }
        },
    ],
    [
        'attribute' => 'entity',
        'label' => Yii::t('reference/main', 'entity'),
    ],
    [
        'attribute' => 'value',
        'label' => Yii::t('reference/main', 'levels'),
    ],
    [
        'class' => ActionColumn::class,
        'template' => '{update} {delete}'
    ]
];

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '{summary}{items}',
    'columns' => $columns,
]); ?>

<?php

//$bookId = Yii::$app->request->getQueryParam('reference_book_id');
$companyId = \App::$domain->account->auth->identity->company_id;
$bookEntity = App::$domain->reference->book->repository->checkExistsBookByCompanyId($companyId);

if ($companyId == $bookEntity->owner_id) {
    echo Html::a(Yii::t('action', 'create'), [
        $baseUrl . 'create',
        'reference_book_id' => Yii::$app->request->getQueryParam('reference_book_id'),
        'parent_id' => Yii::$app->request->getQueryParam('parent_id'),
    ], ['class' => 'btn btn-success']);
} ?>
