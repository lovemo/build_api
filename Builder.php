<?php
/**
 * Created by PhpStorm.
 * User: lovemo
 * Date: 2018/9/14
 * Time: 15:12
 */

require 'BaseInfo.php';
require 'RequestInfo.php';

class Builder {


    private static $parseResult;
    
    private static $tableHeadDivisionLine = '';
    private static $markDownStyle = [];
    private static $markDownDefaultStyle = [
        'func' => [
            'name' => '##',
            'mark' => '####',
        ]
    ];

    /**
     * 初始化配置信息
     * @param $parseResArray
     */
    public static function init($parseResArray) {

        self::$parseResult = $parseResArray;

        self::$markDownStyle = array_merge(self::$markDownDefaultStyle, ConfigParser::$configs['markdown']['style']);

        self::$tableHeadDivisionLine =  YW_VERTICAL_LINE . YW_BLANK_CHAR . ':---' . YW_BLANK_CHAR .
                                        YW_VERTICAL_LINE . YW_BLANK_CHAR . ':---' . YW_BLANK_CHAR .
                                        YW_VERTICAL_LINE . YW_BLANK_CHAR . ':---' . YW_BLANK_CHAR .
                                        YW_VERTICAL_LINE;
    }

    /**
     * 生成markdown形式
     */
    public static function markdown() {

        $body = '';
        // 参数注解头部
        $head =  YW_VERTICAL_LINE . YW_BLANK_CHAR . '英文标识' . YW_BLANK_CHAR .
                 YW_VERTICAL_LINE . YW_BLANK_CHAR . '中文标识' . YW_BLANK_CHAR .
                 YW_VERTICAL_LINE . YW_BLANK_CHAR . '备注';

        foreach (Parser::$parseResult as $key => $value) {
            // 接口名称
            $body .= self::$markDownStyle['func']['name'] . YW_BLANK_CHAR .
                    '接口:' . YW_BLANK_CHAR .
                    $value['module']  . '/' .
                    $value['controller']  . '/' .
                    $value['func'] . YW_DOUBLE_NEWLINE;
            // 接口注解
            $index = 0;
            foreach ($value['mark'] as $funcMark) {
                $body .= self::$markDownStyle['func']['mark'];
                if ($index == 0) {
                    $body .= YW_BLANK_CHAR . '接口说明:' . YW_BLANK_CHAR;
                }
                $body .= ($index == 0 ? '' : YW_BLANK_CHAR) . $funcMark . YW_DOUBLE_NEWLINE;
                $index++;
            }

            if (!empty($value['params'])) {
                // 参数注解头部
                $body .= $head . YW_NEWLINE .
                        self::$tableHeadDivisionLine .
                        YW_NEWLINE;
                // 参数注解
                foreach ($value['params'] as $paramItem) {
                    $body .= YW_VERTICAL_LINE . YW_BLANK_CHAR . $paramItem['param'] . YW_BLANK_CHAR .
                             YW_VERTICAL_LINE . YW_BLANK_CHAR . $paramItem['mark'] . YW_BLANK_CHAR .
                             YW_VERTICAL_LINE . YW_BLANK_CHAR . $paramItem['mark'] . YW_BLANK_CHAR .
                             YW_NEWLINE;
                }
            }

            $body .= YW_NEWLINE;
        }

        file_put_contents(ConfigParser::$configs['markdown']['build'], $body);
    }

    /**
     * 生成eolinker形式
     */
    public static function eolinker() {

        $eolinkerRes = [];
        foreach (self::$parseResult as $key => $value) {
            $eolinkerObj = new \stdClass();

            $baseInfo = new BaseInfo();
            $baseInfo->apiURI = $value['module']  . '/' .
                                $value['controller']  . '/' .
                                $value['func'];
            $baseInfo->apiName = implode(',', $value['mark']);
            $eolinkerObj->baseInfo = $baseInfo;

            $requestInfos = [];
            foreach ($value['params'] as $paramItem) {
                $requestInfo = new RequestInfo();
                $requestInfo->paramKey = $paramItem['param'];
                $requestInfo->paramName = $paramItem['mark'];
                $requestInfos[] = $requestInfo;
            }
            $eolinkerObj->requestInfo = $requestInfos;
            $eolinkerRes[] = $eolinkerObj;
        }

        file_put_contents(ConfigParser::$configs['eolinker']['build'], json_encode($eolinkerRes));
    }
}