<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator aayaresko\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\web\View;
use <?= $generator->indexWidgetType === 'grid' ? "kartik\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
<?= $generator->indexWidgetType === 'grid' ? "use kartik\\export\\ExportMenu;" : "" ?>;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">

    <?php if (!empty($generator->searchModelClass)): ?>
        <?= "    <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php endif; ?>

    <p>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('<i class="fas fa-plus-circle"></i> Crear ' . Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>, ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php if ($generator->indexWidgetType === 'grid'): ?>

        <?= "<?= " ?>ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        <?php
        foreach ($generator->getColumnNames() as $name) {
            echo "            '" . $name . "',\n";
        }
        ?>
        ]]); ?>

        <?php if(!empty($generator->searchModelClass)): ?>
        
        <?= "<?php " ?>$this->registerJs("$('.search-button').click(function(){ $('.search-form').toggle(); return false; });", View::POS_READY, 'searchBaseScriptBoilerplate');?>

        <div class="btn-group" role="group">
            <button id="w3-cols" class="btn btn-default dropdown-toggle search-button">
                <i class="glyphicon glyphicon-search"></i>
            </button>
        </div>

        <div class="row">
            <div class="search-form col-lg-6" style="display:none">
                <?= "<?php " ?>echo $this->render('_search', ['model' => $searchModel]); ?>
            </div>
        </div>
        
        <?php endif; ?>
        
        <?= "<?= " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
        <?php
        $count = 0;
        if (($tableSchema = $generator->getTableSchema()) === false) {
            foreach ($generator->getColumnNames() as $name) {
                if (++$count < 6) {
                    echo "            '" . $name . "',\n";
                } else {
                    echo "            // '" . $name . "',\n";
                }
            }
        } else {
            foreach ($tableSchema->columns as $column) {
                $format = $generator->generateColumnFormat($column);
                if (++$count < 6) {
                    echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                } else {
                    echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                }
            }
        }
        ?>
        ['class' => 'yii\grid\ActionColumn'],
        ],
        ]); ?>
    <?php else: ?>
        <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
        return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
        ]) ?>
    <?php endif; ?>

</div>
