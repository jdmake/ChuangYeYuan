<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/1
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1;


use AppBundle\Controller\Common\CommonController;
use AppBundle\Util\ModelFilterConfigUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Route("/v1/needs")
 */
class NeedsController extends CommonController
{
    /**
     * 获取分类
     * @Route("/getCate",  name="needs_getCate")
     */
    public function getCateAction()
    {
        $needs_service = $this->get('needs_service');
        $cates = $needs_service->findAllCate();
        return $this->jsonSuccess('获取分类', [
            'cates' => $cates
        ]);
    }

    /**
     * @Route("/getCates", name="needs_getcates")
     */
    public function getCatesAction()
    {

        $needs_service = $this->get('needs_service');

        $cates = $needs_service->findAllCate();
        foreach ($cates as &$cate) {
            if ($cate['model'] == 1) {
                // 招聘模型的筛选配置
                $cate['filterSelectItems'] = ModelFilterConfigUtil::$config[$cate['model']];

            } else if ($cate['model'] == 2) {
                // 文章模型的筛选配置
            }
        }

        $cates[0]['selected'] = true;
        return $this->jsonSuccess('获取需求Cates配置', [
            'cates' => $cates
        ]);
    }

    /**
     * @Route("/getPageList", name="needs_getpagelist")
     */
    public function getPageListAction()
    {
        $filterParams = json_decode($this->request()->getContent(), true);

        $needs_service = $this->get('needs_service');
        $pagination = $needs_service->getPageList($filterParams['form'], $filterParams['cate'], $filterParams['model'], $filterParams['page'], 10);

        foreach ($pagination['list'] as &$re) {
            $re = $needs_service->toArray($re);
            $re['abstract'] = $this->_cutstr_html($re['content']);
        }

        return $this->jsonSuccess('获取需求分页列表', $pagination);
    }

    /**
     * 获取招聘信息详情
     * @Route("/getZhaoPinDetail", name="needs_getZhaoPinDetail")
     */
    public function getZhaoPinDetailAction()
    {
        $id = $this->request()->get('id');
        $needs_service = $this->get('needs_service');

        $detail = $needs_service->toArray($needs_service->getZhaoPinDetail($id));
        $mercgant = $needs_service->getMerchant($detail['mid']);


        $detail['createAt'] = date('Y-m-d H:i:s', $detail['createAt']);
        $detail['name'] = $mercgant->getName();
        $detail['scope'] = $mercgant->getScope();
        $detail['logopic'] = $mercgant->getLogopic();
        $detail['duty'] = explode("\r\n", $detail['duty']);
        $detail['seniority'] = explode("\r\n", $detail['seniority']);

        return $this->jsonSuccess('获取招聘信息详情', $detail);
    }

    /**
     * 获取文章信息详情
     * @Route("/getWenZhangDetail", name="needs_getWenZhangDetail")
     */
    public function getWenZhangDetailAction()
    {
        $id = $this->request()->get('id');
        $needs_service = $this->get('needs_service');

        $detail = $needs_service->toArray($needs_service->getWenZhangDetail($id));
        $mercgant = $needs_service->getMerchant($detail['mid']);
        $detail['createAt'] = explode('.', $detail['createAt']['date'])[0];
        $detail['name'] = $mercgant ? $mercgant->getName() : '';
        $detail['scope'] = $mercgant ? $mercgant->getScope() : '';
        $detail['logopic'] = $mercgant ? $mercgant->getLogopic() : '';
        $detail['picture'] = explode(",", $detail['picture']);

        return $this->jsonSuccess('获取文章模型信息详情', $detail);
    }

    /**
     * 提交招聘发布
     * @Route("/submitZhaoPin", name="needs_submitZhaoPin", methods={"POST"})
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function submitZhaoPinAction()
    {
        $cate = $this->getJsonParameter('cate');
        $contacts = $this->getJsonParameter('contacts');
        $duty = $this->getJsonParameter('duty');
        $number = $this->getJsonParameter('number');
        $salarymax = $this->getJsonParameter('salarymax');
        $salarymin = $this->getJsonParameter('salarymin');
        $seniority = $this->getJsonParameter('seniority');
        $subtitle = $this->getJsonParameter('subtitle');
        $tel = $this->getJsonParameter('tel');
        $title = $this->getJsonParameter('title');
        $tag = $this->getJsonParameter('tag');
        $education = $this->getJsonParameter('education');
        $experience = $this->getJsonParameter('experience');

        $needs_service = $this->get('needs_service');
        $uid = $this->getUserSession('uid');
        $mid = $this->get('merchant_service')->getIdByUid($uid);

        $errors = $this->validator([
            'contacts' => $contacts,
            'duty' => $duty,
            'number' => $number,
            'salarymax' => $salarymax,
            'salarymin' => $salarymin,
            'seniority' => $seniority,
            'tel' => $tel,
            'title' => $title,
            'tag' => $tag,
        ], [
            'contacts' => new NotBlank(['message' => '联系人不能为空']),
            'duty' => new NotBlank(['message' => '工作职责不能为空']),
            'number' => new NotBlank(['message' => '招聘人数不能为空']),
            'salarymax' => new NotBlank(['message' => '最高薪水不能为空']),
            'salarymin' => new NotBlank(['message' => '最低薪水不能为空']),
            'seniority' => new NotBlank(['message' => '资历不能为空']),
            'tel' => new NotBlank(['message' => '联系电话不能为空']),
            'title' => new NotBlank(['message' => '标题不能为空']),
            'tag' => new NotBlank(['message' => '标签不能为空']),
        ]);
        if (count($errors) > 0) {
            return $this->jsonError(1, array_shift($errors));
        }

        if (empty($uid)) {
            return $this->jsonError(1, '用户不存在');
        }
        if (empty($mid)) {
            return $this->jsonError(1, '商户不存在');
        }

        $res = $needs_service->createZhaoPinData($uid, $mid, $cate, [
            'contacts' => $contacts,
            'duty' => $duty,
            'number' => $number,
            'salarymax' => $salarymax,
            'salarymin' => $salarymin,
            'seniority' => $seniority,
            'subtitle' => $subtitle,
            'tel' => $tel,
            'title' => $title,
            'tag' => $tag,
            'education' => $education,
            'experience' => $experience,
        ]);
        if ($res <= 0) {
            return $this->jsonError(1, '发布失败，请稍后再试');
        }

        return $this->jsonSuccess('提交招聘发布');
    }

    /**
     * 提交文章发布
     * @Route("/submitWenZhang", name="needs_submitWenZhang", methods={"POST"})
     */
    public function submitWenZhangAction()
    {
        $cate = $this->getJsonParameter('cate');
        $contacts = $this->getJsonParameter('contacts');
        $content = $this->getJsonParameter('content');
        $subtitle = $this->getJsonParameter('subtitle');
        $tel = $this->getJsonParameter('tel');
        $title = $this->getJsonParameter('title');
        $picture = $this->getJsonParameter('picture');
        $thumbnail = $this->getJsonParameter('thumbnail');

        $needs_service = $this->get('needs_service');
        $uid = $this->getUserSession('uid');
        $mid = $this->get('merchant_service')->getIdByUid($uid);

        $errors = $this->validator([
            'contacts' => $contacts,
            'content' => $content,
            'tel' => $tel,
            'title' => $title,
            'thumbnail' => $thumbnail,
        ], [
            'contacts' => new NotBlank(['message' => '联系人不能为空']),
            'content' => new NotBlank(['message' => '内容不能为空']),
            'tel' => new NotBlank(['message' => '联系电话不能为空']),
            'title' => new NotBlank(['message' => '标题不能为空']),
            'thumbnail' => new NotBlank(['message' => '缩略图不能为空']),
        ]);
        if (count($errors) > 0) {
            return $this->jsonError(1, array_shift($errors));
        }

        if (empty($uid)) {
            return $this->jsonError(1, '用户不存在');
        }
        if (empty($mid)) {
            return $this->jsonError(1, '商户不存在');
        }

        $res = $needs_service->createWenZhangData($uid, $mid, $cate, [
            'contacts' => $contacts,
            'content' => $content,
            'subtitle' => $subtitle,
            'tel' => $tel,
            'title' => $title,
            'picture' => $picture,
            'thumbnail' => $thumbnail,
        ]);
        if ($res <= 0) {
            return $this->jsonError(1, '发布失败，请稍后再试');
        }

        return $this->jsonSuccess('提交发布');
    }

    /**
     * @Route("/getNewNeeds")
     */
    public function getNewNeedsAction()
    {
        $needs_service = $this->get('needs_service');
        $res = $needs_service->getNewNeeds(10);

        foreach ($res as &$re) {
            $re = $needs_service->toArray($re);
            $re['abstract'] = $this->_cutstr_html($re['content']);
        }

        return $this->jsonSuccess('获取最新需求', $res);
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