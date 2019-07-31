<?php
return [
    ['radio', 'status', '单选', '', ['1' => '开启', '0' => '关闭'], 1],
    ['text', 'username', '用户名', '提示', 'x'],
    ['textarea', 'summary', '多行文本', '提示'],
    ['group',
        [
            '分组1' => [
                ['radio', 'status1', '单选', '', ['1' => '开启', '0' => '关闭'], 1],
                ['text', 'text1', '单行文本', '提示', 'x'],
                ['textarea', 'textarea1', '多行文本', '提示'],
                ['checkbox', 'checkbox1', '多选', '提示', ['1' => '是', '0' => '否'], 0],
            ],
            '分组2' => [
                ['textarea', 'textarea2', '多行文本', '提示'],
                ['checkbox', 'checkbox2', '多选', '提示', ['1' => '是', '0' => '否'], 0],
            ]
        ]
    ]
];