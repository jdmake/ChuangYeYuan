<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/26
// +----------------------------------------------------------------------


namespace AppBundle\Service;

/**
 * 微信小程序配置参数
 */
class MiniAppConfigService
{
    private $appid = 'wxdcab8fbe5785c92e';

    private $secret = 'e46e6a9f399e7cde173368e366b389c0';

    /**
     * @return string
     */
    public function getAppid()
    {
        return $this->appid;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }


}