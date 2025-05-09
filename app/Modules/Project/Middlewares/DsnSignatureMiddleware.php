<?php

namespace App\Modules\Project\Middlewares;

use App\Models\ProjectKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class DsnSignatureMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! $publicKey = $request->header('X-Hound-Auth')) {
            return response()->json(['error' => 'Missing header X-Hound-Auth'], Response::HTTP_FORBIDDEN);
        }

        if (! $signature = $request->header('X-Hound-Signature')) {
            return response()->json(['error' => 'Missing header X-Hound-Signature'], Response::HTTP_FORBIDDEN);
        }

        if (! $key = ProjectKey::where('public_key', $publicKey)->first()) {
            return response()->json(['error' => 'Wrong public key'], Response::HTTP_FORBIDDEN);
        }

        if (! hash_equals($signature, hash_hmac('sha256', $request->getContent(), $key->private_key))) {
            return response()->json(['error' => 'Wrong signature'], Response::HTTP_FORBIDDEN);
        }

        $request->merge([
            ...$request->input(),
            'project_id' => $key->project->id,
        ]);

        return $next($request);
    }
}
