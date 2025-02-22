<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DoctrineMedicalAppointmentRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;

use function Symfony\Component\String\u;

#[ORM\UniqueConstraint(
    name: 'medical_appointment_reference_number_unique',
    fields: ['referenceNumber'],
)]
#[ORM\Entity(repositoryClass: DoctrineMedicalAppointmentRepository::class)]
class MedicalAppointment
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID)]
    private string $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private HealthSpecialist $practitioner;

    #[ORM\Column(nullable: false)]
    private DateTimeImmutable $openingAt;

    #[ORM\Column(nullable: false)]
    private DateTimeImmutable $closingAt;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private string $referenceNumber;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private string $firstName;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private string $foldedFirstName;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private string $lastName;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private string $foldedLastName;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $phone = null;

    #[ORM\Column(nullable: false)]
    private DateTimeImmutable $createdAt;

    public function __construct(
        HealthSpecialist $practitioner,
        DateTimeImmutable $openingAt,
        DateTimeImmutable $closingAt,
        string $referenceNumber,
        string $firstName,
        string $lastName,
    ) {
        $this->id = (string) Uuid::v4();
        $this->practitioner = $practitioner;
        $this->referenceNumber = $referenceNumber;
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
        $this->openingAt = $openingAt;
        $this->closingAt = $closingAt;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return Uuid::fromString($this->id);
    }

    public function getPractitioner(): HealthSpecialist
    {
        return $this->practitioner;
    }

    public function getOpeningAt(): DateTimeInterface
    {
        return $this->openingAt;
    }

    public function getClosingAt(): DateTimeInterface
    {
        return $this->closingAt;
    }

    public function getReferenceNumber(): string
    {
        return $this->referenceNumber;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
        $this->foldedFirstName = u($firstName)->folded()->lower()->toString();
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getFoldedFirstName(): string
    {
        return $this->foldedFirstName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
        $this->foldedLastName = u($lastName)->folded()->lower()->toString();
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFoldedLastName(): string
    {
        return $this->foldedLastName;
    }

    public function setEmail(string $email): void
    {
        if ($email === '') {
            throw new InvalidArgumentException('The email cannot be empty.');
        }

        $email = \filter_var($email, filter: \FILTER_VALIDATE_EMAIL, options: \FILTER_NULL_ON_FAILURE);

        if ($email === null) {
            throw new InvalidArgumentException('The email is not valid.');
        }

        $this->email = u($email)->lower()->toString();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setPhone(string $phone): void
    {
        if ($phone === '') {
            throw new InvalidArgumentException('The phone number cannot be empty.');
        }

        $this->phone = $phone;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
