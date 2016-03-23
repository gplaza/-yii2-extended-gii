<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace aayaresko\gii\generators\model;

use aayaresko\gii\CodeFile;
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

    /**
     * @inheritdoc
     */
    public $templates = [
        'default' => "@vendor/segic/yii2-extended-gii/generators/model/segic",
        'Yii' => "@vendor/segic/yii2-extended-gii/generators/model/default",
    ];

    // TODO : implement
}
