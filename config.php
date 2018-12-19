<?php
/**
 * Created by PhpStorm.
 * User: lovemo
 * Date: 2018/9/18
 * Time: 11:08
 */

return [

    // markdown样式
    'markdown'   => [
        // 生成目录
        'build'   => __DIR__ . '/demo.md',
        'style'   => [
            'func' => [
                'name' => '##',
                'mark' => '####',
            ]
        ],
    ],

    // eolinker样式
    'eolinker'   => [
        // 生成目录
        'build'  => __DIR__ . '/demo.export',
    ],

    // 模块所在路径
    'path'     => __DIR__ . '/../../../',

    // 定义模块的自动生成
    'module'   =>  [
        'api'  => [
            // 定义控制器的自动生成
            [
                'controller' => 'Test.php',
                // [] 为所有
                'action'     => ['q', 'ww'],
            ]

        ],
    ],


];






