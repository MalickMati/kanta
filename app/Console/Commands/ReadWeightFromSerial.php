<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ReadWeightFromSerial extends Command
{
    protected $signature = 'weight:listen
        {--device= : Serial device path, e.g. /dev/ttyUSB0}
        {--baud=9600 : Baud rate}
        {--cache-key=current_weight : Cache key to write}
        {--ttl=5 : Cache TTL in seconds}
        {--pattern=/([+-]?\d+(?:[.,]\d+)?)/ : Regex to extract the numeric weight}
        {--sleep=200 : Sleep in ms when idle (backup throttle)}';

    protected $description = 'Read weight data from a serial port and cache it for web access';

    /** @var resource|null */
    private $handle = null;

    public function handle()
    {
        // Resolve options
        $device    = $this->option('device') ?: $this->detectSerialPort();
        $baud      = (int) $this->option('baud');
        $cacheKey  = (string) $this->option('cache-key');
        $ttl       = max(1, (int) $this->option('ttl'));
        $pattern   = (string) $this->option('pattern');
        $sleepMs   = max(0, (int) $this->option('sleep'));

        if (!$device) {
            $this->error('No serial port found. Connect the scale and try again, or pass --device=');
            return Command::FAILURE;
        }

        if (!$this->applySerialConfig($device, $baud)) {
            return Command::FAILURE;
        }

        $this->info("Listening to weight data from {$device} at {$baud} baud. Writing to cache key '{$cacheKey}' (TTL {$ttl}s).");
        $this->registerSignalHandlers();

        $this->handle = @fopen($device, 'r');
        if (!$this->handle) {
            $this->error("Unable to open {$device} for reading. Check permissions (uucp/dialout groups) and cabling.");
            return Command::FAILURE;
        }

        // Non-blocking with select
        stream_set_blocking($this->handle, false);

        // Some devices send bursts without newline; build buffer
        $buffer = '';

        while (true) {
            // Check for data without busy looping
            $read   = [$this->handle];
            $write  = null;
            $except = null;

            // Wait up to 0.5s for data
            $ready = @stream_select($read, $write, $except, 0, 500000);

            if ($ready === false) {
                $this->warn('stream_select returned error, continuing...');
            } elseif ($ready > 0) {
                $chunk = @fread($this->handle, 4096);
                if ($chunk === '' || $chunk === false) {
                    // End or transient; back off slightly
                    usleep(100000);
                } else {
                    $buffer .= $chunk;

                    // Process complete lines
                    while (($pos = strpos($buffer, "\n")) !== false) {
                        $line = substr($buffer, 0, $pos);
                        $buffer = substr($buffer, $pos + 1);
                        $this->processLine($line, $pattern, $cacheKey, $ttl);
                    }
                }
            }

            if ($sleepMs > 0) {
                usleep($sleepMs * 1000);
            }
        }
    }

    private function processLine(string $line, string $pattern, string $cacheKey, int $ttl): void
    {
        $clean = trim($line);

        // Remove common unit suffixes without being too aggressive
        $unitStripped = preg_replace('/\s*(kg|g|lb|lbs|oz)\b/i', '', $clean);

        if (preg_match($pattern, $unitStripped, $m)) {
            $raw = $m[1];

            // Normalize decimal comma to dot
            $normalized = str_replace(',', '.', $raw);

            // Validate numeric
            if (is_numeric($normalized)) {
                $weight = $normalized + 0; // cast to numeric type
                Cache::put($cacheKey, $weight, now()->addSeconds($ttl));
                $this->line('Weight: ' . $weight);
                return;
            }
        }

        // Uncomment for verbose debugging
        // $this->output->writeln("<comment>Skipped: {$clean}</comment>");
    }

    private function detectSerialPort(): ?string
    {
        // Most reliable first: by-id symlinks remain stable across reboots
        $candidates = array_merge(
            glob('/dev/serial/by-id/*') ?: [],
            glob('/dev/ttyUSB*') ?: [],
            glob('/dev/ttyACM*') ?: [],
            glob('/dev/ttyS*') ?: []
        );

        // Prefer by-id if present
        usort($candidates, function ($a, $b) {
            $score = fn($p) => str_starts_with($p, '/dev/serial/by-id') ? 0 : (str_starts_with($p, '/dev/ttyUSB') ? 1 : (str_starts_with($p, '/dev/ttyACM') ? 2 : 3));
            return $score($a) <=> $score($b);
        });

        // Resolve symlink if needed
        $port = $candidates[0] ?? null;
        if ($port && str_starts_with($port, '/dev/serial/by-id')) {
            $resolved = @realpath($port);
            if ($resolved) {
                return $resolved;
            }
        }

        return $port ?: null;
    }

    private function applySerialConfig(string $device, int $baud): bool
    {
        // Typical 8N1, no flow control. Many scales need this exact configuration.
        $cmd = sprintf(
            'stty -F %s %d -parenb -cstopb cs8 -ixon -ixoff -crtscts -echo -echoe -echok -echoctl -echoke 2>&1',
            escapeshellarg($device),
            $baud
        );

        $output = [];
        $code = 0;
        @exec($cmd, $output, $code);

        if ($code !== 0) {
            $this->error('Failed to configure serial port:');
            foreach ($output as $line) {
                $this->line("  {$line}");
            }
            $this->line('Ensure stty is installed and the user has permission to access the device.');
            return false;
        }

        return true;
    }

    private function registerSignalHandlers(): void
    {
        if (!function_exists('pcntl_signal')) {
            $this->warn('pcntl is not available. Graceful shutdown signals are disabled.');
            return;
        }

        pcntl_async_signals(true);

        $shutdown = function () {
            $this->info('Shutting down...');
            if (is_resource($this->handle)) {
                @fclose($this->handle);
            }
            exit(Command::SUCCESS);
        };

        pcntl_signal(SIGINT, $shutdown);   // Ctrl+C
        pcntl_signal(SIGTERM, $shutdown);  // kill
        pcntl_signal(SIGHUP, $shutdown);   // terminal closed
    }
}

// pm2 start "php artisan weight:listen --device=/dev/ttyUSB0 --baud=9600 --cache-key=current_weight" --name weight-listener
// sudo usermod -a -G dialout $USER
// stty -F /dev/ttyS0 9600 cs8 -cstopb -parenb -ixon -ixoff -crtscts
// cat /dev/ttyS0
// php artisan weight:listen --device=/dev/ttyS0 --baud=9600
