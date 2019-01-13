<?php
namespace tests\unit;

use yii2lab\extension\validator\DynamicModel;
use yii2lab\test\Test\Unit;

class ValidatorTest extends Unit
{
	public $rules = [
		['amount', 'integer'],
		['is', 'boolean'],
	];

	public function testSuccess()
	{
		$validator = new DynamicModel();
		$validator->loadRules($this->rules);
		$validator->loadData([
			'amount' => 100,
			'is' => '1',
		]);
		$this->tester->assertEquals($validator->validate(), true);
	}

	public function testFail()
	{
		$validator = new DynamicModel();
		$validator->loadRules($this->rules);
		$isValid = $validator->validateData([
			'amount' => 'qwe',
			'is' => 'qwe',
		]);
		$this->tester->assertEquals($isValid, false);
	}
	
}
