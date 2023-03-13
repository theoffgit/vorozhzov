<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class TokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gettoken {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Bearer Token';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $credentials = [
            'email' => $this->argument('email'),
            'password' => $this->argument('password'),
        ];

        if(Auth::attempt($credentials))
        {
            $user = Auth::user();
            $user->tokens()->delete();
            $token = $user->createToken('My App');
            $this->line($token->plainTextToken);
        }else
        {
            $this->line("user with such credentials doesn't exists");
        }
    }
}
