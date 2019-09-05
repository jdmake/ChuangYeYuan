<?php

namespace AppBundle\Entity;

use AppBundle\Custom\Entry\AbsEntry;

/**
 * PioneerparkAdmin
 */
class PioneerparkAdmin extends AbsEntry
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username = '0';

    /**
     * @var string|null
     */
    private $psw;

    /**
     * @var int|null
     */
    private $roleId;

    /**
     * @var bool
     */
    private $status = '1';

    /**
     * @var int
     */
    private $createTime = '0';


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
     * Set username.
     *
     * @param string $username
     *
     * @return PioneerparkAdmin
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set psw.
     *
     * @param string|null $psw
     *
     * @return PioneerparkAdmin
     */
    public function setPsw($psw = null)
    {
        $this->psw = $psw;

        return $this;
    }

    /**
     * Get psw.
     *
     * @return string|null
     */
    public function getPsw()
    {
        return $this->psw;
    }

    /**
     * Set roleId.
     *
     * @param int|null $roleId
     *
     * @return PioneerparkAdmin
     */
    public function setRoleId($roleId = null)
    {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * Get roleId.
     *
     * @return int|null
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * Set status.
     *
     * @param bool $status
     *
     * @return PioneerparkAdmin
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createTime.
     *
     * @param int $createTime
     *
     * @return PioneerparkAdmin
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;

        return $this;
    }

    /**
     * Get createTime.
     *
     * @return int
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }
}
