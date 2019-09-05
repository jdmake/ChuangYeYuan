<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/6
// +----------------------------------------------------------------------


namespace AppBundle\Service;


class OrderService extends AbsService
{
    /**
     * 获取订单分页列表
     * @param $uid
     * @param $status
     * @param $page
     * @param $size
     * @return array
     */
    public function getAppointmentOrderPageList($uid, $status, $page, $size)
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $query->select('a.id, a.uid, a.orderNo, a.total, a.status, a.payStatus, a.createAt, c.title, b.date, b.rid, b.time')
            ->from('AppBundle:PioneerparkAppointmentOrder', 'a')
            ->leftJoin('AppBundle:PioneerparkAppointmentRecord', 'b', 'WITH', 'a.recordId=b.id')
            ->leftJoin('AppBundle:PioneerparkMeetingroom', 'c', 'WITH', 'b.rid=c.id')
            ->where('a.uid=:uid')
            ->setParameter('uid', $uid)
            ->setFirstResult(($page - 1) * $size)
            ->setMaxResults($size)
            ->orderBy('a.createAt', 'desc');

        if ($status != '') {
            $query->andWhere('a.status=:status');
            $query->setParameter('status', $status);
        }

        $total = $this->getTotal($query);
        $list = $query->getQuery()->getResult();
        foreach ($list as &$item) {
            $item['createAt'] = date('Y-m-d H:i:s', $item['createAt']->getTimestamp());
        }
        return [
            'list' => $list,
            'pageSize' => ceil($total / $size),
            'total' => $total
        ];
    }

    /**
     * 根据订单号获取订单详情
     * @param $order_no
     * @return \AppBundle\Entity\PioneerparkAppointmentOrder|\AppBundle\Entity\PioneerparkRentOrder|object|null
     */
    public function findByOrderNo($order_no)
    {
        $res = $this->getDoctrine()->getRepository('AppBundle:PioneerparkAppointmentOrder')
            ->findOneBy([
                'orderNo' => $order_no
            ]);
        if (!$res) {
            $res = $this->getDoctrine()->getRepository('AppBundle:PioneerparkRentOrder')
                ->findOneBy([
                    'orderNo' => $order_no
                ]);
        }
        return $res;
    }

    /**
     * 根据预约号获取订单详情
     * @param $order_no
     * @return \AppBundle\Entity\PioneerparkAppointmentOrder
     */
    public function findMeetingByRid($rid)
    {
        $res = $this->getDoctrine()->getRepository('AppBundle:PioneerparkAppointmentOrder')
            ->findOneBy([
                'recordId' => $rid
            ]);
        return $res;
    }

    /**
     * 获取交租订单分页列表
     * @param $uid
     * @param $status
     * @param $page
     * @param $size
     * @return array
     */
    public function getRentOrderPageList($uid, $status, $page, $size)
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $query->select('a')
            ->from('AppBundle:PioneerparkRentOrder', 'a')
            ->where('a.uid=:uid')
            ->setParameter('uid', $uid)
            ->setFirstResult(($page - 1) * $size)
            ->setMaxResults($size)
            ->orderBy('a.createAt', 'desc');

        if ($status != '') {
            $query->andWhere('a.status=:status');
            $query->setParameter('status', $status);
        }

        $total = $this->getTotal($query);
        $list = $query->getQuery()->getResult();
        foreach ($list as &$item) {
            $item = $this->toArray($item);
            $item['createAt'] = explode('.', $item['createAt']['date'])[0];
        }
        return [
            'list' => $list,
            'pageSize' => ceil($total / $size),
            'total' => $total
        ];
    }
}