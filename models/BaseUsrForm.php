<?php

namespace nineinchnick\usr\models;

use Yii;

/**
 * BaseUsrForm class.
 * BaseUsrForm is the base class for forms extensible using behaviors, which can add attributes and rules.
 */
abstract class BaseUsrForm extends \yii\base\Model
{
    private static $_names=array();
    /**
     * @inheritdoc
     */
    private $_behaviors=[];

    /**
     * @inheritdoc
     *
     * Additionally, tracks attached behaviors to allow iterating over them.
     */
    public function attachBehavior($name, $behavior)
    {
        $this->_behaviors[$name] = $name;
        unset(self::$_names[get_class($this)]);

        return parent::attachBehavior($name, $behavior);
    }

    /**
     * @inheritdoc
     *
     * Additionally, tracks attached behaviors to allow iterating over them.
     */
    public function detachBehavior($name)
    {
        if (isset($this->_behaviors[$name]))
            unset($this->_behaviors[$name]);
        unset(self::$_names[get_class($this)]);

        return parent::detachBehavior($name);
    }

    /**
     * @inheritdoc
     *
     * Additionally, adds attributes defined in attached behaviors that extend FormModelBehavior.
     */
    public function attributes()
    {
        $className=get_class($this);
        if (!isset(self::$_names[$className])) {
            $class=new ReflectionClass(get_class($this));
            $names=array();
            foreach ($class->getProperties() as $property) {
                $name=$property->getName();
                if($property->isPublic() && !$property->isStatic())
                    $names[]=$name;
            }
            foreach ($this->_behaviors as $name=>$name) {
                if (($behavior=$this->getBehavior($name)) instanceof \nineinchnick\usr\components\FormModelBehavior)
                    $names = array_merge($names, $behavior->attributes());
            }

            return self::$_names[$className]=$names;
        } else
            return self::$_names[$className];
    }

    /**
     * Returns attribute labels defined in attached behaviors that extend FormModelBehavior.
     * @return array attribute labels (name => label)
     *               @see Model::attributeLabels()
     */
    public function getBehaviorLabels()
    {
        $labels = [];
        foreach ($this->_behaviors as $name=>$foo) {
            if (($behavior=$this->getBehavior($name)) instanceof \nineinchnick\usr\components\FormModelBehavior)
                $labels = array_merge($labels, $behavior->attributeLabels());
        }

        return $labels;
    }

    /**
     * Returns rules defined in attached behaviors that extend FormModelBehavior.
     * @return array validation rules
     *               @see Model::rules()
     */
    public function getBehaviorRules()
    {
        $rules = [];
        foreach ($this->_behaviors as $name=>$foo) {
            if (($behavior=$this->getBehavior($name)) instanceof \nineinchnick\usr\components\FormModelBehavior)
                $rules = array_merge($rules, $behavior->rules());
        }

        return $rules;
    }

    /**
     * A wrapper for inline validators from behaviors extending FormModelBehavior.
     * Set the behavior name in 'behavior' param and validator name in 'validator' param.
     * @todo port
     * @param $attribute string
     * @param $params array
     */
    public function behaviorValidator($attribute, $params)
    {
        $behavior = $params['behavior'];
        $validator = $params['validator'];
        unset($params['behavior']);
        unset($params['validator']);
        if (($behavior=$this->getBehavior($behavior)) !== null) {
            return $behavior->{$validator}($attribute, $params);
        }

        return true;
    }
}
