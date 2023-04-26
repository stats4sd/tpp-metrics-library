<?php

namespace App\Jobs;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessImportNotifcation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public User $recipient)
      {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        sleep(5);

        Notification::make()
            ->title('Imported file processing is now complete')
            ->broadcast($this->recipient);

    }
}
