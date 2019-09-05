<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/10
// +----------------------------------------------------------------------


namespace AppBundle\Service;


class NoticeService extends AbsService
{
    /**
     * 获取公告分页列表
     * @param $page
     * @param $size
     * @return array
     */
    public function getPageList($page, $size)
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $query->select('a')
            ->from('AppBundle:PioneerparkNotice', 'a')
            ->setFirstResult(($page - 1) * $size)
            ->setMaxResults($size)
            ->orderBy('a.createAt', 'desc');

        $list = $query->getQuery()->getArrayResult();
        $total = $this->getTotal($query);

        foreach ($list as &$item) {
            $item['createAt'] = $item['createAt']->format('Y-m-d H:i');
        }

        return [
            'list' => $list,
            'total' => $total,
            'pageSize' => intval(ceil($total / $size))
        ];
    }

    /**
     * 获取最新公告
     * @return \AppBundle\Entity\PioneerparkNotice[]|array
     */
    public function findNewNotice()
    {
        return $this->getDoctrine()->getRepository('AppBundle:PioneerparkNotice')
            ->findBy([], [
                'createAt' => 'desc'
            ]);
    }

    /**
     * 获取公告详情
     * @param $id
     * @return \AppBundle\Entity\PioneerparkNotice|object|null
     */
    public function findNoticeById($id)
    {
        return $this->getDoctrine()->getRepository('AppBundle:PioneerparkNotice')
            ->find($id);
    }
}