<?php

namespace App\Console\Commands;

use App\Mail\NewsletterMail;
use App\Models\CommonKnowledge;
use App\Models\Subscriber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AutoNewsletter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-newsletter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $c_knowledge = CommonKnowledge::all()->random(1)->first();

        $subcribers = Subscriber::all();

        if ($subcribers->count() >0){
            Mail::to($subcribers)->send(new NewsletterMail($c_knowledge?->message));
        }
        return 0;
    }
}
