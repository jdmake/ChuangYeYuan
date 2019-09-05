<?php

namespace AppBundle\Entity;

use AppBundle\Custom\Entry\AbsEntry;

/**
 * PioneerparkMeetingroom
 */
class PioneerparkMeetingroom extends AbsEntry
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var float
     */
    private $price;

    /**
     * @var integer
     */
    private $number;

    /**
     * @var string
     */
    private $content;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return PioneerparkMeetingroom
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return PioneerparkMeetingroom
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set number
     *
     * @param integer $number
     *
     * @return PioneerparkMeetingroom
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return PioneerparkMeetingroom
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
    /**
     * @var string
     */
    private $picture;


    /**
     * Set picture
     *
     * @param string $picture
     *
     * @return PioneerparkMeetingroom
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }
    /**
     * @var integer
     */
    private $sort;


    /**
     * Set sort
     *
     * @param integer $sort
     *
     * @return PioneerparkMeetingroom
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer
     */
    public function getSort()
    {
        return $this->sort;
    }
    /**
     * @var string
     */
    private $starttime;

    /**
     * @var string
     */
    private $endtime;

    /**
     * @var integer
     */
    private $stepminute;


    /**
     * Set starttime
     *
     * @param string $starttime
     *
     * @return PioneerparkMeetingroom
     */
    public function setStarttime($starttime)
    {
        $this->starttime = $starttime;

        return $this;
    }

    /**
     * Get starttime
     *
     * @return string
     */
    public function getStarttime()
    {
        return $this->starttime;
    }

    /**
     * Set endtime
     *
     * @param string $endtime
     *
     * @return PioneerparkMeetingroom
     */
    public function setEndtime($endtime)
    {
        $this->endtime = $endtime;

        return $this;
    }

    /**
     * Get endtime
     *
     * @return string
     */
    public function getEndtime()
    {
        return $this->endtime;
    }

    /**
     * Set stepminute
     *
     * @param integer $stepminute
     *
     * @return PioneerparkMeetingroom
     */
    public function setStepminute($stepminute)
    {
        $this->stepminute = $stepminute;

        return $this;
    }

    /**
     * Get stepminute
     *
     * @return integer
     */
    public function getStepminute()
    {
        return $this->stepminute;
    }
}
