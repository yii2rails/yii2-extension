<?php

namespace yii2rails\extension\changelog\helpers;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii2tool\vendor\domain\enums\VersionTypeEnum;
use yii2rails\domain\data\Query;
use yii2rails\extension\changelog\entities\CommitEntity;
use yii2rails\extension\changelog\entities\VersionEntity;

class VersionHelper {

    public static function incrementVersion(VersionEntity $versionEntity, string $versionWeight) {
        $versionEntity->{$versionWeight}++;
    }

    public static function forgeVersionEntityFromString(string $version) : VersionEntity {
        preg_match('/(\d+)\.(\d+)\.(\d+)/i', $version, $matches);
        $versionEntity = new VersionEntity;
        //$versionEntity->source = $oldVersion;
        $versionEntity->major = $matches[1];
        $versionEntity->minor = $matches[2];
        $versionEntity->patch = $matches[3];
        return $versionEntity;
    }

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
