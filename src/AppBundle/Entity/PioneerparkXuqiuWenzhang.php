<?php

namespace AppBundle\Entity;

use AppBundle\Custom\Entry\AbsEntry;

/**
 * PioneerparkXuqiuWenzhang
 */
class PioneerparkXuqiuWenzhang extends AbsEntry
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $cate;

    /**
     * @var integer
     */
    private $mid;

    /**
     * @var integer
     */
    private $uid;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $subtitle;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $picture;

    /**
     * @var string
     */
    private $thumbnail;

    /**
     * @var boolean
     */
    private $isquality;

    /**
     * @var boolean
     */
    private $isrecommend;

    /**
     * @var string
     */
    private $contacts;

    /**
     * @var string
     */
    private $tel;

    /**
     * @var integer
     */
    private $status;

    /**
     * @var \DateTime
     */
    private $createAt;


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
     * Set cate
     *
     * @param integer $cate
     *
     * @return PioneerparkXuqiuWenzhang
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
     * Set mid
     *
     * @param integer $mid
     *
     * @return PioneerparkXuqiuWenzhang
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
     * Set uid
     *
     * @param integer $uid
     *
     * @return PioneerparkXuqiuWenzhang
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
     * Set title
     *
     * @param string $title
     *
     * @return PioneerparkXuqiuWenzhang
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
     * Set subtitle
     *
     * @param string $subtitle
     *
     * @return PioneerparkXuqiuWenzhang
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return PioneerparkXuqiuWenzhang
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
     * @return PioneerparkXuqiuWenzhang
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
     * Set thumbnail
     *
     * @param string $thumbnail
     *
     * @return PioneerparkXuqiuWenzhang
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * Set isquality
     *
     * @param boolean $isquality
     *
     * @return PioneerparkXuqiuWenzhang
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
     * @return PioneerparkXuqiuWenzhang
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
     * Set contacts
     *
     * @param string $contacts
     *
     * @return PioneerparkXuqiuWenzhang
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * Get contacts
     *
     * @return string
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return PioneerparkXuqiuWenzhang
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return PioneerparkXuqiuWenzhang
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
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return PioneerparkXuqiuWenzhang
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
        return $this->createAt;
    }

}
