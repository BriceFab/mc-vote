<?php

namespace App\Entity;

use App\Classes\Enum\EnumParamType;
use App\Entity\Traits\UpdateCreateTrait;
use App\Repository\ParameterRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ParameterRepository::class)
 * @Vich\Uploadable
 */
class Parameter
{
    use UpdateCreateTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $param_key;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $value;

    /**
     * @Vich\UploadableField(mapping="param_file", fileNameProperty="value")
     * @var File
     */
    private $file;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $expirationDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $param_type = EnumParamType::STRING;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParamKey(): ?string
    {
        return $this->param_key;
    }

    public function setParamKey(string $param_key): self
    {
        $this->param_key = $param_key;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getFormattedValue()
    {
        $value = $this->value;

        switch ($this->getParamType()) {
            default:
                return $value;
            case EnumParamType::BOOLEAN:
                return $value === "1";
        }
    }

    public function setValue($value): self
    {
        $stringValue = $value;

        if ($this->getParamType() === EnumParamType::BOOLEAN) {
            $stringValue = ($value ? "1" : "0");
        }

        $this->value = $stringValue;

        return $this;
    }

    public function setFile(File $file = null)
    {
        $this->file = $file;

        if ($file) {
            $this->setUpdatedAt(new DateTime('now'));
        }
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function __toString()
    {
        return $this->getParamKey();
    }

    public function getExpirationDate(): ?DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(?DateTimeInterface $expirationDate): self
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function getParamType(): ?string
    {
        return $this->param_type;
    }

    public function setParamType(string $param_type): self
    {
        $this->param_type = $param_type;

        return $this;
    }

}
