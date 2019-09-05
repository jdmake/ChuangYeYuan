<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/9/4
// +----------------------------------------------------------------------


namespace AppBundle\Service;

class ArticleService extends AbsService
{
    const MERCHANT = 'MERCHANT';
    const SOUTHEAST = 'SOUTHEAST';
    const COOPERATION = 'COOPERATION';
    const ABOUT = 'ABOUT';

    /**
     * 获取分类
     * @param $type
     * @return array
     */
    public function getCates($type)
    {

        $query = $this->getEm()->createQueryBuilder();
        $query->select('a')
            ->from('AppBundle:PioneerparkArticleCategory', 'a')
            ->where('a.type = :type')
            ->setParameter('type', $type)
            ->orderBy('a.sort', 'asc');

        $list = $query->getQuery()->getArrayResult();

        return $list;
    }

    /**
     * 获取文章内容分页列表
     * @param $type
     * @param $cate
     * @param $page
     * @param $size
     * @return array
     */
    public function getPageList($type, $cate, $page, $size)
    {
        $query = $this->getEm()->createQueryBuilder();
        $query->select('a')
            ->from('AppBundle:PioneerparkArticle', 'a')
            ->setFirstResult(($page - 1) * $size)
            ->setMaxResults($size)
            ->where('a.type = :type')
            ->setParameter('type', $type)
            ->andWhere('a.cate = :cate')
            ->setParameter('cate', $cate)
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
     * 获取文章详情
     * @param $id
     * @return \AppBundle\Entity\PioneerparkArticle|object|null
     */
    public function getDetail($id)
    {
        $res = $this->getDoctrine()->getRepository('AppBundle:PioneerparkArticle')
            ->find($id);

        return $res;
    }
}