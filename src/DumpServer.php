<?php

declare(strict_types=1);

namespace ArtisanToolbox\DumpToConsole;

use Psr\Log\LoggerInterface;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Server\DumpServer as SymfonyDumpServer;

class DumpServer
{
    protected SymfonyDumpServer $server;

    public function __construct(string $host = DumpClient::DEFAULT_HOST, ?LoggerInterface $logger = null)
    {
        $this->server = new SymfonyDumpServer($host, $logger);
    }

    public function start(): void
    {
        $this->server->start();
    }

    /** @param callable(Data, array<string, mixed>, int): void $callback */
    public function listen(callable $callback): void
    {
        $this->server->listen($callback);
    }

    public function host(): string
    {
        return $this->server->getHost();
    }
}
