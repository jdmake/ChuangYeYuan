<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/6
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1;


use AppBundle\Controller\Common\CommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Route("/v1/swiper")
 */
class SwiperController  extends CommonController
{
    /**
     * @Route("/index", name="swiper_index")
     */
    public function indexAction() {
        $res = $this->getDoctrine()->getRepository('AppBundle:PioneerparkSwiper')
            ->findAll();
        return $this->jsonSuccess('获取轮播图', $res);
    }

}