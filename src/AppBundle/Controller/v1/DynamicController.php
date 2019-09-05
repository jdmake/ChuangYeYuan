<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/3
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1;


use AppBundle\Controller\Common\CommonController;
use AppBundle\Entity\PioneerparkDongtai;
use AppBundle\View\DynamicView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Route("/v1/dynamic")
 */
class DynamicController extends CommonController
{
    /**
     * @Route("/getCates", name="dynamic_getCates")
     */
    public function getCatesAction()
    {
        $dynamic_service = $this->get('dynamic_service');
        $cates = $dynamic_service->findAll();

        return $this->jsonSuccess('获取全部分类', $cates);
    }

    /**
     * @Route("/getPageList", name="dynamic_getPageList")
     */
    public function getPageListAction()
    {
        $cate = $this->getJsonParameter('cate');
        $page = $this->getJsonParameter('page', 1);

        $dynamic_service = $this->get('dynamic_service');
        $res = $dynamic_service->getPageList($cate, $page, 10);

        foreach ($res['list'] as &$re) {
            $re = $dynamic_service->toArray($re);
            $re['abstract'] = $this->_cutstr_html($re['content']);
            $re['picture'] = explode(',', $re['picture']);
        }

        return $this->jsonSuccess('获取动态分页列表', $res);
    }

    /**
     * @Route("/submit", name="dynamic_submit")
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function submitAction()
    {
        $cate = $this->getJsonParameter('cate');
        $title = $this->getJsonParameter('title');
        $content = $this->getJsonParameter('content');
        $picture = $this->getJsonParameter('picture');


        $errors = $this->validator([
            'cate' => $cate,
            'title' => $title,
            'content' => $content,
            'picture' => $picture,
        ], [
            'cate' => new NotBlank(['message' => '分类不能为空']),
            'title' => new NotBlank(['message' => '标题不能为空']),
            'content' => new NotBlank(['message' => '内容不能为空']),
            'picture' => new NotBlank(['message' => '缩略图不能为空']),
        ]);
        if (count($errors) > 0) {
            return $this->jsonError(1, array_shift($errors));
        }

        $dynamic_service = $this->get('dynamic_service');
        $uid = $this->getUserSession('uid');
        $mid = $this->get('merchant_service')->getIdByUid($uid);

        if (empty($uid)) {
            return $this->jsonError(1, '用户不存在');
        }
        if (empty($mid)) {
            return $this->jsonError(1, '商户不存在');
        }

        if ($dynamic_service->isExsitWaitHandle($uid)) {
            return $this->jsonError(1, '上次发布的内容正在审核中，暂时不可以在发布');
        }

        $res = $dynamic_service->createDongTaiData($uid, $mid, $cate, [
            'title' => $title,
            'content' => $content,
            'picture' => $picture,
        ]);

        return $this->jsonSuccess('提交发布', [
            'id' => $res
        ]);
    }

    /**
     * @Route("/getDetail", name="dynamic_getDetail")
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getDetailAction()
    {
        $id = $this->request()->get('id');

        $dynamic_service = $this->get('dynamic_service');
        $detail = $dynamic_service->getDetail($id);

        $merchant = $this->get('merchant_service')->getDetailById($detail['mid']);
        $detail['merchant_name'] = $merchant ? $merchant->getName() : '官方';

        // 增加浏览量
        $dynamic_service->setIncVisit($id);

        return $this->jsonSuccess('获取动态详情', $detail);
    }

    /**
     * @Route("/getNewDynamic")
     */
    public function getNewDynamicAction()
    {
        $dynamic_service = $this->get('dynamic_service');
        $res = $dynamic_service->getNewDynamic(10);

        foreach ($res as &$re) {
            $re = $dynamic_service->toArray($re);
            $re['abstract'] = $this->_cutstr_html($re['content']);
        }

        return $this->jsonSuccess('获取最新动态', $res);
    }

    private function _cutstr_html($string)
    {
        $string = strip_tags($string);
        $string = trim($string);
        $string = str_replace("\t", "", $string);
        $string = str_replace("\r\n", "", $string);
        $string = str_replace("\r", "", $string);
        $string = str_replace("\n", "", $string);
        $string = str_replace(" ", "", $string);
        return trim($string);
    }
}