<?php

namespace App\Modules\User\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

final class CreateMaintainerCommand extends Command
{
    protected $signature = 'create:maintainer';

    protected $description = 'Создает мэйнтейнера.';

    public function handle(): int
    {
        $this->info('Создание нового мэйнтейнера...');

        $name = $this->ask('Введите имя пользователя');
        $email = $this->ask('Введите email пользователя');
        $password = $this->secret('Введите пароль');

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role_id' => Role::where('title', 'Maintainer')->first()->id,
        ]);

        $this->info('Мэйнтейнер успешно создан!');

        return self::SUCCESS;
    }
}
