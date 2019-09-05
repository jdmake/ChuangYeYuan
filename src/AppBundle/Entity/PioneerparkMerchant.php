<?php

namespace AppBundle\Entity;

use AppBundle\Custom\Entry\AbsEntry;

/**
 * PioneerparkMerchant
 */
class PioneerparkMerchant extends AbsEntry
{
    /**
     * @var integer
     */
    private $uid;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $creditcode;

    /**
     * @var string
     */
    private $contacts;

    /**
     * @var string
     */
    private $tel;

    /**
     * @var string
     */
    private $scope;

    /**
     * @var integer
     */
    private $monthlyrent;

    /**
     * @var \DateTime
     */
    private $startingtime;

    /**
     * @var string
     */
    private $licensepic;

    /**
     * @var string
     */
    private $logopic;

    /**
     * @var integer
     */
    private $status;

    /**
     * @var \DateTime
     */
    private $createAt;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set uid
     *
     * @param integer $uid
     *
     * @return PioneerparkMerchant
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
     * Set name
     *
     * @param string $name
     *
     * @return PioneerparkMerchant
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set creditcode
     *
     * @param string $creditcode
     *
     * @return PioneerparkMerchant
     */
    public function setCreditcode($creditcode)
    {
        $this->creditcode = $creditcode;

        return $this;
    }

    /**
     * Get creditcode
     *
     * @return string
     */
    public function getCreditcode()
    {
        return $this->creditcode;
    }

    /**
     * Set contacts
     *
     * @param string $contacts
     *
     * @return PioneerparkMerchant
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
     * @return PioneerparkMerchant
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
     * Set scope
     *
     * @param string $scope
     *
     * @return PioneerparkMerchant
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set monthlyrent
     *
     * @param integer $monthlyrent
     *
     * @return PioneerparkMerchant
     */
    public function setMonthlyrent($monthlyrent)
    {
        $this->monthlyrent = $monthlyrent;

        return $this;
    }

    /**
     * Get monthlyrent
     *
     * @return integer
     */
    public function getMonthlyrent()
    {
        return $this->monthlyrent;
    }

    /**
     * Set startingtime
     *
     * @param \DateTime $startingtime
     *
     * @return PioneerparkMerchant
     */
    public function setStartingtime($startingtime)
    {
        $this->startingtime = $startingtime;

        return $this;
    }

    /**
     * Get startingtime
     *
     * @return \DateTime
     */
    public function getStartingtime()
    {
        return $this->startingtime;
    }

    /**
     * Set licensepic
     *
     * @param string $licensepic
     *
     * @return PioneerparkMerchant
     */
    public function setLicensepic($licensepic)
    {
        $this->licensepic = $licensepic;

        return $this;
    }

    /**
     * Get licensepic
     *
     * @return string
     */
    public function getLicensepic()
    {
        return $this->licensepic;
    }

    /**
     * Set logopic
     *
     * @param string $logopic
     *
     * @return PioneerparkMerchant
     */
    public function setLogopic($logopic)
    {
        $this->logopic = $logopic;

        return $this;
    }

    /**
     * Get logopic
     *
     * @return string
     */
    public function getLogopic()
    {
        return $this->logopic;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return PioneerparkMerchant
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
     * @return PioneerparkMerchant
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
