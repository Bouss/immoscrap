<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    private string $googleId;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $refreshToken;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $accessToken;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private DateTime $accessTokenExpiresAt;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private bool $revoked = false;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $avatar;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private array $propertyAdSearchSettings = [];

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getGoogleId(): string
    {
        return $this->googleId;
    }

    /**
     * @param string $googleId
     *
     * @return User
     */
    public function setGoogleId(string $googleId): User
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @param string $refreshToken
     *
     * @return User
     */
    public function setRefreshToken(string $refreshToken): User
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     *
     * @return User
     */
    public function setAccessToken(string $accessToken): User
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getAccessTokenExpiresAt(): DateTime
    {
        return $this->accessTokenExpiresAt;
    }

    /**
     * @param DateTime $accessTokenExpiresAt
     *
     * @return User
     */
    public function setAccessTokenExpiresAt(DateTime $accessTokenExpiresAt): User
    {
        $this->accessTokenExpiresAt = $accessTokenExpiresAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRevoked(): bool
    {
        return $this->revoked;
    }

    /**
     * @param bool $revoked
     *
     * @return User
     */
    public function setRevoked(bool $revoked): User
    {
        $this->revoked = $revoked;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string|null $avatar
     *
     * @return User
     */
    public function setAvatar(?string $avatar): User
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return array
     */
    public function getPropertyAdSearchSettings(): array
    {
        return $this->propertyAdSearchSettings;
    }

    /**
     * @param array $propertyAdSearchSettings
     *
     * @return User
     */
    public function setPropertyAdSearchSettings(array $propertyAdSearchSettings): User
    {
        $this->propertyAdSearchSettings = $propertyAdSearchSettings;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     *
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPassword()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getSalt()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * @return bool
     */
    public function hasAccessTokenExpired(): bool
    {
        return $this->accessTokenExpiresAt <= new DateTime();
    }
}
