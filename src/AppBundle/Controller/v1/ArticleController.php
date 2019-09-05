<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/9/5
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1;


use AppBundle\Controller\Common\CommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/v1/article")
 */
class ArticleController extends CommonController
{
    /**
     * 获取分类
     * @Route("/getCates")
     */
    public function getCatesAction()
    {
        $type = $this->request()->get('type');

        $article_service = $this->get('article_service');
        $cates = $article_service->getCates($type);

        $cates[0]['selected'] = true;

        return $this->jsonSuccess('获取分类', $cates);
    }

    /**
     * @Route("/getPageList")
     */
    public function getPageListAction()
    {
        $type = $this->request()->get('type');
        $page = $this->request()->get('page', 1);
        $cate = $this->request()->get('cate');

        $article_service = $this->get('article_service');
        $pagination = $article_service->getPageList($type, $cate, $page, 10);

        return $this->jsonSuccess('获取文章列表', $pagination);
    }

    /**
     * 获取文章详情
     * @Route("/getDetail")
     */
    public function getDetailAction()
    {
        $id = $this->request()->get('id');

        $article_service = $this->get('article_service');
        $res = $article_service->getDetail($id);
        $res->setVisit($res->getVisit() + 1);
        $this->getDoctrine()->getManager()->flush();

        $detail = $article_service->toArray($res);
        $detail['createAt'] = explode('.', $detail['createAt']['date'])[0];

        return $this->jsonSuccess('获取文章详情', $detail);
    }
}