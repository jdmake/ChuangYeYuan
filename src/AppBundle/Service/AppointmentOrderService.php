<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/5
// +----------------------------------------------------------------------


namespace AppBundle\Service;


use AppBundle\Entity\PioneerparkAppointmentOrder;
use AppBundle\Entity\PioneerparkAppointmentRecord;
use AppBundle\Entity\PioneerparkMeetingroom;

class AppointmentOrderService extends AbsService
{
    /**
     * 创建订单
     * @param $uid
     * @param $record_id
     * @param $price
     * @param $count
     * @param $stepminute
     * @return string
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createOrder($uid, $record_id, $price, $count, $stepminute)
    {
        $total = $price;
        $entry = new PioneerparkAppointmentOrder();
        $entry->setUid($uid);
        $entry->setRecordId($record_id);
        $entry->setOrderNo(date('YmdHis', time()) . $uid . time());
        $entry->setTotal($total);
        $entry->setPayStatus(false);
        $entry->setStatus(0);
        $entry->setCreateAt(new \DateTime());
        $this->create($entry);
        return $entry->getOrderNo();
    }
}