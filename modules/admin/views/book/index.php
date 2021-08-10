<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Book', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'isbn',
            'pageCount',
            'publishedDate',
            'thumbnailUrl:image',
            //'shortDescription',
            //'longDescription:ntext',
            //'status',
            //'authors',
            [
                'attribute'=>'categories',
                'filter'=>array("Open Source"=>"Open Source","Mobile"=>"Mobile",
                    "Software Engineering	"=>"Software Engineering	","Internet"=>"Internet",
                    "Web Development"=>"Web Development","Miscellaneous"=>"Miscellaneous",
                    "Microsoft .NET"=>"Microsoft .NET","Microsoft"=>"Microsoft",
                    "Next Generation Databases"=>"Next Generation Databases","PowerBuilder"=>"PowerBuilder",
                    "Client-Server"=>"Client-Server","Computer Graphics"=>"Computer Graphics",
                    "Object-Oriented Programming"=>"Object-Oriented Programming","Networking"=>"Networking",

                ),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>