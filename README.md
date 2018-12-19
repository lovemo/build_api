# build_api
根据php文件相关代码注释，自动生成对应的接口文档和eolinker支持的格式文档，可导入到eolinker网站中

# core code
```php
#!/usr/bin/env php
<?php
/**
 * 根据php文件相关代码注释，自动生成对应的接口文档和eolinker支持的格式文档，可导入到eolinker网站中
 */
header('content-type:text/html;charset=utf-8');

require_once '../yw_base.php';

require_once 'Parser.php';
require_once 'Builder.php';
require_once 'ConfigParser.php';


$configPath = __DIR__ . '/' . 'config.php';

// 解析配置
ConfigParser::init($configPath);
$configResult = ConfigParser::config();

// 解析注释内容
Parser::init($configResult);

// 生成文档
Builder::init(Parser::$parseResult);

// 生成markdown格式
// Builder::markdown();

// 生成eolinker格式
// Builder::eolinker();

```

# config file
```php
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
```
