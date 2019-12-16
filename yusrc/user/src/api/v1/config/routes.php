<?php

$version = API_VERSION_STRING;

return [
    "GET {$version}/person" => "user/person/view",
    "PUT {$version}/person" => "user/person/update",
    "OPTIONS {$version}/person" => "user/person/options",

    "DELETE {$version}/avatar" => "user/avatar/delete",
    "OPTIONS {$version}/avatar" => "user/avatar/options",

    "POST {$version}/avatar" => "user/avatar/create",
    "OPTIONS {$version}/avatar" => "user/avatar/options",

];
