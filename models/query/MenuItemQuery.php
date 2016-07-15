<?php

namespace mickey\menuItem\models\query;

/**
 * This is the ActiveQuery class for [[\common\modules\menuItem\models\MenuItem]].
 *
 * @see \common\models\MenuItem
 */
class MenuItemQuery extends \yii\db\ActiveQuery
{
    public function init()
    {
        $modelClass = $this->modelClass;
        $tableName = $modelClass::tableName();
//        $this->andWhere([$tableName.'.active' => 1]);
        $this->orderBy('position');
        parent::init();
    }

    /**
     * @inheritdoc
     * @return \common\modules\menuItem\models\MenuItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    public function root(){
        $this->where(['parent_id' => null]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return \common\modules\menuItem\models\MenuItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}