<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/6
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1;


use AppBundle\Controller\Common\CommonController;
use AppBundle\Entity\PioneerparkRentOrder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/v1/order")
 */
class OrderController extends CommonController
{
    /**
     * 获取订单分页列表
     * @Route("/getOrderPageList", name="order_getOrderPageList")
     */
    public function getOrderPageListAction()
    {
        $type = $this->request()->get('type', 1);
        $page = $this->request()->get('page', 0);
        $filters = json_decode($this->request()->get('filters'), true);
        $status = $filters[0]['status'];

        $order_service = $this->get('order_service');
        $uid = $this->getUserSession('uid');

        if ($type == 1) {
            $res = $order_service->getAppointmentOrderPageList($uid, $status, $page, 10);
        } else if ($type == 2) {
            $res = $order_service->getRentOrderPageList($uid, $status, $page, 10);
        }

        return $this->jsonSuccess('获取订单列表', $res);
    }

    /**
     * @Route("/createRentOrder")
     */
    public function createRentOrderAction()
    {
        $record_id = $this->getJsonParameter('id');
        $rent_service = $this->get('rent_service');
        $record = $rent_service->findById($record_id);
        if (!$record) {
            return $this->jsonError(1, '交租记录不存在');
        }
        // 创建订单
        $orderNo = 'RENT' . date('YmdHis') . time();
        $order_service = $this->get('order_service');
        $order = new PioneerparkRentOrder();
        $order->setUid($this->getUserSession('uid'));
        $order->setOrderNo($orderNo);
        $order->setRecordId($record_id);
        $order->setTotal($record->getTotal());
        $order->setStatus(0);
        $order->setPayStatus(0);
        $order->setCreateAt(new \DateTime());
        $order_service->create($order);
        return $this->jsonSuccess('创建交租订单', [
            'order_no' => $order->getOrderNo()
        ]);
    }
}