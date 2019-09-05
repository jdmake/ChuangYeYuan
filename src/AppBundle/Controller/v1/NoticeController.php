<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/10
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1;


use AppBundle\Controller\Common\CommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/v1/notice")
 */
class NoticeController extends CommonController
{
    /**
     * 获取公告分页列表
     * @Route("/getPageList")
     */
    public function getPageListAction()
    {
        $page = $this->request()->get('page', 1);

        $notice_service = $this->get('notice_service');

        $pagination = $notice_service->getPageList($page, 10);

        return $this->jsonSuccess('获取公告分页列表', $pagination);
    }

    /**
     * @Route("/getNewNotice")
     */
    public function getNewNoticeAction()
    {
        $notice_service = $this->get('notice_service');
        $res = $notice_service->findNewNotice();

        return $this->jsonSuccess('获取最新公告', [
            'list' => $res
        ]);
    }

    /**
     * @Route("/getDetail")
     */
    public function getDetailAction()
    {
        $id = $this->request()->get('id');

        $notice_service = $this->get('notice_service');
        $res = $notice_service->findNoticeById($id);

        return $this->jsonSuccess('获取最新公告', $res);
    }
}