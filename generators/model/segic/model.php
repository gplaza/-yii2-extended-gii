<?php
/**
 * This is the template for generating the model class of a specified table.
 */
/* @var $this yii\web\View */
/* @var $generator aayaresko\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */


/**
 * Si el modelo contiene un campo id y otro nombre, se generar el metodo getOptionList
 */
$generateGetOptionListMethod = false;
$countFields = 0;

/**
 * Si existen campos de tipo fecha se agregaran las rules correspondientes mas el behavior
 */
$existDateFields = false;


echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;

/**
* This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
*
<?php foreach ($tableSchema->columns as $column): ?>
* @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php if ($column->name == 'id' || $column->name == 'nombre') { $countFields++; } ?>
<?php if ($column->dbType == 'date') { $existDateFields = true; } ?>
<?php endforeach; ?>
<?php if ($countFields == 2) {
    $generateGetOptionListMethod = true;
} ?>
<?php if (!empty($relations)): ?>
*
<?php foreach ($relations as $name => $relation): ?>
* @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
*/
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->audit): ?>

    /**
    * Audit
    */
    public function behaviors()
    {
        return [
            'bedezign\yii2\audit\AuditTrailBehavior'
        ];
    }
<?php endif; ?>

<?php if ($generator->db !== 'db'): ?>

    /**
    * @return \yii\db\Connection the database connection used by this AR class.
    */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>
    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . ",\n        " ?>];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
    * @return \yii\db\ActiveQuery
    */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($queryClassName): ?>
    <?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
    ?>

    /**
    * @inheritdoc
    * @return <?= $queryClassFullName ?> the active query used by this AR class.
    */
    public static function find()
    {
        return new <?= $queryClassFullName ?>(get_called_class());
    }
<?php endif; ?>
<?php if ($generateGetOptionListMethod): ?>

    /**
    * Generates the data suitable for list-based HTML elements
    **/
    public static function getOptionList()
    {
        return \yii\helpers\ArrayHelper::map(<?= $className ?>::find()->all(), 'id', 'nombre');
    }
<?php else: ?>

    /**
    * Generates the data suitable for list-based HTML elements
    * stub version
    **/
    public function getOptionList()
    {
        return [];
    }
<?php endif; ?>
}
