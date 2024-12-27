<?php

namespace App\Group\Infrastructure\Http\Controllers;

use App\Auth\Domain\Dto\UserDTO;
use App\Common\Infrastructure\Http\Controllers\Controller;
use App\Group\Application\Services\GroupService;
use App\Group\Domain\Dto\AddMemberToGroupDTO;
use App\Group\Domain\Dto\CreateGroupDTO;
use App\Group\Domain\Dto\DeleteGroupDTO;
use App\Group\Domain\Dto\RemoveMemberFromGroupDTO;
use App\Group\Infrastructure\Http\Requests\AddMemberToGroupRequest;
use App\Group\Infrastructure\Http\Requests\CreateGroupRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Inertia\Inertia;

class GroupController extends Controller
{
    public function __construct(private GroupService $groupService)
    {
    }

    public function createGroup(CreateGroupRequest $request): JsonResponse
    {
        /** @var UserDTO $me */
        $me = auth()->user();

        $createGroup = new CreateGroupDTO($request->name, $me);

        $group = $this->groupService->createGroup($createGroup);

        return response()->json($group->toArray());
    }

    public function deleteGroup(int $id): Response
    {
        /** @var UserDTO $me */
        $me = auth()->user();

        $deleteGroup = new DeleteGroupDTO($id, $me);

        $this->groupService->deleteGroup($deleteGroup);

        return response()->noContent();
    }

    public function addMemberToGroup(AddMemberToGroupRequest $request, int $id): Response
    {
        /** @var UserDTO $me */
        $me = auth()->user();

        $addMemberToGroup = new AddMemberToGroupDTO($id, $request->email, $me);

        $this->groupService->addMemberToGroup($addMemberToGroup);

        return response()->noContent(201);
    }

    public function removeMemberFromGroup(int $id, int $userId): Response
    {
        /** @var UserDTO $me */
        $me = auth()->user();

        $removeMemberFromGroup = new RemoveMemberFromGroupDTO($id, $userId, $me);

        $this->groupService->removeMemberFromGroup($removeMemberFromGroup);

        return response()->noContent();
    }

    public function getMembers(int $id): JsonResponse
    {
        /** @var UserDTO $me */
        $me = auth()->user();

        $members = $this->groupService->getMembers($id, $me);

        return response()->json($members);
    }

    public function getGroups(): JsonResponse
    {
        /** @var UserDTO $me */
        $me = auth()->user();

        $groups = $this->groupService->getGroups($me->id);

        return response()->json($groups);
    }

    public function getOwnedGroups(): JsonResponse
    {
        /** @var UserDTO $me */
        $me = auth()->user();

        $groups = $this->groupService->getOwnedGroups($me->id);

        return response()->json($groups);
    }

    public function showGroupMembers(int $id)
    {
        $group = $this->groupService->getGroupById($id);

        return Inertia::render('Group/Members', [
            'group' => $group->toArray()
        ]);
    }
}
