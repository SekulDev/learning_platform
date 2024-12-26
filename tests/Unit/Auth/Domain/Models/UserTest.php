<?php

namespace Auth\Domain\Models;

use App\Auth\Domain\Models\User;
use App\Common\Domain\ValueObjects\Email;
use App\Common\Domain\ValueObjects\Password;
use Tests\TestCase;

class UserTest extends TestCase
{
    private string $plainPassword = 'secret123';

    public function testUserCreation(): void
    {
        $email = new Email('test@example.com');
        $password = Password::fromPlaintext($this->plainPassword);

        $user = new User(
            id: 1,
            name: 'John Doe',
            email: $email,
            password: $password
        );

        $this->assertEquals(1, $user->getId());
        $this->assertEquals('John Doe', $user->getName());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($password, $user->getPassword());
        $this->assertEmpty($user->getRoles());
        $this->assertNull($user->getProvider());
        $this->assertNull($user->getProviderId());
    }

    public function testUserAuthentication(): void
    {
        $email = new Email('test@example.com');
        $password = Password::fromPlaintext($this->plainPassword);

        $user = new User(
            id: 1,
            name: 'John Doe',
            email: $email,
            password: $password
        );

        $this->assertTrue($user->authenticate($this->plainPassword));
        $this->assertFalse($user->authenticate('wrongpassword'));
    }

    public function testOAuthUserCannotAuthenticate(): void
    {
        $email = new Email('test@example.com');

        $user = User::createFromOAuth(
            name: 'John Doe',
            email: $email,
            provider: 'github',
            providerId: '12345',
            roles: ['user']
        );

        $this->assertFalse($user->authenticate($this->plainPassword));
    }

    public function testCreateFromOAuth(): void
    {
        $email = new Email('test@example.com');

        $roles = ['user'];
        $user = User::createFromOAuth(
            name: 'John Doe',
            email: $email,
            provider: 'github',
            providerId: '12345',
            roles: $roles
        );

        $this->assertEquals(0, $user->getId());
        $this->assertEquals('John Doe', $user->getName());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals('github', $user->getProvider());
        $this->assertEquals('12345', $user->getProviderId());
        $this->assertEquals($roles, $user->getRoles());
        $this->assertInstanceOf(Password::class, $user->getPassword());
    }

    public function testToArray(): void
    {
        $email = new Email('test@example.com');
        $password = Password::fromPlaintext($this->plainPassword);

        $user = new User(
            id: 1,
            name: 'John Doe',
            email: $email,
            password: $password
        );

        $expected = [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'test@example.com',
        ];

        $this->assertEquals($expected, $user->toArray());
    }

    public function testUserWithRoles(): void
    {
        $email = new Email('test@example.com');
        $password = Password::fromPlaintext($this->plainPassword);

        $roles = ['user', 'admin'];
        $user = new User(
            id: 1,
            name: 'John Doe',
            email: $email,
            password: $password,
            roles: $roles
        );

        $this->assertEquals($roles, $user->getRoles());
    }
}
