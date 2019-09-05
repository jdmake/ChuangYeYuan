<?php

namespace AppBundle\Entity;

use AppBundle\Custom\Entry\AbsEntry;

/**
 * PioneerparkRentRecord
 */
class PioneerparkRentRecord extends AbsEntry
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $uid;

    /**
     * @var int
     */
    private $merchantId;

    /**
     * @var float
     */
    private $total;

    /**
     * @var string
     */
    private $remarks;

    /**
     * @var int
     */
    private $status;

    /**
     * @var \DateTime
     */
    private $createAt;

    /**
     * @var \DateTime
     */
    private $completion;


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
     * Set uid.
     *
     * @param int $uid
     *
     * @return PioneerparkRentRecord
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid.
     *
     * @return int
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set merchantId.
     *
     * @param int $merchantId
     *
     * @return PioneerparkRentRecord
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;

        return $this;
    }

    /**
     * Get merchantId.
     *
     * @return int
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * Set total.
     *
     * @param float $total
     *
     * @return PioneerparkRentRecord
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total.
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set remarks.
     *
     * @param string $remarks
     *
     * @return PioneerparkRentRecord
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;

        return $this;
    }

    /**
     * Get remarks.
     *
     * @return string
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return PioneerparkRentRecord
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createAt.
     *
     * @param \DateTime $createAt
     *
     * @return PioneerparkRentRecord
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

    /**
     * Set completion.
     *
     * @param \DateTime $completion
     *
     * @return PioneerparkRentRecord
     */
    public function setCompletion($completion)
    {
        $this->completion = $completion;

        return $this;
    }

    /**
     * Get completion.
     *
     * @return \DateTime
     */
    public function getCompletion()
    {
        return $this->completion;
    }
}
