<?php

namespace App\Media\Infrastructure\Http\Controllers;

use App\Auth\Domain\Dto\UserDTO;
use App\Common\Infrastructure\Http\Controllers\Controller;
use App\Media\Application\Services\MediaService;
use App\Media\Domain\Exceptions\MediaExceptions;
use App\Media\Infrastructure\Http\Requests\MediaRequest;
use Illuminate\Support\Facades\RateLimiter;

class MediaController extends Controller
{
    public function __construct(
        private MediaService $mediaService
    )
    {
    }

    public function uploadMedia(MediaRequest $request)
    {
        /** @var UserDTO $me */
        $me = auth()->user();

        $executed = RateLimiter::attempt(
            'upload-media:' . $me->id,
            $perMinute = 5,
            function () {
                return true;
            }
        );
        if (!$executed) {
            return MediaExceptions::rateLimit();
        }

        $file = $request->file('file');

        $url = $this->mediaService->upload($me->id, $file);

        return response()->json(['url' => $url]);
    }
}
