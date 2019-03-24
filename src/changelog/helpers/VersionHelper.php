<?php

namespace yii2rails\extension\changelog\helpers;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii2module\vendor\domain\enums\VersionTypeEnum;
use yii2rails\domain\data\Query;
use yii2rails\extension\changelog\entities\CommitEntity;

class VersionHelper {

    public static function getWeight(array $collection) : string {
        $versions = self::extractWeightList($collection);
        $versionWeight = self::oneWeightFromList($versions);
        return $versionWeight;
    }

    private static function oneWeightFromList(array $versions) : string {
        if(in_array(VersionTypeEnum::MAJOR, $versions)) {
            $versionWeight = VersionTypeEnum::MAJOR;
        } elseif(in_array(VersionTypeEnum::MINOR, $versions)) {
            $versionWeight = VersionTypeEnum::MINOR;
        } else {
            $versionWeight = VersionTypeEnum::PATCH;
        }
        return $versionWeight;
    }

    private static function extractWeightList(array $collection) : array {
        $versions = [];
        foreach ($collection as $item) {
            $versions[] = ArrayHelper::getValue($item, 'class.type.version', VersionTypeEnum::PATCH);
        }
        $versions = array_unique($versions);
        $versions = array_values($versions);
        return $versions;
    }
}
