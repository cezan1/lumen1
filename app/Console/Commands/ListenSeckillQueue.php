<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcessSeckillQueue;

class ListenSeckillQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seckill:listen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen to the seckill Redis queue and process tasks asynchronously';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting to listen to the seckill Redis queue...');
        
        dispatch(new ProcessSeckillQueue());
        
        $this->info('Successfully started listening to the seckill queue.');
        return Command::SUCCESS;
    }
}