<?php

namespace PhpMailer\Library\Snowflake;

use DateInvalidTimeZoneException;
use DateMalformedStringException;

final class Snowflake
{
    private static ?self $instance = null;

    private int $epoch;
    private int $workerId;
    private int $processId;
    private int $lastTimestamp = -1;
    private int $sequence = 0;

    private const SEQUENCE_BITS = 12;
    private const MAX_SEQUENCE = -1 ^ (-1 << self::SEQUENCE_BITS);

    private const LOCK_FILE = '/tmp/snowflake.lock';
    private $lockHandle;

    private function __construct()
    {
        $this->epoch = (int)(getenv('SNOWFLAKE_EPOCH') ?: 1609459200000);
        $this->workerId = ((int)(getenv('SNOWFLAKE_WORKER_ID') ?: 1)) & 0x1F;
        $this->processId = ((int)(getenv('SNOWFLAKE_PROCESS_ID') ?: 1)) & 0x1F;

        $this->checkClockDrift();

        $this->lockHandle = fopen(self::LOCK_FILE, 'c');
        if (!$this->lockHandle) {
            throw new \RuntimeException("Could not open lock file: " . self::LOCK_FILE);
        }
    }

    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    public function generate(): string
    {
        flock($this->lockHandle, LOCK_EX);

        $timestamp = $this->currentTimeMillis();

        if ($timestamp < $this->lastTimestamp) {
            flock($this->lockHandle, LOCK_UN);
            throw new \RuntimeException("Clock moved backwards. Refusing to generate ID.");
        }

        if ($timestamp === $this->lastTimestamp) {
            $this->sequence = ($this->sequence + 1) & self::MAX_SEQUENCE;
            if ($this->sequence === 0) {
                $timestamp = $this->waitNextMillis($timestamp);
            }
        } else {
            $this->sequence = 0;
        }

        $this->lastTimestamp = $timestamp;

        $id = (($timestamp - $this->epoch) << 22)
            | ($this->workerId << 17)
            | ($this->processId << 12)
            | $this->sequence;

        flock($this->lockHandle, LOCK_UN);

        return (string)$id;
    }

    /**
     * @throws DateInvalidTimeZoneException
     * @throws DateMalformedStringException
     */
    public function decode(string $id, string $format, ?string $timezone = null): array
    {
        $id = (int)$id;

        $timestamp = ($id >> 22) + $this->epoch;
        $workerId = ($id >> 17) & 0x1F;
        $processId = ($id >> 12) & 0x1F;
        $sequence = $id & 0xFFF;

        $dateTime = new \DateTimeImmutable('@' . (int)($timestamp / 1000));
        if ($timezone !== null) {
            $tz = new \DateTimeZone($timezone);
            $dateTime = $dateTime->setTimezone($tz);
        }

        return [
            'timestamp' => $timestamp,
            'datetime' => $dateTime->format($format),
            'workerId' => $workerId,
            'processId' => $processId,
            'sequence' => $sequence,
            'timezone' => $timezone,
        ];
    }


    private function waitNextMillis(int $current): int
    {
        $now = $this->currentTimeMillis();
        while ($now <= $current) {
            usleep(10);
            $now = $this->currentTimeMillis();
        }
        return $now;
    }

    private function currentTimeMillis(): int
    {
        return (int)(microtime(true) * 1000);
    }

    private function checkClockDrift(): void
    {


        $ntpTime = $this->getNtpTime();
        if ($ntpTime === null) {
            return;
        }

        error_log($ntpTime);

        $localTime = $this->currentTimeMillis();
        $drift = abs($localTime - $ntpTime);

        error_log($drift);

        if ($drift > 1000) {
            error_log("Warning: Clock drift detected. Local time is off by $drift ms.");
        }
    }

    private function getNtpTime(): ?int
    {
        $server = '0.de.pool.ntp.org';
        $port = 123;

        $sock = @fsockopen("udp://$server", $port, $errno, $errstr, 1);
        if (!$sock) return null;

        $msg = "\010" . str_repeat("\0", 47);
        fwrite($sock, $msg);
        $response = fread($sock, 48);
        fclose($sock);

        if (strlen($response) < 48) return null;

        $unpack = unpack('N12', $response);
        $timestamp = sprintf('%u', $unpack[9]);
        $ntpUnixTime = $timestamp - 2208988800;

        return (int)($ntpUnixTime * 1000);
    }

    public static function reset(): void
    {
        if (self::$instance && self::$instance->lockHandle) {
            fclose(self::$instance->lockHandle);
        }
        self::$instance = null;
    }
}
