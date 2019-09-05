<?php

namespace AppBundle\Entity;

/**
 * PioneerparkCommentZanrecord
 */
class PioneerparkCommentZanrecord
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
    private $commentid;

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
     * @return PioneerparkCommentZanrecord
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
     * Set commentid
     *
     * @param integer $commentid
     *
     * @return PioneerparkCommentZanrecord
     */
    public function setCommentid($commentid)
    {
        $this->commentid = $commentid;

        return $this;
    }

    /**
     * Get commentid
     *
     * @return integer
     */
    public function getCommentid()
    {
        return $this->commentid;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return PioneerparkCommentZanrecord
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
