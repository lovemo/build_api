<?php
/**
 * Created by PhpStorm.
 * User: lovemo
 * Date: 2018/9/17
 * Time: 20:22
 */

// 返回公共参数接口类
class BaseInfo
{
    public $apiID = 0;
    /**
     * @var string 接口名称
     */
    public $apiName = '';
    /**
     * @var string 接口地址
     */
    public $apiURI = '';

    public $apiProtocol = 0;

    public $apiFailureMock = '';

    public $apiSuccessMock = '';

    public $apiRequestType = 0;

    public $apiRequestParamType = '';

    public $apiStatus = 0;

    public $apiUpdateTime = '';

    public $createTime = '';

    public $groupID = 0;

    public $projectID = 0;

    public $starred = 0;

    public $removed = 0;

    public $removeTime = 0;

    public $apiNoteType = 0;

    public $apiNoteRaw = '';

    public $apiNote = '';

    public $apiRequestRaw = '';

    public $mockCode = '';


    public function __construct($baseInfo = [])
    {
        $this->createTime = date('Y-m-d H:i:s');
    }

    public function filter() {
        $baseInfo = new \stdClass();
        foreach ($this as $key => $value) {
            if (!empty($value)) {
                $baseInfo->{$key} = $value;
            }
        }
        return $baseInfo;
    }

}


