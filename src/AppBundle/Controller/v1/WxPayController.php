<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/12
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/v1/wxpay")
 */
class WxPayController extends CommentController
{
    /**
     * 统一下单
     * @Route("/uniformOrder")
     */
    public function uniformOrderAction()
    {
        $order_no = $this->getJsonParameter('order_no');
        $openid = $this->getUserSession('openid');

        // 获取订单信息
        $order_service = $this->get('order_service');
        $order_entry = $order_service->findByOrderNo($order_no);
        $money = $order_entry->getTotal();
        $wxpay_service = $this->get('wxpay_service');
        $res = $wxpay_service->uniformOrder($openid, $money, $order_no, $order_no);
        if (isset($res['err_code']) && $res['err_code']) {
            $order_entry->setStatus(2);
            $this->getDoctrine()->getManager()->flush();
            // 改变预约记录状态
            $recordEntry = $this->get('appointmentrecord_service')->getDetailById($order_entry->getRecordId());
            $recordEntry->setStatus(2);
            $this->getDoctrine()->getManager()->flush();
            return $this->jsonError(1, '订单已失效');
        }
        return $this->jsonSuccess('统一下单', $res);
    }

    /**
     * @Route("/notify_url")
     */
    public function notifyUrlAction()
    {
        $wxpay_service = $this->get('wxpay_service');
        $wxpay_service->notifyUrlCallBack(function ($data) {
            $out_trade_no = $data['out_trade_no'];
            $total_fee = $data['total_fee'] / 100;
            // 获取订单信息
            $order_service = $this->get('order_service');
            $order_entry = $order_service->findByOrderNo($out_trade_no);
            if($order_entry->getTotal() == $total_fee) {
                // 改变订单状态
                $order_entry->setStatus(1);
                $order_entry->setPayStatus(1);
                $this->getDoctrine()->getManager()->flush();

                if(substr($out_trade_no, 0, 2) != 'TM') {
                    if(substr($out_trade_no, 0, 4) != 'RENT') {
                        // 改变预约记录状态
                        $recordEntry = $this->get('appointmentrecord_service')->getDetailById($order_entry->getRecordId());
                        $recordEntry->setStatus(1);
                    }else {
                        $recordEntry = $this->get('rent_service')->findById($order_entry->getRecordId());
                        $recordEntry->setStatus(1);
                        $recordEntry->setCompletion(new \DateTime());
                    }
                }

                $this->getDoctrine()->getManager()->flush();
                return new Response('success');
            }else {
                return new Response('error');
            }
        });
    }
}