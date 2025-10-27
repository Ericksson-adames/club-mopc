<?php

namespace App\Console\Commands;

use App\Mail\cartaReserva;
use App\Models\User;
use App\Mail\Prueba;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class testMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:emails';

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
        //
        $user = User::find(1);
        Mail::to('nelsy.pp12@gmail.com')->send(new cartaReserva('Erickson Adames'));
    }
}
