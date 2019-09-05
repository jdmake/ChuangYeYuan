<?php

namespace AppBundle\Entity;

use AppBundle\Custom\Entry\AbsEntry;

/**
 * PioneerparkDongtai
 */
class PioneerparkDongtai extends AbsEntry
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $uid;

    /**
     * @var integer
     */
    private $mid;

    /**
     * @var integer
     */
    private $cate;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $tag;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $picture;

    /**
     * @var boolean
     */
    private $isquality;

    /**
     * @var boolean
     */
    private $isrecommend;

    /**
     * @var \DateTime
     */
    private $createAt;

    /**
     * @var integer
     */
    private $status;


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
     * Set uid
     *
     * @param integer $uid
     *
     * @return PioneerparkDongtai
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return integer
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set mid
     *
     * @param integer $mid
     *
     * @return PioneerparkDongtai
     */
    public function setMid($mid)
    {
        $this->mid = $mid;

        return $this;
    }

    /**
     * Get mid
     *
     * @return integer
     */
    public function getMid()
    {
        return $this->mid;
    }

    /**
     * Set cate
     *
     * @param integer $cate
     *
     * @return PioneerparkDongtai
     */
    public function setCate($cate)
    {
        $this->cate = $cate;

        return $this;
    }

    /**
     * Get cate
     *
     * @return integer
     */
    public function getCate()
    {
        return $this->cate;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return PioneerparkDongtai
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
     * Set tag
     *
     * @param string $tag
     *
     * @return PioneerparkDongtai
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return PioneerparkDongtai
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
     * Set picture
     *
     * @param string $picture
     *
     * @return PioneerparkDongtai
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
     * Set isquality
     *
     * @param boolean $isquality
     *
     * @return PioneerparkDongtai
     */
    public function setIsquality($isquality)
    {
        $this->isquality = $isquality;

        return $this;
    }

    /**
     * Get isquality
     *
     * @return boolean
     */
    public function getIsquality()
    {
        return $this->isquality;
    }

    /**
     * Set isrecommend
     *
     * @param boolean $isrecommend
     *
     * @return PioneerparkDongtai
     */
    public function setIsrecommend($isrecommend)
    {
        $this->isrecommend = $isrecommend;

        return $this;
    }

    /**
     * Get isrecommend
     *
     * @return boolean
     */
    public function getIsrecommend()
    {
        return $this->isrecommend;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return PioneerparkDongtai
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt->getTimestamp();
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return PioneerparkDongtai
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * @var integer
     */
    private $visit;


    /**
     * Set visit
     *
     * @param integer $visit
     *
     * @return PioneerparkDongtai
     */
    public function setVisit($visit)
    {
        $this->visit = $visit;
        return $this;
    }

    /**
     * Get visit
     *
     * @return integer
     */
    public function getVisit()
    {
        return $this->visit;
    }
}
