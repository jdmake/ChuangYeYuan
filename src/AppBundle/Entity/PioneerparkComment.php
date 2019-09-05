<?php

namespace AppBundle\Entity;

use AppBundle\Custom\Entry\AbsEntry;

/**
 * PioneerparkComment
 */
class PioneerparkComment extends AbsEntry
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
    private $fromid;

    /**
     * @var integer
     */
    private $fromcate;

    /**
     * @var string
     */
    private $content;

    /**
     * @var integer
     */
    private $zantotal;

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
     * Set uid
     *
     * @param integer $uid
     *
     * @return PioneerparkComment
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
     * Set fromid
     *
     * @param integer $fromid
     *
     * @return PioneerparkComment
     */
    public function setFromid($fromid)
    {
        $this->fromid = $fromid;

        return $this;
    }

    /**
     * Get fromid
     *
     * @return integer
     */
    public function getFromid()
    {
        return $this->fromid;
    }

    /**
     * Set fromcate
     *
     * @param integer $fromcate
     *
     * @return PioneerparkComment
     */
    public function setFromcate($fromcate)
    {
        $this->fromcate = $fromcate;

        return $this;
    }

    /**
     * Get fromcate
     *
     * @return integer
     */
    public function getFromcate()
    {
        return $this->fromcate;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return PioneerparkComment
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
     * Set zantotal
     *
     * @param integer $zantotal
     *
     * @return PioneerparkComment
     */
    public function setZantotal($zantotal)
    {
        $this->zantotal = $zantotal;

        return $this;
    }

    /**
     * Get zantotal
     *
     * @return integer
     */
    public function getZantotal()
    {
        return $this->zantotal;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return PioneerparkComment
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
        return date('Y-m-d H:i:s', $this->createAt->getTimestamp());
    }
    /**
     * @var bool
     */
    private $enable = '0';


    /**
     * Set enable.
     *
     * @param bool $enable
     *
     * @return PioneerparkComment
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * Get enable.
     *
     * @return bool
     */
    public function getEnable()
    {
        return $this->enable;
    }
}
