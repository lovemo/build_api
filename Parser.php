<?php
/**
 * Created by PhpStorm.
 * User: lovemo
 * Date: 2018/9/14
 * Time: 14:53
 */


class Parser {

    // 解析文件资源对象
    private static $fileResource;

    // 解析文件路径
    private static $parsePath;

    // 解析结果集
    public  static $parseResult = [];

    // 方法结果集
    private static $actionArray = [];


    /**
     * 初始化解析配置信息
     * @param $configResult 配置信息
     */
    public static function init($configResult = []) {

        foreach ($configResult as $moduleKey => $moduleItem) {
            self::$parsePath = $moduleItem['modulePath'] . '/' . 'controller' . '/';
            foreach ($moduleItem['controllers'] as $controllerItem) {
                self::$parsePath .= $controllerItem['controller'];
                self::$fileResource = fopen(self::$parsePath,'r');
                self::$actionArray = $controllerItem['action'];
                self::parse();
                fclose(self::$fileResource);
            }
        }
    }

    /**
     * 解析配置文件内容
     */
    public static function parse() {

        $markPattern = '/.*\/\*\*/';
        $content = '';
        $classPattern   = '/class/';
        $findClass = false;

        while ( !feof(self::$fileResource)) {
            $line = fgets(self::$fileResource);
            if (!preg_match('/\/\//', $line)) {
                $line = str_replace(PHP_EOL, '', $line);
            }

            if (empty($line)) {
                continue;
            }
            if ($findClass) {
                if (preg_match($markPattern, $line)) {
                    if (!empty($content)) {
                        $buildParse = self::buildParse($content);
                        if (!empty($buildParse)) {
                            self::$parseResult[] = $buildParse;
                        }
                    }
                    $content  = '';
                    $content .= "/**";
                    continue;
                }
                $content .= $line;
            }

            if (preg_match($classPattern, $line)) {
                $findClass = true;
            }
        }
        self::$parseResult[] = self::buildParse($content);

    }

    /**
     * 解析注释
     * @param $content
     * @return array
     */
    private static function parseAnnotation($content) {
        $pattern = '/^\/\*\*(.*?)\*\/.*?/';
        $result = array();
        preg_match($pattern, $content,$result);
        if (empty($result)) {
            return $result;
        }

        $result = explode('*',$result[1]);

        foreach ($result as $key => $value) {
            $value = str_replace(array(" ", "\t"), "", $value);
            $result[$key] = $value;

            if (empty($value)){
                unset($result[$key]);
            }
        }
        return $result;
    }

    /**
     * 解析方法
     * @param $content
     * @return string
     */
    private static function parseFunc($content) {

        $condition = '';
        if (!empty(self::$actionArray)) {
            $condition = implode('|', self::$actionArray);
        }

        $pattern = "/function\s($condition.*?)\(\)/";
        $result = array();

        preg_match($pattern, $content,$result);
        if (empty($result)) {
            return '';
        }

        return $result[1];
    }

    /**
     * 解析方法参数
     * @param $content
     * @return array
     */
    private static function parseParameter($content) {
        $pattern  = "/\/\/\s(.*?)[\n|\r\n]?.*?param\('(.*?)'\)/i";
        $result      = array();
        preg_match_all($pattern, $content,$result);
        if (empty($result)) {
            return $result;
        }

        $result = array_filter($result);
        $params = [];

        foreach ($result as $key => $val) {
            if (empty($val) || empty($result[2])) {
                continue;
            }

            foreach ($result[2] as $paramKey => $paramValue) {
                $params[] = [
                    'mark'  =>  $result[1][$paramKey],
                    'param' =>  $paramValue,
                ];
            }
        }
        return $params;
    }

    /**
     * 解析控制器名
     * @return string
     */
    private static function parseController() {
        $pattern = '/class\s(.*)\sextends/';
        $fileResource = fopen(self::$parsePath,'r');
        $result = array();
        while ( !feof($fileResource)) {
            $line = fgets($fileResource);
            if (preg_match($pattern, $line,$result)) {
                break;
            }
        }
        fclose($fileResource);

        if (empty($result)) {
            return '';
        }
        return $result[1];
    }

    /**
     * 解析模块名
     * @return string
     */
    private static function parseModuleName() {
        $pattern = '/namespace.*?\\\(.*)\\\.*?controller/';
        $fileResource = fopen(self::$parsePath,'r');
        $result = array();
        while ( !feof($fileResource)) {
            $line = fgets($fileResource);
            if (preg_match($pattern, $line,$result)) {
                break;
            }
        }
        fclose($fileResource);
        if (empty($result)) {
            return '';
        }
        return $result[1];
    }

    /**
     * 构建解析结果
     * @param $content
     * @return array
     */
    private static function buildParse($content) {
        $func       = self::parseFunc($content);
        if (!empty($func)) {
            $annotation = self::parseAnnotation($content);
            $parameter  = self::parseParameter($content);
            $controllerName = self::parseController();
            $applicationName = self::parseModuleName();
            return [
                'mark'        => $annotation,
                'func'        => $func,
                'params'      => $parameter,
                'controller'  => $controllerName,
                'module'      => $applicationName,
            ];

        }
    }

}