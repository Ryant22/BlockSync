<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MojangApiService;
use Illuminate\Support\Facades\App;

class ParseJsonStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:ingestStats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ingest JSON stats for each player from local storage and update the database';

    /**
     * The Mojang API service instance.
     *
     * @var MojangApiService
     */
    protected $mojangApiService;
    
    public function __construct(MojangApiService $mojangApiService)
    {
        parent::__construct();
        $this->mojangApiService = $mojangApiService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting JSON stats ingestion...');
        $mojang = App::make(MojangApiService::class);
        // Get all JSON files from the local storage
        $files = \Storage::disk('local')->files();

        foreach ($files as $file) {
            $playerUuid = pathinfo($file, PATHINFO_FILENAME);
            $this->info("Processing file: $file for player UUID: $playerUuid");
            $playerName = $mojang->getUsernameFromUuid($playerUuid);
            if (!$playerName) {
                $this->error("Could not find player name for UUID: $playerUuid");
                continue;
            }
            $this->info("Player name: $playerName");
            $data = json_decode(\Storage::disk('local')->get($file), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error(json_last_error() . ': ' . json_last_error_msg());
                continue;
            }
            $player = \App\Models\Player::updateOrCreate(
                ['uuid' => $playerUuid],
                ['username' => $playerName]
            );
            $this->info("Player {$playerName} (UUID: {$playerUuid}) found or created in the database.");
            foreach ($data['stats'] as $category => $stats) {
                foreach ($stats as $key => $value) {
                    \App\Models\Stat::updateOrCreate(
                        [
                            'uuid' => $playerUuid,
                            'category' => $category,
                            'key' => $key,
                        ],
                        ['value' => $value]
                    );
                    $this->info("Stat updated for player {$playerName}: {$category} - {$key} = {$value}");
                }
            }
            $this->info("Data for player {$playerName}: " . print_r($data, true));
        }

        $this->info('JSON stats ingestion completed.');        
    }
}
