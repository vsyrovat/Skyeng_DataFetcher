<?php declare(strict_types=1);

namespace DataProvider;

interface DataProviderInterface
{
    public function setParameters(string $url, ?string $user = null, ?string $password = null): void;

    public function getResponse(array $input): string;

    public function getUrl(): string;

    public function getUser(): ?string;

    public function getPassword(): ?string;
}
