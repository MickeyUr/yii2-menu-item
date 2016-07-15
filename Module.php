<?php

namespace mickey\menuItem;
use yii\base\Module as BaseModule;
/**
 * menuItem module definition class
 */
class Module extends BaseModule
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'mickey\menuItem\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
