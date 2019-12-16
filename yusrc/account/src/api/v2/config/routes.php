<?php

$version = API_VERSION_STRING;

return [
	"GET {$version}/auth" => "account/auth/info",
	"POST {$version}/auth" => "account/auth/login",
    "OPTIONS {$version}/auth" => "account/auth/options",

    "GET {$version}/auth-cookie" => "account/auth-cookie/info",
    "POST {$version}/auth-cookie" => "account/auth-cookie/login",
    "OPTIONS {$version}/auth-cookie" => "account/auth-cookie/options",
    "DELETE {$version}/auth-cookie" => "account/auth-cookie/logout",


    "POST {$version}/load-ldap" => "account/auth/load-ldap",

	"{$version}/registration/<action>" => "account/registration/<action>",

	"{$version}/restore-password/<action>" => "account/restore-password/<action>",

	"{$version}/security/<action>" => "account/security/<action>",

	["class" => "yii\\rest\UrlRule", "controller" => ["{$version}/user" => "account/user"]],
    ["class" => "yii\\rest\UrlRule", "controller" => ["{$version}/identity" => "account/identity"]],
];
