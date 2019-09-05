<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/19
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1;


use AppBundle\Controller\Common\CommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/v1/search")
 */
class SearchController extends CommonController
{
    /**
     * @Route("/")
     */
    public function searchAction()
    {
        $page = $this->request()->get('page', 1);
        $searchValue = $this->request()->get('searchValue', '');

        // 搜索需求
        $needs_service = $this->get('needs_service');
        $needs_list = $needs_service->searchPageList($searchValue, $page, 15);
        foreach ($needs_list as &$item) {
            $item['abstract'] = $this->_cutstr_html($item['content']);
        }

        // 搜索动态
        $dynamic_service = $this->get('dynamic_service');
        $dynamic_list = $dynamic_service->searchPageList($searchValue, $page, 15);
        foreach ($dynamic_list as &$item) {
            $item = $dynamic_service->toArray($item);
            $item['abstract'] = $this->_cutstr_html($item['content']);
        }

        return $this->jsonSuccess('搜索', [
            'needs' => $needs_list,
            'dynamics' => $dynamic_list,
        ]);
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