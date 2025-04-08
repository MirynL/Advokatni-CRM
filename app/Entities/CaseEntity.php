<?php

namespace App\Entities;

use App\Entities\CaseStatusEntity;
use App\Entities\CurrencyEntity;
use App\Entities\UserEntity;
use App\Models\CurrencyModel;

class CaseEntity
{
	

	private ?int $id;
	private string $caseNumber;
	private string $title;
	private ?string $description = null;
	private ?\DateTimeInterface $archivedAt = null;
	private CurrencyEntity $currency;
	private UserEntity $owner;
	private UserEntity $createdBy;
	private \DateTimeInterface $createdAt;
	private \DateTimeInterface $modifiedAt;
	private ?UserEntity $modifiedBy = null;
	private CaseStatusEntity $status;



	// ID
	public function getId(): ?int
	{
		return $this->id;
	}
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	// Case number
	public function getCaseNumber(): string
	{
		return $this->caseNumber;
	}
	public function setCaseNumber(string $caseNumber): void
	{
		$this->caseNumber = $caseNumber;
	}

	// Title
	public function getTitle(): string
	{
		return $this->title;
	}
	public function setTitle(string $title): void
	{
		$this->title = $title;
	}

	// Description
	public function getDescription(): ?string
	{
		return $this->description;
	}
	public function setDescription(?string $description): void
	{
		$this->description = $description;
	}

	// Archived at
	public function getArchivedAt(): ?\DateTimeInterface
	{
		return $this->archivedAt;
	}
	public function setArchivedAt(?\DateTimeInterface $archivedAt): void
	{
		$this->archivedAt = $archivedAt;
	}

	// Currency code
	public function getCurrency(): CurrencyEntity
	{
		return $this->currency;
	}
	public function setCurrency(CurrencyEntity $currency): void
	{
		$this->currency = $currency;
	}

	// Assigned to
	public function getOwner(): UserEntity
	{
		return $this->owner;
	}
	public function setOwner(UserEntity $owner): void
	{
		$this->owner = $owner;
	}

	// Created by
	public function getCreatedBy(): UserEntity
	{
		return $this->createdBy;
	}
	public function setCreatedBy(UserEntity $createdBy): void
	{
		$this->createdBy = $createdBy;
	}

	// Created at
	public function getCreatedAt(): \DateTimeInterface
	{
		return $this->createdAt;
	}
	public function setCreatedAt(\DateTimeInterface $createdAt): void
	{
		$this->createdAt = $createdAt;
	}

	// Modified at
	public function getModifiedAt(): \DateTimeInterface
	{
		return $this->modifiedAt;
	}
	public function setModifiedAt(\DateTimeInterface $modifiedAt): void
	{
		$this->modifiedAt = $modifiedAt;
	}

	// Modified by
	public function getModifiedBy(): ?UserEntity
	{
		return $this->modifiedBy;
	}
	public function setModifiedBy(?UserEntity $modifiedBy): void
	{
		$this->modifiedBy = $modifiedBy;
	}

	// Status ID
	public function getStatus(): CaseStatusEntity
	{
		return $this->status;
	}
	public function setStatus(CaseStatusEntity $status): void
	{
		$this->status = $status;
	}
}