<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rabbit;
use Carbon\Carbon;
use Database\Seeders\TreatmentProtocolSeeder;

class ApplyTreatmentProtocol extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbits:apply-protocol {rabbit_id} {protocol} {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Applique un protocole de traitement à un lapin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $rabbitId = $this->argument('rabbit_id');
        $protocolName = $this->argument('protocol');
        $dateString = $this->option('date');
        
        $rabbit = Rabbit::find($rabbitId);
        
        if (!$rabbit) {
            $this->error("Lapin avec l'ID {$rabbitId} non trouvé.");
            return 1;
        }
        
        $date = $dateString ? Carbon::createFromFormat('Y-m-d', $dateString) : Carbon::now();
        
        $protocolSeeder = new TreatmentProtocolSeeder();
        $protocols = $protocolSeeder->getProtocols();
        $protocol = collect($protocols)->firstWhere('name', $protocolName);
        
        if (!$protocol) {
            $this->error("Protocole '{$protocolName}' non trouvé.");
            $this->info("Protocoles disponibles :");
            foreach ($protocols as $p) {
                $this->info("- {$p['name']} : {$p['description']}");
            }
            return 1;
        }
        
        $protocolSeeder->applyProtocolToRabbit($rabbitId, $protocolName, $date);
        
        $this->info("Protocole '{$protocolName}' appliqué au lapin {$rabbit->name} (ID: {$rabbitId}) à partir du {$date->format('d/m/Y')}.");
        
        return 0;
    }
}