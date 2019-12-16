<?php

$version = API_VERSION_STRING;

return [
    "{$version}/staff-division-tree" => "staff/division/tree",
    ["class" => "yii\\rest\UrlRule", "controller" => ["{$version}/staff-division" => "staff/division"]],
    ["class" => "yii\\rest\UrlRule", "controller" => ["{$version}/staff-worker" => "staff/worker"]],
    ["class" => "yii\\rest\UrlRule", "controller" => ["{$version}/staff-post" => "staff/post"]],
];
