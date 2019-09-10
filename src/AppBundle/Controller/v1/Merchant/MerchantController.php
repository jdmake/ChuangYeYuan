<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/31
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1\Merchant;


use AppBundle\Controller\Common\CommonController;
use AppBundle\Service\ArticleService;
use AppBundle\Service\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Article;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Route("/v1/merchant", name="merchant_join")
 */
class MerchantController extends CommonController
{

    /**
     * 获取商户商铺内容分类
     * @Route("/getShopCates")
     */
    public function getShopCatesAction()
    {
        $article_service = $this->get('article_service');
        $list = $article_service->getCates(ArticleService::MERCHANT);
        if($list) {
            $list[0]['selected'] = true;
        }
        return $this->jsonSuccess('获取商户商铺内容分类', $list);
    }

    /**
     * 获取商户商铺内容分页列表
     * @Route("/getShopPageList")
     */
    public function getShopPageListAction()
    {
        $page = $this->request()->get('page', 1);
        $cate = $this->request()->get('cate');

        $article_service = $this->get('article_service');
        $pagination = $article_service->getPageList(ArticleService::MERCHANT, $cate, $page, 10);

        return $this->jsonSuccess('获取商户商铺内容分页列表', $pagination);
    }

    /**
     * 企业入驻提交申请
     * @Route("/join", name="merchant_join", methods={"POST"})
     * @throws \Exception
     */
    public function joinAction()
    {
        $formId = $this->getJsonParameter('formId');
        $contacts = $this->getJsonParameter('contacts');
        $creditCode = $this->getJsonParameter('creditCode');
        $licensePic = $this->getJsonParameter('licensePic');
        $logoPic = $this->getJsonParameter('logoPic');
        $monthlyRent = $this->getJsonParameter('monthlyRent');
        $name = $this->getJsonParameter('name');
        $scope = $this->getJsonParameter('scope');
        $startingTime = $this->getJsonParameter('startingTime');
        $tel = $this->getJsonParameter('tel');

        $jointime = $this->getJsonParameter('jointime');
        $capital = $this->getJsonParameter('capital');
        $legal = $this->getJsonParameter('legal');
        $staffcount = $this->getJsonParameter('staffcount');
        $needsarea = $this->getJsonParameter('needsarea');

        $data = [
            'contacts' => $contacts,
            'creditCode' => $creditCode,
            'licensePic' => $licensePic,
            'logoPic' => $logoPic,
            'monthlyRent' => $monthlyRent,
            'name' => $name,
            'scope' => $scope,
            'startingTime' => $startingTime,
            'tel' => $tel,
            'jointime' => $jointime,
            'capital' => $capital,
            'legal' => $legal,
            'staffcount' => $staffcount,
            'needsarea' => $needsarea,
        ];

        $error = $this->validator($data, [
            'contacts' => new NotBlank(['message' => '联系人不能为空']),
            'jointime' => new NotBlank(['message' => '入园时间不能为空']),
            'licensePic' => new NotBlank(['message' => '请上传营业执照']),
            'logoPic' => new NotBlank(['message' => '请上传公司logo']),
            'name' => new NotBlank(['message' => '企业名称不能为空']),
            'scope' => new NotBlank(['message' => '主营业务不能为空']),
            'legal' => new NotBlank(['message' => '法定代表人不能为空']),
            'tel' => new NotBlank(['message' => '联系电话不能为空']),
            'staffcount' => new NotBlank(['message' => '员工人数不能为空']),
            'needsarea' => new NotBlank(['message' => '面积需求不能为空']),
        ]);

        if (count($error) > 0) {
            return $this->jsonError(1, array_shift($error));
        }

        $uid = $this->getUserSession('uid');

        // 入库
        $merchant_service = $this->get('merchant_service');
        if ($merchant_service->getDetailByUid($uid)) {
            return $this->jsonError(1, '已经提交过申请了');
        }
        $id = $merchant_service->createJoinRecord($uid, $data);

        // 保存推送码
        $this->get('member_service')->saveFormId($uid, $formId);

        return $this->jsonSuccess('企业入驻提交申请', [
            'mid' => $id
        ]);
    }
}