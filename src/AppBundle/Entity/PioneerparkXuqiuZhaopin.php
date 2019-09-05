<?php

namespace AppBundle\Entity;

/**
 * PioneerparkXuqiuZhaopin
 */
class PioneerparkXuqiuZhaopin implements \JsonSerializable
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
    private $tag;

    /**
     * @var integer
     */
    private $salarymin;

    /**
     * @var integer
     */
    private $salarymax;

    /**
     * @var integer
     */
    private $number;

    /**
     * @var string
     */
    private $education;

    /**
     * @var string
     */
    private $experience;

    /**
     * @var string
     */
    private $duty;

    /**
     * @var string
     */
    private $seniority;

    /**
     * @var boolean
     */
    private $isquality;

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
     * @return PioneerparkXuqiuZhaopin
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
     * @return PioneerparkXuqiuZhaopin
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
     * @return PioneerparkXuqiuZhaopin
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
     * @return int
     */
    public function getCate()
    {
        return $this->cate;
    }

    /**
     * @param int $cate
     */
    public function setCate($cate)
    {
        $this->cate = $cate;
    }


    /**
     * Set tag
     *
     * @param string $tag
     *
     * @return PioneerparkXuqiuZhaopin
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
     * Set number
     *
     * @param integer $number
     *
     * @return PioneerparkXuqiuZhaopin
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
     * Set education
     *
     * @param string $education
     *
     * @return PioneerparkXuqiuZhaopin
     */
    public function setEducation($education)
    {
        $this->education = $education;

        return $this;
    }

    /**
     * Get education
     *
     * @return string
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * Set experience
     *
     * @param string $experience
     *
     * @return PioneerparkXuqiuZhaopin
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return string
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * Set duty
     *
     * @param string $duty
     *
     * @return PioneerparkXuqiuZhaopin
     */
    public function setDuty($duty)
    {
        $this->duty = $duty;

        return $this;
    }

    /**
     * Get duty
     *
     * @return string
     */
    public function getDuty()
    {
        return $this->duty;
    }

    /**
     * Set seniority
     *
     * @param string $seniority
     *
     * @return PioneerparkXuqiuZhaopin
     */
    public function setSeniority($seniority)
    {
        $this->seniority = $seniority;

        return $this;
    }

    /**
     * Get seniority
     *
     * @return string
     */
    public function getSeniority()
    {
        return $this->seniority;
    }

    /**
     * Set isquality
     *
     * @param boolean $isquality
     *
     * @return PioneerparkXuqiuZhaopin
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
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return PioneerparkXuqiuZhaopin
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

    /**
     * @return int
     */
    public function getMid()
    {
        return $this->mid;
    }

    /**
     * @param int $mid
     */
    public function setMid($mid)
    {
        $this->mid = $mid;
    }


    /**
     * Set salarymin
     *
     * @param integer $salarymin
     *
     * @return PioneerparkXuqiuZhaopin
     */
    public function setSalarymin($salarymin)
    {
        $this->salarymin = $salarymin;

        return $this;
    }

    /**
     * Get salarymin
     *
     * @return integer
     */
    public function getSalarymin()
    {
        return $this->salarymin;
    }

    /**
     * Set salarymax
     *
     * @param integer $salarymax
     *
     * @return PioneerparkXuqiuZhaopin
     */
    public function setSalarymax($salarymax)
    {
        $this->salarymax = $salarymax;

        return $this;
    }

    /**
     * Get salarymax
     *
     * @return integer
     */
    public function getSalarymax()
    {
        return $this->salarymax;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'cate' => $this->getCate(),
            'mid' => $this->getMid(),
            'uid' => $this->getUid(),
            'title' => $this->getTitle(),
            'subtitle' => $this->getSubtitle(),
            'tag' => explode(',', $this->getTag()),
            'salarymin' => $this->getSalarymin(),
            'salarymax' => $this->getSalarymax(),
            'number' => $this->getNumber(),
            'education' => $this->getEducation(),
            'experience' => $this->getExperience(),
            'duty' => $this->getDuty(),
            'seniority' => $this->getSeniority(),
            'isquality' => $this->getIsquality(),
            'contacts' => $this->getContacts(),
            'tel' => $this->getTel(),
            'visit' => $this->getVisit(),
            'createAt' => $this->getCreateAt()->getTimestamp(),
        ];
    }

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
     * Set isrecommend
     *
     * @param boolean $isrecommend
     *
     * @return PioneerparkXuqiuZhaopin
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
     * @return PioneerparkXuqiuZhaopin
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
     * @return PioneerparkXuqiuZhaopin
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
     * @return PioneerparkXuqiuZhaopin
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
     * @return PioneerparkXuqiuZhaopin
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
