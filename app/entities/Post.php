<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30.10.18
 * Time: 23:53
 */

namespace App\Entities;

/**
 * Class Post
 * @Entity(repositoryClass="App\Repositories\PostRepository")
 * @Table(name="posts")
 */
class Post
{
    /**
     * @Id()
     * @GeneratedValue()
     * @Column(type="integer")
     */
    private $id;

    /**
     * @Column(type="text")
     */
    private $message;

    /**
     * @Column(type="string")
     */
    private $title;

    /**
     * @Column(type="integer")
     */
    private $user_id;

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

//    RELATIONSHIPS

    /**
     * @ManyToOne(targetEntity="User", inversedBy="posts")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @var User
     */
    private $user;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [
            'message' => 'required|min:6',
        ];
    }

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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }


    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
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


    /**
     * Set user.
     *
     * @param \App\Entities\User $user
     *
     * @return Post
     */
    public function setUser(\App\Entities\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \App\Entities\User
     */
    public function getUser()
    {
        return $this->user;
    }


}