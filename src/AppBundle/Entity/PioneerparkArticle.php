<?php

namespace AppBundle\Entity;

use AppBundle\Custom\Entry\AbsEntry;

/**
 * PioneerparkArticle
 */
class PioneerparkArticle extends AbsEntry
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $cate;

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
    private $thumbnail;

    /**
     * @var boolean
     */
    private $isdisplay;

    /**
     * @var int
     */
    private $visit;

    /**
     * @var \DateTime
     */
    private $createAt;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return PioneerparkArticle
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set cate.
     *
     * @param int $cate
     *
     * @return PioneerparkArticle
     */
    public function setCate($cate)
    {
        $this->cate = $cate;

        return $this;
    }

    /**
     * Get cate.
     *
     * @return int
     */
    public function getCate()
    {
        return $this->cate;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return PioneerparkArticle
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set subtitle.
     *
     * @param string $subtitle
     *
     * @return PioneerparkArticle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle.
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return PioneerparkArticle
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set thumbnail.
     *
     * @param string $thumbnail
     *
     * @return PioneerparkArticle
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * Get thumbnail.
     *
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * Set isdisplay.
     *
     * @param boolean $isdisplay
     *
     * @return PioneerparkArticle
     */
    public function setIsdisplay($isdisplay)
    {
        $this->isdisplay = $isdisplay;

        return $this;
    }

    /**
     * Get isdisplay.
     *
     * @return boolean
     */
    public function getIsdisplay()
    {
        return $this->isdisplay;
    }

    /**
     * Set visit.
     *
     * @param int $visit
     *
     * @return PioneerparkArticle
     */
    public function setVisit($visit)
    {
        $this->visit = $visit;

        return $this;
    }

    /**
     * Get visit.
     *
     * @return int
     */
    public function getVisit()
    {
        return $this->visit;
    }

    /**
     * Set createAt.
     *
     * @param \DateTime $createAt
     *
     * @return PioneerparkArticle
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt.
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }
}
