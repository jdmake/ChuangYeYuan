<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/15
// +----------------------------------------------------------------------


namespace AdminBundle\Service;


use AdminBundle\Traits\EntryPaginatorTrait;

class CategoryService extends AbsService
{
    use EntryPaginatorTrait;

    /**
     * 获取全部需求分类
     * @param bool $reverse
     * @return \AppBundle\Entity\PioneerparkXuqiuCategory[]|object[]
     */
    public function findAllChoiceByXuQiu($reverse = false)
    {
        $entryList = $this->getDoctrine()->getRepository('AppBundle:PioneerparkXuqiuCategory')
        ->findAll();
        $results = [];
        foreach ($entryList as $item) {
            if(!$reverse) {
                $results[$item->getId()] = $item->getTitle();
            }else {
                $results[$item->getTitle()] = $item->getId();
            }
        }
        return $results;
    }

    /**
     * 获取全部动态分类
     * @param bool $reverse
     * @return \AppBundle\Entity\PioneerparkXuqiuCategory[]|object[]
     */
    public function findAllChoiceByDongTai($reverse = false)
    {
        $entryList = $this->getDoctrine()->getRepository('AppBundle:PioneerparkDongtaiCategory')
        ->findAll();
        $results = [];
        foreach ($entryList as $item) {
            if(!$reverse) {
                $results[$item->getId()] = $item->getTitle();
            }else {
                $results[$item->getTitle()] = $item->getId();
            }
        }
        return $results;
    }
}