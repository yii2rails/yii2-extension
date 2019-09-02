<?php

namespace yii2rails\extension\encrypt\libs;

use yii2rails\extension\common\traits\classAttribute\MagicSetTrait;
use yii2rails\extension\encrypt\entities\JwtProfileEntity;
use yii2rails\extension\encrypt\helpers\ConfigProfileHelper;
use yii2rails\extension\psr\container\BaseContainer;

class ProfileContainer extends BaseContainer {

    use MagicSetTrait;

    public function setProfiles($profiles) {

        $this->setDefinitions($profiles);
    }

    protected function prepareDefinition($component) {
        $component = parent::prepareDefinition($component);
        $component['class'] = JwtProfileEntity::class;
        $component = ConfigProfileHelper::prepareDefinition($component);
        return $component;
    }

}
