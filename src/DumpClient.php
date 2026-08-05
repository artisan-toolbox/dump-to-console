<?php

declare(strict_types=1);

namespace ArtisanToolbox\DumpToConsole;

use Symfony\Component\VarDumper\Caster\ReflectionCaster;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Throwable;

class DumpClient
{
    public const string DEFAULT_HOST = 'tcp://127.0.0.1:9912';

    private const float CONNECTION_TIMEOUT = 0.05;

    /** @var resource|null */
    protected $socket;

    protected VarCloner $cloner;

    public function __construct(
        protected string $host = self::DEFAULT_HOST,
        ?VarCloner $cloner = null,
    ) {
        if (! str_contains($this->host, '://')) {
            $this->host = 'tcp://'.$this->host;
        }

        $this->cloner = $cloner ?? tap(new DumpCloner)->addCasters(
            ReflectionCaster::UNSET_CLOSURE_FILE_INFO,
        );
    }

    /** @param array<string, mixed> $context */
    public function dump(mixed $value, array $context = [], ?string $label = null): void
    {
        try {
            $data = $this->cloner->cloneVar($value);

            if ($label !== null) {
                $data = $data->withContext(['label' => $label]);
            }

            $payload = base64_encode(serialize([
                $data,
                array_filter(['timestamp' => microtime(true), ...$context]),
            ]))."\n";

            $wasConnected = is_resource($this->socket);

            if ($this->write($payload)) {
                return;
            }

            $this->disconnect();

            if ($wasConnected) {
                $this->write($payload);
            }
        } catch (Throwable) {
            $this->disconnect();
        }
    }

    protected function write(string $payload): bool
    {
        if (! is_resource($this->socket) && ! $this->connect()) {
            return false;
        }

        $length = strlen($payload);
        $written = 0;

        while ($written < $length) {
            $bytes = @fwrite($this->socket, substr($payload, $written));

            if (! $bytes) {
                return false;
            }

            $written += $bytes;
        }

        return true;
    }

    protected function connect(): bool
    {
        $socket = @stream_socket_client(
            $this->host,
            $errorCode,
            $errorMessage,
            self::CONNECTION_TIMEOUT,
        );

        if (! is_resource($socket)) {
            return false;
        }

        if (! @stream_set_blocking($socket, false)) {
            @fclose($socket);

            return false;
        }

        $this->socket = $socket;

        return true;
    }

    protected function disconnect(): void
    {
        if (is_resource($this->socket)) {
            @fclose($this->socket);
        }

        $this->socket = null;
    }

    public function __destruct()
    {
        $this->disconnect();
    }
}
