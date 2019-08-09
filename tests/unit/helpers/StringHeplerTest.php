<?php

namespace tests\unit\helpers;

use yii2rails\domain\helpers\DomainHelper;
use yii2rails\extension\common\enums\RegexpPatternEnum;
use yii2rails\extension\common\helpers\StringHelper;
use yii2rails\extension\git\domain\entities\BranchEntity;
use yii2rails\extension\git\domain\entities\GitEntity;
use yii2rails\extension\git\domain\entities\RemoteEntity;
use yii2tool\test\Test\Unit;

class StringHeplerTest extends Unit {
	
	const PACKAGE = 'yii2rails/yii2-extension';

	public function testFill() {
        $value = '1234';
		$value = StringHelper::fill($value, 8, '0');
		$this->tester->assertStringLength(8, $value);
	}

    public function testGenUuid() {
        $value = StringHelper::genUuid();
        $this->tester->assertRegExp(RegexpPatternEnum::UUID_REQUIRED, $value);
    }

    public function testGenerateRandomString() {
        $value = StringHelper::generateRandomString();
        $this->tester->assertStringLength(8, $value);

        $value2 = StringHelper::generateRandomString();
        $this->tester->assertNotEquals($value, $value2);

        $value = StringHelper::generateRandomString(18);
        $this->tester->assertStringLength(18, $value);

        for ($i = 0; $i < 20; $i++) {
            $value = StringHelper::generateRandomString(18, 'num');
            $this->tester->assertStringLength(18, $value);
            $this->tester->assertRegExp(RegexpPatternEnum::NUM_REQUIRED, $value);
        }

        for ($i = 0; $i < 20; $i++) {
            $value = StringHelper::generateRandomString(18, 'lower');
            $this->tester->assertStringLength(18, $value);
            $this->tester->assertRegExp('#^[a-z]+$#', $value);
        }

        for ($i = 0; $i < 20; $i++) {
            $value = StringHelper::generateRandomString(18, 'upper');
            $this->tester->assertStringLength(18, $value);
            $this->tester->assertRegExp('#^[A-Z]+$#', $value);
        }

        for ($i = 0; $i < 20; $i++) {
            $value = StringHelper::generateRandomString(18, 'char');
            $this->tester->assertStringLength(18, $value);
            $this->tester->assertRegExp('#^[[:graph:]]+$#', $value);
        }
    }

}
