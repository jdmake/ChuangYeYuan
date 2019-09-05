<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/9/4
// +----------------------------------------------------------------------


namespace AppBundle\Service;

use Knp\Component\Pager\Paginator;

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
     * 获取文章分类分页列表
     * @param $type
     * @param $search
     * @param $page
     * @param $size
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getCategoryPageListByAdmin($type, $search, $page, $size)
    {
        $query = $this->getEm()->createQueryBuilder();
        $query->select('a')
            ->from('AppBundle:PioneerparkArticleCategory', 'a')
            ->setFirstResult(($page - 1) * $size)
            ->setMaxResults($size)
            ->where('a.type = :type')
            ->setParameter('type', $type)
            ->orderBy('a.sort', 'asc');

        if($search != '') {
            $query->andWhere('a.title like :title')->setParameter('title', "%{$search}%");
        }

        /** @var Paginator $knp_paginator */
        $knp_paginator = $this->get('knp_paginator');
        $pagination = $knp_paginator->paginate($query, $page, $size);

        return $pagination;
    }

    /**
     * 获取文章分页列表
     * @param $type
     * @param $cate
     * @param $search
     * @param $page
     * @param $size
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getPageListByAdmin($type, $cate, $search, $page, $size)
    {
        $query = $this->getEm()->createQueryBuilder();
        $query->select('a')
            ->from('AppBundle:PioneerparkArticle', 'a')
            ->setFirstResult(($page - 1) * $size)
            ->setMaxResults($size)
            ->where('a.type = :type')
            ->setParameter('type', $type)
            ->orderBy('a.createAt', 'desc');

        if($search != '') {
            $query->andWhere('a.title like :title')->setParameter('title', "%{$search}%");
        }
        if($cate != '') {
            $query->andWhere('a.cate = :cate')->setParameter('cate', $cate);
        }

        /** @var Paginator $knp_paginator */
        $knp_paginator = $this->get('knp_paginator');
        $pagination = $knp_paginator->paginate($query, $page, $size);

        return $pagination;
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