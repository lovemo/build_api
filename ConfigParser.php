<?php
/**
 * Created by PhpStorm.
 * User: lovemo
 * Date: 2018/9/18
 * Time: 15:10
 */


// 配置解析器
class ConfigParser {


    // 读取到的配置内容
    public static $configs = array();
    // 处理后的配置
    public static $configResult = array();


    /**
     * 初始化设置配置信息
     * @param string $configPath 配置文件路径
     */
    public static function init($configPath = '') {
        self::$configs = require $configPath;
        if (!isset(self::$configs['markdown']['build'])) {
            self::$configs['markdown']['build'] = __DIR__ . '/apidoc.md';
        }

        if (!isset(self::$configs['eolinker']['build'])) {
            self::$configs['eolinker']['build'] = __DIR__ . '/apidoc.export';
        }
    }

    /**
     * 处理配置信息
     * @return array 处理后的配置信息
     */
    public static function config() {
        foreach (self::$configs['module'] as $module => $configItem) {
            self::$configResult[] = [
                'moduleName'  => $module,
                'modulePath'  => self::$configs['path'] . $module,
                'controllers' => $configItem,
            ];

        }
        return self::$configResult;
    }

}
