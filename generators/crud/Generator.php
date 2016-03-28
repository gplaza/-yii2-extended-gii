<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace aayaresko\gii\generators\crud;

use aayaresko\gii\CodeFile;
use aayaresko\gii\FormItemsFacade;
use yii\db\ActiveRecord;
use yii\db\Schema;
use yii\helpers\Inflector;
use yii\web\Controller;
use Yii;

/**
 * Generates CRUD
 *
 * @property array $columnNames Model column names. This property is read-only.
 * @property string $controllerID The controller ID (without the module ID prefix). This property is
 * read-only.
 * @property array $searchAttributes Searchable attributes. This property is read-only.
 * @property boolean|\yii\db\TableSchema $tableSchema This property is read-only.
 * @property string $viewPath The controller view path. This property is read-only.
 *
 * @author Andrey Yaresko <aayaresko@gmail.com>
 * @since 2.0
 */
class Generator extends DefaultGenerator {

    /** @var FormItemsFacade $items_generator */
    private $items_generator;

    /**
     * @inheritdoc
     */
    public $templates = [
        'default' => "@vendor/segic/yii2-extended-gii/generators/crud/segic",
        'Yii' => "@vendor/segic/yii2-extended-gii/generators/crud/default",
    ];

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->items_generator = new FormItemsFacade();
    }

    /**
     * Generates code for active field
     * @param string $attribute
     * @return string
     */
    public function generateActiveField($attribute) {

        $items_generator = $this->items_generator;
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute)) {
                return $items_generator::generateField($attribute, null, $this->templateType, 'passwordInput', null);
            } elseif (preg_match('/^(up_machine)$/i', $attribute)) {
                return $items_generator::generateField($attribute, '\trntv\filekit\widget\Upload', $this->templateType, 'widget', ['url' => ['upload'], 'sortable' => true, 'maxFileSize' => 10 * 1024 * 1024, 'maxNumberOfFiles' => 3,]);
            } else {
                return $items_generator::generateField($attribute, null, $this->templateType, null, null);
            }
        }
        $column = $tableSchema->columns[$attribute];
        if ($column->phpType === 'boolean') {
            return $items_generator::generateField($attribute, null, $this->templateType, 'checkbox', null);
        } elseif ($column->type === 'text') {
            return $items_generator::generateField($attribute, null, $this->templateType, 'textarea', ['rows' => 6]);
        } elseif ($column->dbType === 'date') {
            return $items_generator::generateField($attribute, 'kartik\datecontrol\DateControl', $this->templateType, 'widget', ['type' => 'date']);
        } elseif ($column->dbType === 'datetime') {
            return $items_generator::generateField($attribute, 'kartik\datecontrol\DateControl', $this->templateType, 'widget', ['type' => 'datetime']);
        } else {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                $input = 'passwordInput';
            } else {
                $input = 'textInput';
            }
            if (is_array($column->enumValues) && count($column->enumValues) > 0) {
                $dropDownOptions = [];
                foreach ($column->enumValues as $enumValue) {
                    $dropDownOptions[$enumValue] = Inflector::humanize($enumValue);
                }
                return $items_generator::generateField($attribute, $dropDownOptions, $this->templateType, 'dropDownList', ['prompt' => '']);
            } elseif ($column->phpType !== 'string' || $column->size === null) {
                return $items_generator::generateField($attribute, null, $this->templateType, $input, null);
            } else {
                return $items_generator::generateField($attribute, null, $this->templateType, $input, ['maxlength' => true]);
            }
        }
    }

    /**
     * Generates code for active search field
     * @param string $attribute
     * @return string
     */
    public function generateActiveSearchField($attribute) {
        $items_generator = $this->items_generator;
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false) {
            return $items_generator::generateField($attribute, null, $this->templateType, null, null);
        }
        $column = $tableSchema->columns[$attribute];
        if ($column->phpType === 'boolean') {
            return $items_generator::generateField($attribute, null, $this->templateType, 'checkbox', null);
        } else {
            return $items_generator::generateField($attribute, null, $this->templateType, null, null);
        }
    }

    /**
     * /**
     * Generates URL parameters
     * @return string
     * @param int $templateType
     */
    public function generateUrlParams($templateType = self::TEMPLATE_TYPE_PHP) {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        $pks = $class::primaryKey();
        if (count($pks) === 1) {
            if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                return "'id' => (string)\$model->{$pks[0]}";
            } else {
                return "'id' => \$model->{$pks[0]}";
            }
        } else {
            $params = [];
            foreach ($pks as $pk) {
                if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                    $params[] = "'{$pk}' => (string)\$model->{$pk}";
                } else {
                    $params[] = "'{$pk}' => \$model->{$pk}";
                }
            }

            return implode(', ', $params);
        }
    }

}
