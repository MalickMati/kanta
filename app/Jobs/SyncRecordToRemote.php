<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SyncRecordToRemote implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recordData;
    protected $code;

    public function __construct(array $recordData, int $code)
    {
        $this->recordData = $recordData;
        $this->code = $code;
    }

    public function handle(): void
    {
        switch($this->code){
            case 1:
                $url = 'https://kanta.malick.site/first';
                break;
            case 2:
                $url = 'https://kanta.malick.site/second';
                break;
            case 3:
                $url = 'https://kanta.malick.site/add/user';
                break;
        }

        try {
            $response = Http::timeout(10)->post($url, $this->recordData);

            if ($response->failed()) {
                \Log::error('Remote sync failed', [
                    'record' => $this->recordData,
                    'response' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error syncing record to remote', [
                'error' => $e->getMessage(),
                'record' => $this->recordData
            ]);
        }
    }
}
