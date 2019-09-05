<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/5
// +----------------------------------------------------------------------


namespace AppBundle\Service;


use AppBundle\Entity\PioneerparkAppointmentRecord;

class AppointmentRecordService extends AbsService
{
    /**
     * 获取全部预约记录
     * @param $status
     * @param $uid
     * @return PioneerparkAppointmentRecord[]|array
     */
    public function getAllByUid($status, $uid)
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $query->select('a.id, a.date, a.time, a.status, b.title, b.price')
            ->from('AppBundle:PioneerparkAppointmentRecord', 'a')
            ->innerJoin('AppBundle:PioneerparkMeetingroom', 'b', 'WITH', 'a.rid=b.id')
            ->where('a.uid=:uid')
            ->setParameter('uid', $uid)
            ->orderBy('a.date', 'desc');

        if ($status != '') {
            $query->andWhere('a.status=:status');
            $query->setParameter('status', $status);
        }

        $res = $query->getQuery()->getResult();
        return $res;
    }

    /**
     * 获取记录
     * @param $id
     * @return PioneerparkAppointmentRecord|object|null
     */
    public function getDetailById($id)
    {
        return $this->getDoctrine()->getRepository('AppBundle:PioneerparkAppointmentRecord')
            ->find($id);
    }

    /**
     * 是否已经预约
     * @param $date
     * @param $rid
     * @return bool
     */
    public function isExist($date, $time, $rid)
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $and = $query->expr()->andX();
        $query->select('a')
            ->from('AppBundle:PioneerparkAppointmentRecord', 'a')
            ->where($query->expr()->andX(
                $query->expr()->eq('a.date', ':date'),
                $query->expr()->eq('a.rid', ':rid'),
                $query->expr()->eq('a.status', ':status'),
                $query->expr()->like('a.time', ':time')
            ))
            ->setParameter('date', $date)
            ->setParameter('rid', $rid)
            ->setParameter('status', 1)
            ->setParameter('time', "%{$time}%");

        $res = $query->getQuery()->getResult();
        return count($res) > 0 ? true : false;
    }

    /**
     * 创建预约记录
     * @param $id
     * @param $uid
     * @param $date
     * @param $time
     * @return int
     * @throws \Exception
     */
    public function createRecord($id, $uid, $date, $time)
    {
        $entry = new PioneerparkAppointmentRecord();
        $entry->setRid($id);
        $entry->setUid($uid);
        $entry->setDate($date);
        $entry->setTime($time);
        $entry->setStatus(0);
        $entry->setCreateAt(new \DateTime());

        $this->create($entry);

        return $entry->getId();
    }

    /**
     * 已过期的全部预约设置为已失效
     */
    public function setTodayExpiredRecordInvalid()
    {
        // 今日日期
        $todayDate = date('Y-m-d', time());
        // 获取超过今日,且支付状态待支付，的预约
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $and = $query->expr()->andX(
            $query->expr()->lt('a.date', ':date')
        );
        $query->select('a.id')
            ->from('AppBundle:PioneerparkAppointmentRecord', 'a')
            ->where('a.status=:status')
            ->andWhere($and);
        $query->setParameter('status', 0);
        $query->setParameter('date', $todayDate);

        $ids = array_column($query->getQuery()->getResult(), 'id');
        foreach ($ids as $id) {
            $entry = $this->getDoctrine()->getRepository('AppBundle:PioneerparkAppointmentRecord')
                ->find($id);
            $entry->setStatus(2);
            $this->getEm()->flush($entry);
        }
        return true;
    }
}