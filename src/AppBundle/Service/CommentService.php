<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/4
// +----------------------------------------------------------------------


namespace AppBundle\Service;


use AppBundle\Entity\PioneerparkComment;
use AppBundle\Entity\PioneerparkCommentZanrecord;
use Doctrine\ORM\Query\Expr\OrderBy;


class CommentService extends AbsService
{
    /**
     * 获取评论分页列表
     * @param $cate
     * @param $id
     * @param $page
     * @param $size
     * @return array
     */
    public function getPageList($cate, $id, $page, $size)
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $order = new OrderBy();
        $order->add('a.zantotal', 'desc');
        $order->add('a.createAt', 'desc');
        $query->select('a.id,a.uid,a.fromid,a.fromcate,a.content,a.zantotal, a.createAt, c.avatar, c.nickname')
            ->from('AppBundle:PioneerparkComment', 'a')
            ->innerJoin('AppBundle:PioneerparkMember', 'b', 'WITH', 'a.uid=b.uid')
            ->innerJoin('AppBundle:PioneerparkMemberProfile', 'c', 'WITH', 'b.profileid=c.id')
            ->where('a.fromcate=:cate')
            ->andWhere('a.fromid=:fromid')
            ->andWhere('a.enable=:enable')
            ->setParameter('cate', $cate)
            ->setParameter('fromid', $id)
            ->setParameter('enable', true)
            ->setFirstResult(($page - 1) * $size)
            ->setMaxResults($size)
            ->orderBy($order);

        $total = $this->getTotal($query);

        return [
            'list' => $query->getQuery()->getResult(),
            'pageSize' => intval(ceil($total / $size)),
            'total' => $total,
        ];
    }

    /**
     * 点赞
     * @param $id
     * @param $zan
     * @return bool
     */
    public function dianZan($uid, $id, $zan)
    {

        $entry = $this->getDoctrine()->getRepository('AppBundle:PioneerparkComment')
            ->find($id);
        if ($entry) {
            $zantotal = $entry->getZantotal();
            if ($zan) {
                $zantotal += 1;
            } else {
                if ($zantotal > 0) {
                    $zantotal -= 1;
                }
            }
            $entry->setZantotal($zantotal);
            $this->getEm()->flush();

            if ($zan) {
                // 添加点赞记录
                $this->addZanRecord($uid, $id);
            } else {
                // 删除点赞记录
                $this->delZanRecord($uid, $id);
            }
            return true;
        }
        return false;
    }

    /**
     * 添加点赞记录
     * @param $uid
     * @param $id
     * @return int
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addZanRecord($uid, $id)
    {
        if (!$this->isZan($id, $uid)) {
            // 添加点赞记录
            $zanRecord = new PioneerparkCommentZanrecord();
            $zanRecord->setUid($uid);
            $zanRecord->setCommentid($id);
            $zanRecord->setCreateAt(new \DateTime());
            $this->getEm()->persist($zanRecord);
            $this->getEm()->flush();
            return $zanRecord->getId();
        }
        return 0;
    }

    /**
     * 删除点赞记录
     * @param $uid
     * @param $id
     */
    public function delZanRecord($uid, $id)
    {
        $entry = $this->getDoctrine()->getRepository('AppBundle:PioneerparkCommentZanrecord')
            ->findOneBy([
                'commentid' => $id,
                'uid' => $uid
            ]);
        if ($entry) {
            $this->getEm()->remove($entry);
            $this->getEm()->flush();
        }
    }

    /**
     * 是否赞过
     * @param $id
     * @param $uid
     * @return bool
     */
    public function isZan($id, $uid)
    {
        $entry = $this->getDoctrine()->getRepository('AppBundle:PioneerparkCommentZanrecord')
            ->findOneBy([
                'commentid' => $id,
                'uid' => $uid
            ]);
        if ($entry) {
            return true;
        }
        return false;
    }

    /**
     * 发布评论
     * @param $uid
     * @param $cate
     * @param $id
     * @param $content
     * @return int
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addComment($uid, $cate, $id, $content)
    {
        $entry = new PioneerparkComment();
        $entry->setUid($uid);
        $entry->setFromid($id);
        $entry->setContent($content);
        $entry->setZantotal(0);
        $entry->setFromcate($cate);
        $entry->setCreateAt(new \DateTime());
        $this->getEm()->persist($entry);
        $this->getEm()->flush();
        return $entry->getId();
    }
}