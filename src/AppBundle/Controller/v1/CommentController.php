<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/4
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1;


use AppBundle\Controller\Common\CommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/v1/comment")
 */
class CommentController extends CommonController
{
    /**
     * @Route("/getPageList")
     */
    public function getPageListAction()
    {
        $cate = $this->request()->get('cate');
        $id = $this->request()->get('id');
        $page = $this->request()->get('page', 1);

        $comment_service = $this->get('comment_service');
        $res = $comment_service->getPageList($cate, $id, $page, 10);
        foreach ($res['list'] as &$re) {
            $re['createAt'] = $re['createAt']->getTimestamp();
            $re['isZan'] = $comment_service->isZan($re['id'], $this->getUserSession('uid'));
        }
        return $this->jsonSuccess('获取评论分页列表', $res);
    }

    /**
     * @Route("/dianZan")
     */
    public function dianZanAction()
    {
        $id = $this->getJsonParameter('id');
        $zan = $this->getJsonParameter('zan');

        $comment_service = $this->get('comment_service');
        $res = $comment_service->dianZan($this->getUserSession('uid'), $id, $zan);

        return $this->jsonSuccess('点赞', $res);
    }

    /**
     * @Route("/submitComment")
     */
    public function submitCommentAction() {
        $id = $this->getJsonParameter('id');
        $cate = $this->getJsonParameter('cate');
        $content = $this->getJsonParameter('content');

        if(empty($content)) {
            return $this->jsonError(1, '评论内容不能为空');
        }

        $comment_service = $this->get('comment_service');
        $comment_service->addComment($this->getUserSession('uid'), $cate, $id, $content);

        return $this->jsonSuccess('评论');
    }
}