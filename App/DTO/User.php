<?php

namespace App\DTO;

class User {

    private int $id;

    private string $login;

    private string $password;
    private string $token_id;

    private int $department_id;
    private int $room_id;
    private int $group_id;

    private string $photo_path;

    private string $name;
    private string $lastname;
    private string $phone;

    /**
     * @param int $id
     * @param string $login
     * @param string $password
     * @param string $token_id
     * @param int $department_id
     * @param int $room_id
     * @param int $group_id
     * @param string $photo_path
     * @param string $name
     * @param string $lastname
     * @param string $phone
     */
    public function __construct(int $id, string $login, string $password, string $token_id, int $department_id, int $room_id, int $group_id, string $photo_path, string $name, string $lastname, string $phone)
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->token_id = $token_id;
        $this->department_id = $department_id;
        $this->room_id = $room_id;
        $this->group_id = $group_id;
        $this->photo_path = $photo_path;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->phone = $phone;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getTokenId(): string
    {
        return $this->token_id;
    }

    /**
     * @param string $token_id
     */
    public function setTokenId(string $token_id): void
    {
        $this->token_id = $token_id;
    }

    /**
     * @return int
     */
    public function getDepartmentId(): int
    {
        return $this->department_id;
    }

    /**
     * @param int $department_id
     */
    public function setDepartmentId(int $department_id): void
    {
        $this->department_id = $department_id;
    }

    /**
     * @return int
     */
    public function getRoomId(): int
    {
        return $this->room_id;
    }

    /**
     * @param int $room_id
     */
    public function setRoomId(int $room_id): void
    {
        $this->room_id = $room_id;
    }

    /**
     * @return int
     */
    public function getGroupId(): int
    {
        return $this->group_id;
    }

    /**
     * @param int $group_id
     */
    public function setGroupId(int $group_id): void
    {
        $this->group_id = $group_id;
    }

    /**
     * @return string
     */
    public function getPhotoPath(): string
    {
        return $this->photo_path;
    }

    /**
     * @param string $photo_path
     */
    public function setPhotoPath(string $photo_path): void
    {
        $this->photo_path = $photo_path;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }




}