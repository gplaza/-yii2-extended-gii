<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator aayaresko\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";

?>

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\bootstrap4\ActiveForm; */
?>
<div class="card card-primary">
    <?= "<?php " ?>$form = ActiveForm::begin(); ?>
    <div class="card-body">
    <?php foreach ($generator->getColumnNames() as $attribute) {
        if (in_array($attribute, $safeAttributes)) {
            echo "  <div class=\"row\">\n";
            echo "      <div class=\"col-md-4\">\n";
            echo "          <?php echo " . $generator->generateActiveField($attribute) . " ?>\n";
            echo "      </div>\n";
            echo "  </div>\n";
        }
    } ?>
    </div>
    <div class="card-footer">
        <?= "<?= " ?>Html::submitButton(($model->isNewRecord ? '<i class="fas fa-plus-circle"></i> Crear' : '<i class="fas fa-save"></i> Actualizar'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?= "<?php " ?>ActiveForm::end(); ?>
</div>
