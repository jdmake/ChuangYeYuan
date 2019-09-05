<?php

namespace AppBundle\Entity;

use AppBundle\Custom\Entry\AbsEntry;

/**
 * PioneerparkMemberProfile
 */
class PioneerparkMemberProfile extends AbsEntry
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $avatar;

    /**
     * @var \DateTime
     */
    private $birthday;

    /**
     * @var boolean
     */
    private $gender;

    /**
     * @var string
     */
    private $idcard;

    /**
     * @var string
     */
    private $nickname;

    /**
     * @var string
     */
    private $realname;

    /**
     * @var string
     */
    private $city;


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
     * Set avatar
     *
     * @param string $avatar
     *
     * @return PioneerparkMemberProfile
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return PioneerparkMemberProfile
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set gender
     *
     * @param boolean $gender
     *
     * @return PioneerparkMemberProfile
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return boolean
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set idcard
     *
     * @param string $idcard
     *
     * @return PioneerparkMemberProfile
     */
    public function setIdcard($idcard)
    {
        $this->idcard = $idcard;

        return $this;
    }

    /**
     * Get idcard
     *
     * @return string
     */
    public function getIdcard()
    {
        return $this->idcard;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     *
     * @return PioneerparkMemberProfile
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set realname
     *
     * @param string $realname
     *
     * @return PioneerparkMemberProfile
     */
    public function setRealname($realname)
    {
        $this->realname = $realname;

        return $this;
    }

    /**
     * Get realname
     *
     * @return string
     */
    public function getRealname()
    {
        return $this->realname;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return PioneerparkMemberProfile
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }
}
