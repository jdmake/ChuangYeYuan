<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/9/5
// +----------------------------------------------------------------------


namespace YuZhi\TableingBundle\Tableing\Components;


class Enumeration implements TableComponentInterface
{
    private $cates;

    /**
     * Enumeration constructor.
     * @param $cates
     */
    public function __construct($cates)
    {
        $this->cates = $cates;
    }


    public function render($pk_value, $value)
    {
        return $this->cates[$value];
    }
}