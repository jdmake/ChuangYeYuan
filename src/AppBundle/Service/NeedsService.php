<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/1
// +----------------------------------------------------------------------


namespace AppBundle\Service;


use AppBundle\Entity\PioneerparkXuqiuWenzhang;
use AppBundle\Entity\PioneerparkXuqiuZhaopin;
use AppBundle\View\NeedView;

class NeedsService extends AbsService
{
    /**
     * 分页显示需求
     */
    public function getPageList($filterParams, $cate, $model, $page, $size)
    {

        $em = $this->getEm();
        $query = $em->createQueryBuilder();

        if ($model == 1) {
            // 招聘系统模型
            $order = $query->expr()->desc('a.isquality');
            $order->add('a.createAt', 'desc');
            $query->select('a.id, a.cate, a.uid, a.title, a.subtitle, a.tag, a.salarymin, a.salarymax, a.number, a.education, a.experience, a.duty, a.seniority, a.isquality, a.createAt, a.mid, b.name,a.status, b.logopic')
                ->from('AppBundle:PioneerparkXuqiuZhaopin', 'a')
                ->innerJoin('AppBundle:PioneerparkMerchant', 'b', 'WITH', 'a.mid=b.id')
                ->where('a.cate=:cate')
                ->andWhere('a.status=:status')
                ->setParameter('cate', $cate)
                ->setParameter('status', 1)
                ->setFirstResult(($page - 1) * $size)
                ->setMaxResults($size)
                ->orderBy($order);

            // 设置筛选条件
            $welfare = @$filterParams['welfare'];
            $sendDate = @$filterParams['sendDate'];
            $salary = @$filterParams['salary'];
            $education = @$filterParams['education'];
            $experience = @$filterParams['experience'];
            // 设置WHERE
            if ($welfare) {
                $query->andWhere('a.tag LIKE :welfare')
                    ->setParameter('welfare', "%{$welfare}%");
            }
            if ($sendDate) {
                if (strpos('#' . $sendDate, 'd')) {
                    //天
                    $sendDate = substr($sendDate, 0, strlen($sendDate) - 1);
                    $start = date('Y-m-d H:i:s', strtotime("-{$sendDate} day"));
                    $end = date('Y-m-d 23:00:00', time());
                }
                if (strpos('#' . $sendDate, 'm')) {
                    //月
                    $sendDate = substr($sendDate, 0, strlen($sendDate) - 1);
                    $start = date('Y-m-d H:i:s', strtotime("-{$sendDate} month"));
                    $end = date('Y-m-d 23:00:00', time());
                }
                $query
                    ->andWhere($query->expr()->between('a.createAt', ':start', ':end'))
                    ->setParameter('start', $start)
                    ->setParameter('end', $end);

            }
            if ($salary) {
                list($x, $y) = explode('_', $salary);
                $query
                    ->andWhere($query->expr()->andX(
                        $query->expr()->gte('a.salarymin', ':x'),
                        $query->expr()->lte('a.salarymin', ':y')
                    ))
                    ->setParameter('x', $x)
                    ->setParameter('y', $y);
            }
            if ($education) {
                $query->andWhere('a.education=:education')
                    ->setParameter('education', $education);
            }
            if ($experience) {
                $query->andWhere('a.experience=:experience')
                    ->setParameter('experience', $experience);
            }

            $total = $this->getTotal($query);

            $list = $query->getQuery()->getResult();
            foreach ($list as &$item) {
                $item['tag'] = explode(',', $item['tag']);
                $item['createAt'] = $item['createAt']->getTimestamp();
                $item['sendCount'] = $this->getMerchantSendCount($item['mid'], $model);
            }

            return [
                'list' => $list,
                'pageSize' => intval(ceil($total / $size)),
                'total' => $total,
            ];
        } else if ($model == 2) {
            // 文章系统模型
            $order = $query->expr()->desc('a.isquality');
            $order->add('a.createAt', 'desc');
            $query->select('a.id, a.cate, a.mid, a.uid, a.title, a.subtitle, a.content, a.thumbnail, a.isquality, a.isrecommend, a.contacts, a.tel, a.createAt,a.status, b.name, b.logopic')
                ->from('AppBundle:PioneerparkXuqiuWenzhang', 'a')
                ->leftJoin('AppBundle:PioneerparkMerchant', 'b', 'WITH', 'a.mid=b.id')
                ->where('a.cate=:cate')
                ->andWhere('a.status=:status')
                ->setParameter('cate', $cate)
                ->setParameter('status', 1)
                ->setFirstResult(($page - 1) * $size)
                ->setMaxResults($size)
                ->orderBy($order);

            $total = $this->getTotal($query);

            $list = $query->getQuery()->getResult();
            foreach ($list as &$item) {
                $item['createAt'] = $item['createAt']->getTimestamp();
                $item['sendCount'] = $this->getMerchantSendCount($item['mid'], $model);
            }

            return [
                'list' => $list,
                'pageSize' => intval(ceil($total / $size)),
                'total' => $total,
            ];
        }

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
        $query->select('a.id, a.cate, a.mid, a.uid, a.title, a.subtitle, a.content, a.thumbnail, a.isquality, a.isrecommend, a.contacts, a.tel, a.createAt,a.status, b.name, b.logopic')
            ->from('AppBundle:PioneerparkXuqiuWenzhang', 'a')
            ->leftJoin('AppBundle:PioneerparkMerchant', 'b', 'WITH', 'a.mid=b.id')
            ->where('a.title like :search')
            ->setParameter('search', "%{$searchValue}%")
            ->setFirstResult(($page - 1) * $size)
            ->setMaxResults($size)
            ->orderBy('a.createAt', 'desc');

        $list = $query->getQuery()->getResult();
        foreach ($list as &$item) {
            $item['createAt'] = $item['createAt']->getTimestamp();
            $item['sendCount'] = $this->getMerchantSendCount($item['mid'], 2);
        }

        return $list;
    }

    /**
     * 获取企业发布数量
     * @param $model
     * @return
     */
    public function getMerchantSendCount($mid, $model)
    {
        $em = $this->getEm();

        if ($model == 1) {
            $query = $em->createQuery('
        SELECT count(a) as total FROM AppBundle:PioneerparkXuqiuZhaopin a
        WHERE a.mid = :mid 
        ')->setParameter('mid', $mid);
        } else if ($model == 2) {
            $query = $em->createQuery('
        SELECT count(a) as total FROM AppBundle:PioneerparkXuqiuWenzhang a
        WHERE a.mid = :mid 
        ')->setParameter('mid', $mid);
        }

        return $query->getResult()[0]['total'];
    }

    /**
     * 获取全部类型
     */
    public function findAllCate()
    {
        $res = $this->getDoctrine()->getRepository('AppBundle:PioneerparkXuqiuCategory')
            ->findBy([], [
                'sort' => 'asc'
            ]);
        foreach ($res as &$re) {
            $re = $this->toArray($re);
        }
        return $res;
    }

    /**
     * 获取企业信息
     * @param $mid
     * @return \AppBundle\Entity\PioneerparkMerchant|object|null
     */
    public function getMerchant($mid)
    {
        return $this->getDoctrine()->getRepository('AppBundle:PioneerparkMerchant')
            ->find($mid);
    }

    /**
     * 获取招聘模型详情
     */
    public function getZhaoPinDetail($id)
    {
        return $this->getDoctrine()->getRepository('AppBundle:PioneerparkXuqiuZhaopin')
            ->find($id);
    }

    /**
     * 获取文章模型详情
     */
    public function getWenZhangDetail($id)
    {
        return $this->getDoctrine()->getRepository('AppBundle:PioneerparkXuqiuWenzhang')
            ->find($id);
    }

    /**
     * 创建招聘数据
     * @param $uid
     * @param $mid
     * @param $cate
     * @param array $data
     * @return int
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createZhaoPinData($uid, $mid, $cate, array $data = [])
    {
        $entry = new PioneerparkXuqiuZhaopin();
        $entry->setCate($cate);
        $entry->setUid($uid);
        $entry->setMid($mid);
        $entry->setContacts($data['contacts']);
        $entry->setDuty($data['duty']);
        $entry->setNumber($data['number']);
        $entry->setSalarymax($data['salarymax']);
        $entry->setSalarymin($data['salarymin']);
        $entry->setSeniority($data['seniority']);
        $entry->setSubtitle($data['subtitle']);
        $entry->setTel($data['tel']);
        $entry->setTitle($data['title']);
        $entry->setTag($data['tag']);
        $entry->setEducation($data['education'] == '不限' ? '' : $data['education']);
        $entry->setExperience($data['experience'] == '不限' ? '' : $data['experience']);
        $entry->setIsquality(false);
        $entry->setIsrecommend(false);
        $entry->setVisit(0);
        $entry->setStatus(0);
        $entry->setCreateAt(new \DateTime());

        $this->getEm()->persist($entry);
        $this->getEm()->flush();

        return $entry->getId();
    }

    /**
     * 获取招聘信息上一次发布的时间
     */
    public function getZhaoPinLastPostTime($mid, $uid)
    {
        $res = $this->getDoctrine()->getRepository('AppBundle:PioneerparkXuqiuZhaopin')
            ->findBy([
                'mid' => $mid,
                'uid' => $uid,
            ], ['createAt' => 'desc']);
        if ($res) {
            return $res[0]->getCreateAt()->getTimestamp();
        }
        return 0;
    }

    /**
     * 提交文章模型发布
     * @param $uid
     * @param $mid
     * @param $cate
     * @param array $data
     * @return int
     */
    public function createWenZhangData($uid, $mid, $cate, array $data = [])
    {
        $entry = new PioneerparkXuqiuWenzhang();
        $entry->setUid($uid);
        $entry->setMid($mid);
        $entry->setCate($cate);
        $entry->setContacts($data['contacts']);
        $entry->setContent($data['content']);
        $entry->setSubtitle($data['subtitle']);
        $entry->setTel($data['tel']);
        $entry->setTitle($data['title']);
        $entry->setPicture($data['picture']);
        $entry->setThumbnail($data['thumbnail']);
        $entry->setIsquality(false);
        $entry->setIsrecommend(0);
        $entry->setStatus(0);
        $entry->setCreateAt(new \DateTime());

        $this->getEm()->persist($entry);
        $this->getEm()->flush();

        return $entry->getId();
    }

    /**
     * 获取最新需求
     */
    public function getNewNeeds()
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $query->select('a')
            ->from('AppBundle:PioneerparkXuqiuZhaopin', 'a')
            ->setFirstResult(0)
            ->setMaxResults(5)
            ->where('a.isrecommend=:isrecommend')
            ->setParameter('isrecommend', true)
            ->orderBy('a.createAt', 'desc');
        $res = $query->getQuery()->getResult();

        $listNeeds = [];
        /** @var PioneerparkXuqiuZhaopin $re */
        foreach ($res as $re) {
            $needView = new NeedView();
            $needView->setModel(1);
            $needView->setId($re->getId());
            $needView->setUid($re->getUid());
            $needView->setMid($re->getMid());
            $needView->setTitle($re->getTitle());
            $needView->setContent(
                sprintf('薪水：%s-%s 要求：招%s人/学历，%s/工作经验，%s',
                    $re->getSalarymin(),
                    $re->getSalarymax(),
                    $re->getNumber(),
                    $re->getEducation(),
                    $re->getExperience()
                )
            );
            $needView->setIsquality($re->getIsquality());
            $needView->setCreateAt(date('Y-m-d H:i:s', $re->getCreateAt()->getTimestamp()));
            $needView->setPath('/pages/needs/ZhaoPinDetail?id=' . $needView->getId());

            $listNeeds[] = $needView;
        }

        $query = $em->createQueryBuilder();
        $query->select('a')
            ->from('AppBundle:PioneerparkXuqiuWenzhang', 'a')
            ->setFirstResult(0)
            ->setMaxResults(5)
            ->orderBy('a.createAt', 'desc');
        $res = $query->getQuery()->getResult();
        /** @var PioneerparkXuqiuWenzhang $re */
        foreach ($res as $re) {
            $needView = new NeedView();
            $needView = new NeedView();
            $needView->setModel(2);
            $needView->setId($re->getId());
            $needView->setUid($re->getUid());
            $needView->setMid($re->getMid());
            $needView->setTitle($re->getTitle());
            $needView->setContent($re->getContent());
            $needView->setIsquality($re->getIsquality());
            $needView->setCreateAt(date('Y-m-d H:i:s', $re->getCreateAt()->getTimestamp()));
            $needView->setPath('/pages/needs/WenZhangDetail?id=' . $needView->getId());
            $listNeeds[] = $needView;
        }

        return $listNeeds;
    }

    /**
     * 获取我的招聘信息列表
     * @param $uid
     * @param $page
     * @param $size
     * @return array
     */
    public function getNeedsListByUid($uid, $page, $size)
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $query->select('a')
            ->from('AppBundle:PioneerparkXuqiuWenzhang', 'a')
            ->where('a.uid=:uid')
            ->setParameter('uid', $uid)
            ->setFirstResult(($page - 1) * $size)
            ->setMaxResults($size)
            ->orderBy('a.createAt', 'desc');
        $res = $query->getQuery()->getResult();
        /** @var PioneerparkXuqiuWenzhang $re */
        foreach ($res as $re) {
            $needView = new NeedView();
            $needView = new NeedView();
            $needView->setModel(2);
            $needView->setId($re->getId());
            $needView->setUid($re->getUid());
            $needView->setMid($re->getMid());
            $needView->setTitle($re->getTitle());
            $needView->setContent($re->getContent());
            $needView->setIsquality($re->getIsquality());
            $needView->setCreateAt(date('Y-m-d H:i:s', $re->getCreateAt()->getTimestamp()));
            $needView->setPath('/pages/needs/WenZhangDetail?id=' . $needView->getId());
            $listNeeds[] = $needView;
        }
        $wenzhangTotal = $this->getTotal($query);

        return [
            'list' => $listNeeds,
            'total' => $wenzhangTotal,
            'pageSize' => ceil(($wenzhangTotal) / $size)
        ];
    }


}