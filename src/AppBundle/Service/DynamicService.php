<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/4
// +----------------------------------------------------------------------


namespace AppBundle\Service;

use AppBundle\Entity\PioneerparkDongtai;
use AppBundle\View\DynamicView;
use Doctrine\ORM\Query\Expr\OrderBy;

/**
 * 动态服务
 */
class DynamicService extends AbsService
{
    public function getPageList($cate, $page, $size)
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $query->select('a')
            ->from('AppBundle:PioneerparkDongtai', 'a')
            ->orderBy('a.createAt', 'desc')
            ->where('a.status=:status')
            ->setParameter('status', 1)
            ->setFirstResult(($page - 1) * $size)
            ->setMaxResults($size);

        if ($cate) {
            $query->andWhere('a.cate=:cate')
                ->setParameter('cate', $cate);
        }

        $total = $this->getTotal($query);

        $res = $query->getQuery()->getResult();
        return [
            'list' => $res,
            'pageSize' => intval(ceil($total / $size)),
            'total' => $total,
        ];
    }

    /**
     * 搜索分页
     * @param $searchValue
     * @param $page
     * @param $size
     * @return array
     */
    public function searchPageList($searchValue, $page, $size)
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $query->select('a')
            ->from('AppBundle:PioneerparkDongtai', 'a')
            ->where('a.title like :search')
            ->setParameter('search', "%{$searchValue}%")
            ->setFirstResult(($page - 1) * $size)
            ->setMaxResults($size)
            ->orderBy('a.createAt', 'desc');

        $list = $query->getQuery()->getResult();
        return $list;
    }

    /**
     * 获取全部
     */
    public function findAll()
    {
        $em = $this->getEm();
        $order = new OrderBy();
        $order->add('a.sort', 'desc');
        $order->add('a.id', 'desc');

        $query = $em->createQueryBuilder();
        $query->select('a')
            ->from('AppBundle:PioneerparkDongtaiCategory', 'a')
            ->orderBy($order);

        return $query->getQuery()->getResult();
    }

    /**
     * 创建动态信息
     * @param $uid
     * @param $mid
     * @param $cate
     * @param array $data
     * @return int
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function createDongTaiData($uid, $mid, $cate, array $data = [])
    {
        $entry = new PioneerparkDongtai();
        $entry->setCate($cate);
        $entry->setUid($uid);
        $entry->setMid($mid);
        $entry->setTitle($data['title']);
        $entry->setContent($data['content']);
        $entry->setPicture($data['picture']);
        $entry->setIsquality(false);
        $entry->setIsrecommend(false);
        $entry->setTag('');
        $entry->setCreateAt(new \DateTime());
        $entry->setStatus(0);
        $entry->setVisit(0);

        $this->getEm()->persist($entry);
        $this->getEm()->flush();

        return $entry->getId();
    }

    /**
     * 是否存在待审核的动态
     */
    public function isExsitWaitHandle($uid)
    {
        $res = $this->getDoctrine()->getRepository('AppBundle:PioneerparkDongtai')
            ->findOneBy([
                'uid' => $uid,
                'status' => 0
            ]);
        return $res ? true : false;
    }

    /**
     * 获取详情
     * @param $id
     * @return PioneerparkDongtai|object|null
     */
    public function getDetailObject($id)
    {
        $res = $this->getDoctrine()->getRepository('AppBundle:PioneerparkDongtai')
            ->findOneBy([
                'id' => $id,
            ]);
        return $res;
    }

    /**
     * 获取详情
     * @param $id
     * @return PioneerparkDongtai|object|null
     */
    public function getDetail($id)
    {
        $res = $this->getDoctrine()->getRepository('AppBundle:PioneerparkDongtai')
            ->findOneBy([
                'id' => $id,
            ]);
        return $this->toArray($res);
    }

    /**
     * 浏览量增加
     * @param $id
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setIncVisit($id)
    {
        $entry = $this->getDoctrine()->getRepository('AppBundle:PioneerparkDongtai')->find($id);
        if ($entry) {
            $entry->setVisit($entry->getVisit() + 1);
            $this->getEm()->flush();
        }
    }

    /**
     * 获取最新动态
     * @param $size
     * @return array
     */
    public function getNewDynamic($size)
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $query->select('a')
            ->from('AppBundle:PioneerparkDongtai', 'a')
            ->setFirstResult(0)
            ->setMaxResults($size)
            ->where('a.isrecommend=:isrecommend')
            ->setParameter('isrecommend', true)
            ->orderBy('a.createAt', 'desc');
        $res = $query->getQuery()->getResult();

        $listNeeds = [];
        /** @var PioneerparkDongtai $re */
        foreach ($res as $re) {
            $dynamicView = new DynamicView();
            $dynamicView->setId($re->getId());
            $dynamicView->setUid($re->getUid());
            $dynamicView->setMid($re->getMid());
            $dynamicView->setTitle($re->getTitle());
            $dynamicView->setContent($re->getContent());
            $dynamicView->setIsquality($re->getIsquality());
            $dynamicView->setCreateAt(date('Y-m-d H:i:s', $re->getCreateAt()));
            $dynamicView->setPath('/pages/dynamic/detail?id=' . $re->getId());
            $listNeeds[] = $dynamicView;
        }

        return $listNeeds;
    }

    /**
     * 获取我发布的动态列表
     * @param $uid
     * @param $page
     * @param $size
     */
    public function getDynamicListByUid($uid, $page, $size)
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $query->select('a')
            ->from('AppBundle:PioneerparkDongtai', 'a')
            ->where('a.uid=:uid')
            ->setParameter('uid', $uid)
            ->setFirstResult(($page - 1) * $size)
            ->setMaxResults($size)
            ->orderBy('a.createAt', 'desc');
        $res = $query->getQuery()->getResult();
        $total = $this->getTotal($query);

        $listNeeds = [];
        /** @var PioneerparkDongtai $re */
        foreach ($res as $re) {
            $dynamicView = new DynamicView();
            $dynamicView->setId($re->getId());
            $dynamicView->setUid($re->getUid());
            $dynamicView->setMid($re->getMid());
            $dynamicView->setTitle($re->getTitle());
            $dynamicView->setContent($re->getContent());
            $dynamicView->setIsquality($re->getIsquality());
            $dynamicView->setCreateAt(date('Y-m-d H:i:s', $re->getCreateAt()));
            $dynamicView->setPath('/pages/dynamic/detail?id=' . $re->getId());
            $listNeeds[] = $dynamicView;
        }

        return [
            'list' => $listNeeds,
            'total' => $total,
            'pageSize' => ceil($total / $size)
        ];
    }
}