<?php
namespace Training\Application\Service\User\Access;

final class AuthenticateUserRequest
{
    private $username;
    private $password;

    /**
     * AuthenticateUserRequest constructor.
     * @param $username
     * @param $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function password()
    {
        return $this->password;
    }
}

