<?php
/**
 * Created by PhpStorm.
 * User: lovemo
 * Date: 2018/9/18
 * Time: 16:49
 */


// 生成参数注释接口类
class RequestInfo {

    /**
     * @var 参数id
     */
    public $paramID = 0;
    /**
     * @var 参数值
     */
    public $paramName = '';
    /**
     * @var 参数名称
     */
    public $paramKey = '';
    /**
     * @var 参数类型
     */
    public $paramType = 0;
    /**
     * @var 参数限制
     */
    public $paramLimit = '';
    /**
     * @var 参数默认值
     */
    public $paramValue = '';
    /**
     * @var 参数是否可空
     */
    public $paramNotNull = 0;
    /**
     * @var 参数值列表
     */
    public $paramValueList = [];


    public function __construct($requestInfo = [])
    {

    }

    public function filter() {
        $requestInfo = new \stdClass();
        foreach ($this as $key => $value) {
            if (!empty($value)) {
                $requestInfo->{$key} = $value;
            }
        }
        return $requestInfo;
    }
}
