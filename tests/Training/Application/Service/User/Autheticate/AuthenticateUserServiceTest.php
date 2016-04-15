<?php

namespace Training\Application\Service\User\Create;

use Assert\Assertion;
use Mockery;
use Training\Application\Service\User\Access\AuthenticateUserRequest;
use Training\Application\Service\User\Access\AuthenticateUserService;
use Training\Application\Service\User\Access\AuthenticateUserServiceException;
use Training\Domain\Model\Credentials;
use Training\Domain\Model\FullName;
use Training\Domain\Model\User\Identity\User;
use Training\Domain\Model\User\Identity\UserId;
use Training\Infrastructure\Persistence\Doctrine\User\Identity\InMemoryUserRepository;

class AutheticateUserServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Mockery\MockInterface
     */
    private $userRepository;

    protected function setUp()
    {
        $this->userRepository = Mockery::mock('Training\Domain\Model\User\Identity\UserRepository');
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    public function testItShouldReturnAnExceptionWhenUsernameNotExists()
    {
        $this->expectException(AuthenticateUserServiceException::class);

        $this->userRepository->shouldReceive('findOneByUsername')->once()->andThrow('\Exception');
        $appService = new AuthenticateUserService($this->userRepository);
        $appService->execute($this->makeARequest());
    }


    public function testItShouldReturnAnExceptionWhenPasswordDontMatch()
    {
        $this->expectException(AuthenticateUserServiceException::class);

        $user = new User(new UserId(), new FullName('Sergio', 'Moreno'), new Credentials('smoreno', password_hash('smorenos', PASSWORD_DEFAULT)));
        $this->userRepository->shouldReceive('findOneByUsername')->times(1)->andReturn($user);

        $appService = new AuthenticateUserService($this->userRepository);
        $appService->execute($this->makeARequest());
    }


    public function testItShouldReturnAnUser()
    {
        $user = new User(new UserId(), new FullName('Sergio', 'Moreno'), new Credentials('smoreno', password_hash('smoreno', PASSWORD_DEFAULT)));
        $this->userRepository->shouldReceive('findOneByUsername')->times(1)->andReturn($user);

        $appService = new AuthenticateUserService($this->userRepository);
        $response = $appService->execute($this->makeARequest());

        $this->assertInstanceOf(User::class, $response->user());
    }

    /**
     * @param string $username
     * @param string $password
     * @return AuthenticateUserRequest
     */
    private function makeARequest(
        $username = 'smoreno',
        $password = 'smoreno'
    ) {
        return new AuthenticateUserRequest($username, $password);
    }
}
