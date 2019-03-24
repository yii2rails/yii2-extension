<?php

namespace yii2rails\extension\changelog\helpers;

use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yii2rails\extension\changelog\entities\CommitEntity;

class CommitHelper {

    /**
     * @param array $commits
     * @return CommitEntity[]
     */
    public static function parseArray(array $commits) : array {
        $collection = [];
        foreach ($commits as $commit) {
            $commitEntity = self::parse($commit);
            self::loadRelation($commitEntity);
            $collection[] = $commitEntity;
        }
        return $collection;
    }

    private static function loadRelation(CommitEntity $commitEntity) {
        $query = new Query;
        $query->andWhere(['name' => $commitEntity->type]);
        $query->with('type');
        try {
            $wordEntity = \App::$domain->changelog->word->one($query);
            $commitEntity->class = $wordEntity;
        } catch (NotFoundHttpException $e) {}
    }

	public static function parse(string $commit) : CommitEntity {
        $commit = trim($commit);
        preg_match('/^
            ([\w\s]+)
            (\(([^\)]+)\))?
            :
            (.+)
            /ix', $commit, $matches);
        $commitEntity = new CommitEntity;
        $commitEntity->type = $matches[1];
        $commitEntity->scope = $matches[3];
        $commitEntity->subject = $matches[4];
        $commitEntity->source_text = $commit;

        $isMatch = preg_match('/\#([0-9]+)/ix', $commitEntity->subject, $matches2);
        if($isMatch) {
            $commitEntity->task_id = $matches2[1];
        }
        return $commitEntity;
    }

}
