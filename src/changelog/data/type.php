<?php

return [
    [
        'name' => 'breaking_change',
        'title' => 'Breaking change',
        'version' => 'major',
        'sort' => 1,
        'desctiption' => 'нарушений обратной совместимости',
    ],
    [
        'name' => 'feature',
        'title' => 'Features',
        'version' => 'minor',
        'sort' => 2,
        'desctiption' => 'добавление нового функционала',
    ],
    [
        'name' => 'performance',
        'title' => 'Performance',
        'version' => 'minor',
        'sort' => 3,
        'desctiption' => 'изменения в коде, улучшающие произодительность',
    ],
    [
        'name' => 'refactor',
        'title' => 'Refactor',
        'version' => 'minor',
        'sort' => 4,
        'desctiption' => 'изменения в коде, не исправляющие ошибок и не добавляющие новый функционал',
    ],
    [
        'name' => 'deprecated',
        'title' => 'Deprecated',
        'version' => 'patch',
        'sort' => 5,
        'desctiption' => 'Пометка устаревания кода (при смене мажорной версии, все устаревшее удаляется)',
    ],
    [
        'name' => 'fix',
        'title' => 'Bug fixes',
        'version' => 'patch',
        'sort' => 10,
        'desctiption' => 'исправление какой-либо программной ошибки',
    ],
    [
        'name' => 'Documentation',
        'title' => '',
        'version' => 'patch',
        'sort' => 6,
        'desctiption' => 'изменения в документации',
    ],
    [
        'name' => 'style',
        'title' => 'Code style',
        'version' => 'patch',
        'sort' => 7,
        'desctiption' => 'изменения в коде, не затрагивающие его содержание (форматирование, добавление точек с запятой и т.д.)',
    ],
    [
        'name' => 'test',
        'title' => 'Test',
        'version' => 'patch',
        'sort' => 8,
        'desctiption' => 'добавление новых тестов или исправление существующих',
    ],
    [
        'name' => 'chore',
        'title' => 'Other',
        'version' => 'patch',
        'sort' => 9,
        'desctiption' => 'любые другие изменения, не затрагивающие код',
    ],
];
