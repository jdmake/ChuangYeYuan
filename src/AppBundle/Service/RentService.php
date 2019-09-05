<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/19
// +----------------------------------------------------------------------


namespace AppBundle\Service;


class RentService extends AbsService
{
    /**
     * 获取交租记录分页列表
     * @param $uid
     * @param $page
     * @param $size
     * @return array
     */
    public function getPageList($uid, $page, $size)
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $query->select('a')
            ->from('AppBundle:PioneerparkRentRecord', 'a')
            ->where('a.uid=:uid')
            ->setParameter('uid', $uid)
            ->orderBy('a.createAt', 'desc')
            ->setFirstResult(($page - 1) * $size)
            ->setMaxResults($size);

        $total = $this->getTotal($query);

        return [
            'list' => $query->getQuery()->getResult(),
            'pageSize' => ceil($total / $size),
            'total' => $total
        ];
    }

    /**
     * 获取交租记录
     * @param $id
     * @return \AppBundle\Entity\PioneerparkRentRecord|object|null
     */
    public function findById($id) {
        return $this->getDoctrine()->getRepository('AppBundle:PioneerparkRentRecord')
            ->find($id);
    }
}