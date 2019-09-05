<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/12
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1\Task;


use AppBundle\Controller\v1\CommentController;
use http\Env\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/v1/task")
 */
class AppointmentValidityCheckController extends CommentController
{
    /**
     * 过期预约状态清理
     * @Route("/expiredHandle")
     */
    public function expiredHandleAction()
    {
        $appointmentrecord_service = $this->get('appointmentrecord_service');

        // 已过期的全部预约设置为已失效
        $appointmentrecord_service->setTodayExpiredRecordInvalid();

        return new \Symfony\Component\HttpFoundation\Response('success');
    }
}