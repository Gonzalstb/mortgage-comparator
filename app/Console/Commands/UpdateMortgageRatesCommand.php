<?php

namespace App\Console\Commands;

use App\Models\Bank;
use App\Services\MortgageDataAggregatorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateMortgageRates extends Command
{
    protected $signature = 'mortgage:update-rates {--bank=} {--force}';
    protected $description = 'Actualiza las tasas de hipoteca desde APIs y web scraping';
    
    public function __construct(
        private MortgageDataAggregatorService $aggregatorService
    ) {
        parent::__construct();
    }
    
    public function handle(): int
    {
        $this->info('Iniciando actualización de tasas de hipoteca...');
        
        $banks = $this->option('bank') 
            ? Bank::where('slug', $this->option('bank'))->get()
            : Bank::where('is_active', true)->get();
        
        if ($banks->isEmpty()) {
            $this->error('No se encontraron bancos para actualizar');
            return 1;
        }
        
        $criteria = [
            'loan_amount' => 150000,
            'property_value' => 200000,
            'term_years' => 25,
            'ltv' => 75,
            'age' => 35,
            'income' => 3000,
            'contract_type' => 'permanent',
        ];
        
        foreach ($banks as $bank) {
            $this->info("Actualizando datos de {$bank->name}...");
            
            try {
                $data = $this->aggregatorService->getMortgageData($bank, $criteria);
                
                if ($data->isNotEmpty()) {
                    $this->info("✓ {$bank->name}: {$data->count()} productos actualizados");
                    
                    if ($this->option('verbose')) {
                        $data->each(function ($item) {
                            $this->line("  - {$item['product_name']}: {$item['interest_type']} " . 
                                       ($item['tae'] ? "TAE {$item['tae']}%" : ''));
                        });
                    }
                } else {
                    $this->warn("✗ {$bank->name}: Sin datos disponibles");
                }
                
            } catch (\Exception $e) {
                $this->error("✗ {$bank->name}: Error - " . $e->getMessage());
                Log::error("Error updating rates for {$bank->name}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
            
            // Pausa entre bancos para no sobrecargar
            if (!$banks->last()->is($bank)) {
                sleep(2);
            }
        }
        
        $this->info('Actualización completada');
        
        // Limpiar caché si es necesario
        if ($this->option('force')) {
            $this->call('cache:clear', ['--tags' => 'mortgages']);
            $this->info('Caché limpiado');
        }
        
        return 0;
    }
}