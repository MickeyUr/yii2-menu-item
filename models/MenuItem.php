<?php

namespace mickey\menuItem\models;

//use common\models\ActiveRecord;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
//use common\models\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\Url;
use yii\helpers\Html;
use mickey\menuItem\models\query\MenuItemQuery;

/**
 * This is the model class for table "{{%menu_item}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $uri
 * @property integer $parent_id
 * @property integer $level
 * @property integer $position
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $updated_at
 */
class MenuItem extends ActiveRecord
{
    /**
     * @inheritdoc
     */

    private $_neighbors;

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
            ],
        ];
    }

    public static function tableName()
    {
        return '{{%menu_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'uri'], 'filter', 'filter'=>'strip_tags'],
            [['parent_id', 'level', 'position'], 'integer'],
            [['name', 'uri'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('MenuItem', 'ID'),
            'name' => Yii::t('MenuItem', 'Name'),
            'uri' => Yii::t('MenuItem', 'Uri'),
            'parent_id' => Yii::t('MenuItem', 'Parent ID'),
            'level' => Yii::t('MenuItem', 'Level'),
            'position' => Yii::t('MenuItem', 'Position'),
            'created_at' => Yii::t('MenuItem', 'Created At'),
            'created_by' => Yii::t('MenuItem', 'Created By'),
            'updated_by' => Yii::t('MenuItem', 'Updated By'),
            'updated_at' => Yii::t('MenuItem', 'Updated At'),
        ];
    }

    public function getChildrenCount() {
        return $this->hasOne(MenuItem::className(), ['parent_id' => 'id'])->count();
    }

    public function getChildren() {
        return $this->hasMany(MenuItem::className(), ['parent_id' => 'id']);
    }

    public function getParent() {
        return $this->hasOne(MenuItem::className(), ['id' => 'parent_id']);
    }

    public static function hasChildren($item) {
        if($item->children) {
            $children = [];
            foreach ($item->children as $n=>$child) {
                $children[$n]['text'] = $child->name;
                $children[$n]['href'] = Url::toRoute(['/menu-item/update', 'id' => $child->id]);
                if($child->children){
                    $children[$n]['nodes'] = MenuItem::hasChildren($child);
                }
            }
            return $children;
        }
    }

    public function createTree() {
        $subMenu = MenuItem::find()->where(['parent_id' => $this->id])->all();
        foreach($subMenu as $key=>$item){
            $arr[$key]['text'] = $item->name;
            $arr[$key]['href'] = Url::toRoute(['/menu-item/admin/update', 'id' => $item->id]);
            if($item->children) {
                $arr[$key]['nodes'] = MenuItem::hasChildren($item);
            }
        };
        return $arr;
    }

    public function getUrl()
    {
        if (strpos($this->uri, 'www.') === 0 || strpos($this->uri, 'http://') === 0) {
            return $this->uri;
        }

//        $urlManager = (IS_BACKEND) ? Yii::$app->frontUrlManager : Yii::$app->urlManager;
//        if (empty($this->uri)) {
//            return $urlManager->baseUrl;
//        }

        $pars=parse_url($this->uri);

        if(empty($pars['path']) && !empty($pars['fragment'])) {
            return '#'.$pars['fragment'];
        }

        $route=empty($pars['path'])?'site/index':$pars['path'];
        $params=array();
        if(!empty($pars['query'])) {
            foreach(explode('&', $pars['query']) as $str) {
                list($key, $val)=explode('=', $str);
                $params[$key]=$val;
            }
        }
        if(!empty($pars['fragment'])) {
            $params['#']=$pars['fragment'];
        }
//dump($route);
//dump($params);
        return Url::toRoute($route);
//        return $urlManager->createUrl($route, $params);
    }

    public function getText()
    {
        return Html::a(Html::encode($this->name), ['update', 'id' => $this->id]);
    }

    public function beforeSave($insert) //TODO бажит при сохранении
    {
        if (parent::beforeSave($insert)) {
            if ($this->parent === null || $this->parent->isNewRecord) {
                $this->level = 0;
            } else {
                $this->level = $this->parent->level + 1;
            }
            if ($this->isNewRecord) {
                $query = new Query;
                $query->select(['position']);
                $query->from(self::tableName());
                $query->orderBy('position DESC');
                if ($this->parent_id) {
                    $query->where('parent_id=:parent_id');
                    $query->params([':parent_id' => $this->parent_id]);
                } else {
                    $query->where('parent_id is NULL');
                }
                $last = $query->one();
                $this->position = $last ? $last['position'] + 1 : 0;
            }
            return true;
        } else {
            return false;
        }
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            self::deleteAll('parent_id = :parent_id', [':parent_id'=>$this->id]);
            return true;
        } else {
            return false;
        }
    }

    public function getIsRooted()
    {
        return empty($this->parent_id);
    }

    public function getRooted()
    {
        $parent = $this;
        while ($parent->parent) {
            $parent = $parent->parent;
        }
        return $parent;
    }

    public function getNeighbors()
    {
        if ($this->_neighbors == null) {
            if ($this->isRooted) {
                $this->_neighbors = MenuItem::find()->root()->all();
            } else {
                $this->_neighbors = MenuItem::find()->where(['parent_id' => $this->parent_id])->all();
            }
        }
        return $this->_neighbors;
    }

    public static function find()
    {
        return new MenuItemQuery(get_called_class());
    }

}
