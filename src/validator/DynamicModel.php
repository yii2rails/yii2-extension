<?php

namespace yii2rails\extension\validator;

use Yii;
use yii\web\NotFoundHttpException;
use yii2rails\domain\base\Model;
use yii\helpers\ArrayHelper;

class DynamicModel extends Model
{
	
	private $attrs = [];
	private $rules = [];
	private $labels = [];
	private $scenarios = [];

	public function attributes()
    {
        return array_keys($this->attrs);
    }

    public function toArray(array $fields = null, array $expand = null, $recursive = null) {
		return $this->attrs;
	}
	
	public function __get($name) {
		return ArrayHelper::getValue($this->attrs, $name);
	}
	
	public function __set($name, $value) {
		return $this->attrs[$name] = $value;
	}
	
	public function __isset($name) {
		return isset($this->attrs[$name]);
	}
	
	public function loadRules($rules) {
		$this->rules = $rules;
	}

	public function loadData($data) {
		Yii::configure($this, $data);
	}

	public function validateData($data, $attributeNames = null) {
		$this->loadData($data);
		return $this->validate($attributeNames);
	}

	public function validateDataCollection($data, $attributeNames = null) {
		foreach($data as $item) {
			if(!$this->loadData($item, $attributeNames)) {
				return false;
			}
		}
		return true;
	}

	public function loadAttributeLabels($labels) {
		if(empty($labels)) {
			return;
		}
		foreach($labels as $name => $text) {
			if(is_array($text)) {
				$text = call_user_func_array('Yii::t', $text);
			}
			$this->labels[$name] = $text;
		}
	}
	
	public function loadScenarios($scenarios) {
		$this->scenarios = $scenarios;
	}
	
	/**
	 * @inheritdoc
	 */
	public function scenarios()
	{
		if(!empty($this->scenarios)) {
			return $this->scenarios;
		}
		return parent::scenarios();
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return $this->rules;
	}

    public function replaceRulesByType($fieldName, $ruleType, array $newRule = null)
    {
        foreach ($this->rules as $key => $value){
            if($value[0] == $fieldName && $value[1] == $ruleType){
                if(empty($newRule)){
                    unset($this->rules[$key]);
                    sort($this->rules);
                    return;
                }
                $this->rules[$key] = $newRule;
                return;
            }

        }
    }
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
    {
        return $this->labels;
    }
	
}
