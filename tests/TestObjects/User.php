<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\TestObjects;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class User
{
    private $name;
    private $email;
    private $description;
    private $roles = [];
    private $groups = [];
    private $birthdate;

    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setDescription(string $description = null): void
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getGroups(): array
    {
        return $this->groups;
    }

    public function setGroups(array $groups): void
    {
        $this->groups = $groups;
    }

    public function setBirthDate(\DateTime $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    public function getBirthDate(): ?\DateTime
    {
        return $this->birthdate;
    }
}
