<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30.10.18
 * Time: 22:42
 */

namespace App\Entities;

use App\Repositories\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="App\Repositories\EmailSendingRepository")
 * @Table(name="email_sendings")
 */
class EmailSending
{
    /**
     * @Id()
     * @GeneratedValue()
     * @Column(type="integer")
     */
    private $id;

    /**
     * @Column(type="string")
     */
    private $name;

    /**
     * @Column(type="text")
     */
    private $text;

    /**
     * @Column(type="json")
     */
    private $emails;

    /**
     * @Column(type="datetime")
     */
    private $created_at;

    /**
     * @Column(type="datetime")
     */
    private $updated_at;

    /**
     * @Column(type="datetime")
     */
    private $deleted_at = null;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }


    /**
     * @return mixed
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * @param mixed $emails
     */
    public function setEmails($emails): void
    {
        $this->emails = $emails;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }

    /**
     * @param mixed $deleted_at
     */
    public function setDeletedAt($deleted_at): void
    {
        $this->deleted_at = $deleted_at;
    }

}