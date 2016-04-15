<?php

namespace Training\Application\Service\User\Access;

use Training\Application\Service\User\Create\CreateUserServiceException;
use Training\Domain\Model\User\Identity\User;
use Training\Domain\Model\User\Identity\UserRepository;

final class AuthenticateUserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(AuthenticateUserRequest $request)
    {
        try {
            /* @var $user User */ 
            $user = $this->userRepository->findOneByUsername($request->username());
            $user->authenticate($request->password(), $user->passwordHash(), 'password_verify');

            return new AuthenticateUserResponse($user);

        } catch (\Exception $exception) {
            throw new AuthenticateUserServiceException($exception->getMessage());
        }
    }
    
    
}
