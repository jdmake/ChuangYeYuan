<?php

namespace AppBundle\Entity;

use AppBundle\Custom\Entry\AbsEntry;

/**
 * PioneerparkSwiper
 */
class PioneerparkSwiper extends AbsEntry
{
    /**
     * @var integer
     */
    private $swiperId;

    /**
     * @var integer
     */
    private $swiperType;

    /**
     * @var integer
     */
    private $swiperSort;

    /**
     * @var string
     */
    private $swiperPicture;

    /**
     * @var string
     */
    private $swiperPath;


    /**
     * Get swiperId
     *
     * @return integer
     */
    public function getSwiperId()
    {
        return $this->swiperId;
    }

    /**
     * Set swiperType
     *
     * @param integer $swiperType
     *
     * @return PioneerparkSwiper
     */
    public function setSwiperType($swiperType)
    {
        $this->swiperType = $swiperType;

        return $this;
    }

    /**
     * Get swiperType
     *
     * @return integer
     */
    public function getSwiperType()
    {
        return $this->swiperType;
    }

    /**
     * Set swiperSort
     *
     * @param integer $swiperSort
     *
     * @return PioneerparkSwiper
     */
    public function setSwiperSort($swiperSort)
    {
        $this->swiperSort = $swiperSort;

        return $this;
    }

    /**
     * Get swiperSort
     *
     * @return integer
     */
    public function getSwiperSort()
    {
        return $this->swiperSort;
    }

    /**
     * Set swiperPicture
     *
     * @param string $swiperPicture
     *
     * @return PioneerparkSwiper
     */
    public function setSwiperPicture($swiperPicture)
    {
        $this->swiperPicture = $swiperPicture;

        return $this;
    }

    /**
     * Get swiperPicture
     *
     * @return string
     */
    public function getSwiperPicture()
    {
        return $this->swiperPicture;
    }

    /**
     * Set swiperPath
     *
     * @param string $swiperPath
     *
     * @return PioneerparkSwiper
     */
    public function setSwiperPath($swiperPath)
    {
        $this->swiperPath = $swiperPath;

        return $this;
    }

    /**
     * Get swiperPath
     *
     * @return string
     */
    public function getSwiperPath()
    {
        return $this->swiperPath;
    }
}

