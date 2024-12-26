<?php

namespace Auth\Domain\Dto;

use App\Auth\Domain\Dto\UserDTO;
use App\Auth\Domain\Models\User;
use App\Common\Domain\ValueObjects\Email;
use App\Common\Domain\ValueObjects\Password;
use Tests\TestCase;

class UserDTOTest extends TestCase
{
    public function testToArrayWithAllFields(): void
    {
        $dto = new UserDTO(
            id: 1,
            name: 'John Doe',
            email: 'john@example.com',
            roles: ['user'],
            provider: 'github',
            providerId: '12345'
        );

        $result = $dto->toArray();

        $this->assertEquals([
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'roles' => ['user'],
            'provider' => 'github',
            'providerId' => '12345'
        ], $result);
    }

    public function testToArrayWithoutOptionalFields(): void
    {
        $dto = new UserDTO(
            id: 1,
            name: 'John Doe',
            email: 'john@example.com',
            roles: ['user']
        );

        $result = $dto->toArray();

        $this->assertEquals([
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'roles' => ['user'],
            'provider' => null,
            'providerId' => null
        ], $result);
    }

    public function testFromUserWithRegularUser(): void
    {
        // Given
        $email = new Email('john@example.com');
        $password = Password::fromHash('hashed_password');
        $user = new User(
            id: 1,
            name: 'John Doe',
            email: $email,
            password: $password,
            roles: ['user']
        );

        $dto = UserDTO::fromUser($user);

        $this->assertEquals(1, $dto->id);
        $this->assertEquals('John Doe', $dto->name);
        $this->assertEquals('john@example.com', $dto->email);
        $this->assertEquals(['user'], $dto->roles);
        $this->assertNull($dto->provider);
        $this->assertNull($dto->providerId);
    }

    public function testFromUserWithOAuthUser(): void
    {
        $email = new Email('john@example.com');
        $user = User::createFromOAuth(
            name: 'John Doe',
            email: $email,
            provider: 'github',
            providerId: '12345',
            roles: ['user']
        );

        $dto = UserDTO::fromUser($user);

        $this->assertEquals(0, $dto->id);
        $this->assertEquals('John Doe', $dto->name);
        $this->assertEquals('john@example.com', $dto->email);
        $this->assertEquals(['user'], $dto->roles);
        $this->assertEquals('github', $dto->provider);
        $this->assertEquals('12345', $dto->providerId);
    }

    public function testFromUserWithMultipleRoles(): void
    {
        $email = new Email('john@example.com');
        $password = Password::fromHash('hashed_password');
        $user = new User(
            id: 1,
            name: 'John Doe',
            email: $email,
            password: $password,
            roles: ['user', 'admin']
        );

        $dto = UserDTO::fromUser($user);

        $this->assertEquals(['user', 'admin'], $dto->roles);
    }
}
