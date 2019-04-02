<?php

namespace yii2rails\extension\changelog\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class VersionEntity
 * 
 * @package yii2rails\extension\changelog\entities
 * 
 * @property $major
 * @property $minor
 * @property $patch
 * @property $as_string
 */
class VersionEntity extends BaseEntity {

	protected $major = 0;
	protected $minor = 0;
	protected $patch = 0;
    protected $as_string;

    public function fieldType() {
        return [
            'major' => 'integer',
            'minor' => 'integer',
            'patch' => 'integer',
        ];
    }

    public function getAsString() {
        return $this->major . DOT . $this->minor . DOT . $this->patch;
    }

}
