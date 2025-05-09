<?php

namespace App\Modules\Project\Actions;

use App\Models\Project;

final readonly class CreateProjectAction
{
    public function execute(array $data): Project
    {
        \DB::beginTransaction();

        try {
            $project = Project::create([
                'team_id' => $data['team_id'],
                'name' => $data['name'],
                'slug' => $data['slug'] ?? \Str::slug($data['name']),
                'platform' => $data['platform'],
            ]);

            $key = $project->key()->create([
                'public_key' => \Str::uuid7(),
                'private_key' => \Str::random(32),
                'label' => $data['name'],
            ]);

            $key->update([
                'dsn' => $this->generateDsn($key->public_key),
            ]);

            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();

            throw $e;
        }

        return $project;
    }

    private function generateDsn(string $publicKey): string
    {
        return sprintf(
            '%s://%s:%s/api',
            \Config::get('app.scheme'),
            $publicKey,
            \Config::get('app.domain')
        );
    }
}
