<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/9
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1;


use AppBundle\Controller\Common\CommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/v1/setting")
 */
class SettingController extends CommonController
{
    /**
     * @Route("/getCustomerServiceTel")
     */
    public function getCustomerServiceTelAction()
    {
        $setting_service = $this->get('setting_service');
        $tel = $setting_service->getValue('customer_service_tel');

        return $this->jsonSuccess('获取客服热线', $tel);
    }
}