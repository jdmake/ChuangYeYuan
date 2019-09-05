<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/19
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1;




use AppBundle\Controller\Common\CommonController;
use AppBundle\Entity\PioneerparkRentRecord;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/v1/rent")
 */
class RentController extends CommonController
{
    /**
     * @Route("/getRentPageList")
     */
    public function getRentPageListAction()
    {
        $page = $this->request()->get('page');

        $rent_service = $this->get('rent_service');

        $uid = $this->getUserSession('uid');
        $records = $rent_service->getPageList($uid, $page, 15);

        return $this->jsonSuccess('获取交租记录', [
            'record' => $records
        ]);
    }
}