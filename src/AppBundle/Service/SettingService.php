<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/9
// +----------------------------------------------------------------------


namespace AppBundle\Service;


class SettingService extends AbsService
{
    /**
     * 获取配置
     * @param $key
     * @return \AppBundle\Entity\PioneerparkSetting|object|null
     */
    public function getValue($key)
    {
        $res = $this->getDoctrine()->getRepository('AppBundle:PioneerparkSetting')
            ->findOneBy([
                'settingKey' => $key
            ]);

        return $res->getSettingValue();
    }
}