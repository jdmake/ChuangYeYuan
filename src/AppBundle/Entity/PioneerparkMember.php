<?php

namespace AppBundle\Entity;

use AppBundle\Custom\Entry\AbsEntry;

/**
 * PioneerparkMember
 */
class PioneerparkMember extends AbsEntry
{
    /**
     * @var integer
     */
    private $uid;

    /**
     * @var boolean
     */
    private $isenterprise;

    /**
     * @var string
     */
    private $openid;

    /**
     * @var string
     */
    private $mobile;

    /**
     * @var integer
     */
    private $level = '1';

    /**
     * @var integer
     */
    private $parentid;

    /**
     * @var float
     */
    private $credit;

    /**
     * @var string
     */
    private $lastloginip;

    /**
     * @var \DateTime
     */
    private $lastlogintime;

    /**
     * @var integer
     */
    private $profileid;

    /**
     * @var \DateTime
     */
    private $regtime;

    /**
     * @var boolean
     */
    private $enable;


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
     * Set isenterprise
     *
     * @param boolean $isenterprise
     *
     * @return PioneerparkMember
     */
    public function setIsenterprise($isenterprise)
    {
        $this->isenterprise = $isenterprise;

        return $this;
    }

    /**
     * Get isenterprise
     *
     * @return boolean
     */
    public function getIsenterprise()
    {
        return $this->isenterprise;
    }

    /**
     * Set openid
     *
     * @param string $openid
     *
     * @return PioneerparkMember
     */
    public function setOpenid($openid)
    {
        $this->openid = $openid;

        return $this;
    }

    /**
     * Get openid
     *
     * @return string
     */
    public function getOpenid()
    {
        return $this->openid;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return PioneerparkMember
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return PioneerparkMember
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set parentid
     *
     * @param integer $parentid
     *
     * @return PioneerparkMember
     */
    public function setParentid($parentid)
    {
        $this->parentid = $parentid;

        return $this;
    }

    /**
     * Get parentid
     *
     * @return integer
     */
    public function getParentid()
    {
        return $this->parentid;
    }

    /**
     * Set credit
     *
     * @param float $credit
     *
     * @return PioneerparkMember
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return float
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set lastloginip
     *
     * @param string $lastloginip
     *
     * @return PioneerparkMember
     */
    public function setLastloginip($lastloginip)
    {
        $this->lastloginip = $lastloginip;

        return $this;
    }

    /**
     * Get lastloginip
     *
     * @return string
     */
    public function getLastloginip()
    {
        return $this->lastloginip;
    }

    /**
     * Set lastlogintime
     *
     * @param \DateTime $lastlogintime
     *
     * @return PioneerparkMember
     */
    public function setLastlogintime($lastlogintime)
    {
        $this->lastlogintime = $lastlogintime;

        return $this;
    }

    /**
     * Get lastlogintime
     *
     * @return \DateTime
     */
    public function getLastlogintime()
    {
        return $this->lastlogintime;
    }

    /**
     * Set profileid
     *
     * @param integer $profileid
     *
     * @return PioneerparkMember
     */
    public function setProfileid($profileid)
    {
        $this->profileid = $profileid;

        return $this;
    }

    /**
     * Get profileid
     *
     * @return integer
     */
    public function getProfileid()
    {
        return $this->profileid;
    }

    /**
     * Set regtime
     *
     * @param \DateTime $regtime
     *
     * @return PioneerparkMember
     */
    public function setRegtime($regtime)
    {
        $this->regtime = $regtime;

        return $this;
    }

    /**
     * Get regtime
     *
     * @return \DateTime
     */
    public function getRegtime()
    {
        return $this->regtime;
    }

    /**
     * Set enable
     *
     * @param boolean $enable
     *
     * @return PioneerparkMember
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * Get enable
     *
     * @return boolean
     */
    public function getEnable()
    {
        return $this->enable;
    }


    /**
     * @var string
     */
    private $formid;


    /**
     * Set formid.
     *
     * @param string $formid
     *
     * @return PioneerparkMember
     */
    public function setFormid($formid)
    {
        $this->formid = $formid;

        return $this;
    }

    /**
     * Get formid.
     *
     * @return string
     */
    public function getFormid()
    {
        return $this->formid;
    }
}
