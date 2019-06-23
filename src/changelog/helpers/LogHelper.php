<?php

namespace yii2rails\extension\changelog\helpers;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii2tool\vendor\domain\enums\VersionTypeEnum;
use yii2rails\domain\data\Query;
use yii2rails\extension\changelog\entities\CommitEntity;
use yii2rails\extension\changelog\entities\TypeEntity;

class LogHelper {

    public static function generate(array $collection, string $version, $date = null) : string {
        $newVersion = self::incrementVersion($collection, $version);
        $code = self::generateHeader($newVersion, $date) . PHP_EOL;
        $code .= self::generateParts($collection);
        $code = trim($code);
        return $code;
    }

    private static function incrementVersion(array $collection, string $version) {
        $weight = VersionHelper::getWeight($collection);
        $versionEntity = VersionHelper::forgeVersionEntityFromString($version);
        VersionHelper::incrementVersion($versionEntity, $weight);
        return $versionEntity->as_string;
    }

    private static function generateHeader(string $version, $date = null) : string {
        if(empty($date)) {
            $date = date('Y-m-d');
        }
        $code = '';
        $code .= '# Changelog' . PHP_EOL . PHP_EOL;
        $code .= '## [' . $version . '] - (' . $date . ')';
        return $code;
    }

    private static function generateParts(array $collection) : string {
        $code = '';
        $tree = self::collectionToTree($collection);
        /** @var TypeEntity[] $typeCollection */
        $typeCollection = \App::$domain->changelog->type->all();
        foreach ($typeCollection as $typeEmtity) {
            $subjects = ArrayHelper::getValue($tree, $typeEmtity->name);
            if(!empty($subjects)) {
                $code .= PHP_EOL . '### ' . $typeEmtity->title . PHP_EOL . PHP_EOL;
                $code .= self::generateLines($subjects);
            }
        }
        return $code;
    }

    private static function generateLines(array $subjects) {
        $code = '';
        foreach ($subjects as $subject) {
            $code .= '- ' . $subject . PHP_EOL;
        }
        return $code;
    }

    /**
     * @param CommitEntity[] $collection
     * @return array
     */
    private static function collectionToTree(array $collection) : array {
        $tree = [];
        foreach ($collection as $commitEntity) {
            $groupName = ArrayHelper::getValue($commitEntity, 'class.type.name', 'chore');
            $tree[$groupName][] = $commitEntity->subject;
        }
        return $tree;
    }

}

/*
# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/ru/0.3.0/)
and this project adheres to [Semantic Versioning](https://semver.org/).

## [Unreleased]

## [1.0.0] - 2017-06-20

### Added

- New visual identity by @tylerfortune8.
- Version navigation.

### Changed

- Start using "changelog" over "change log" since it's the common usage.
- Start versioning based on the current English version at 0.3.0 to help

### Removed

- Section about "changelog" vs "CHANGELOG".*/
