<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

trait UpdateCreateByTrait
{

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Ignore()
     */
    private $createdBy;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Ignore()
     */
    private $updatedBy;

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?string $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?string $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

}
