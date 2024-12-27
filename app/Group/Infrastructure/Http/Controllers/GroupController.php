<?php

namespace App\Group\Infrastructure\Http\Controllers;

use App\Common\Infrastructure\Http\Controllers\Controller;
use App\Group\Application\Services\GroupService;
use App\Group\Domain\Dto\CreateGroupDTO;
use App\Group\Domain\Dto\DeleteGroupDTO;
use App\Group\Infrastructure\Http\Requests\CreateGroupRequest;

class GroupController extends Controller
{
    public function __construct(private GroupService $groupService)
    {
    }

    public function createGroup(CreateGroupRequest $request)
    {
        $me = auth()->user();

        $createGroup = new CreateGroupDTO($request->name, $me);

        $group = $this->groupService->createGroup($createGroup);

        return response()->json($group->toArray());
    }

    public function deleteGroup(int $id)
    {
        $me = auth()->user();

        $deleteGroup = new DeleteGroupDTO($id, $me);

        $this->groupService->deleteGroup($deleteGroup);

        return response()->noContent();
    }
}
