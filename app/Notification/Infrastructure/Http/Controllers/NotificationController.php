<?php

namespace App\Notification\Infrastructure\Http\Controllers;

use App\Auth\Domain\Dto\UserDTO;
use App\Common\Infrastructure\Http\Controllers\Controller;
use App\Notification\Application\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class NotificationController extends Controller
{
    public function __construct(private NotificationService $notificationService)
    {
    }

    public function getUnreadedCount(): JsonResponse
    {
        /** @var UserDTO $me */
        $me = auth()->user();

        $count = $this->notificationService->getUserUnreadedCount($me->id);

        return response()->json(['count' => $count]);
    }

    public function getNotifications(): JsonResponse
    {
        /** @var UserDTO $me */
        $me = auth()->user();

        $notifications = $this->notificationService->getUserNotifications($me->id);

        return response()->json($notifications);
    }

    public function readNotification(int $id): Response
    {
        $this->notificationService->readNotification($id);

        return response()->noContent();
    }

    public function readAllNotifications(): Response
    {
        /** @var UserDTO $me */
        $me = auth()->user();

        $this->notificationService->readAllForUser($me->id);
        return response()->noContent();
    }
}
