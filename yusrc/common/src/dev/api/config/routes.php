<?php

$version = API_VERSION_STRING;

return [

    "DELETE {$version}/user-manage/<phone:\d+>" => "dev/user/delete",

];
