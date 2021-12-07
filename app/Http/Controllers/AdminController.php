<?php

namespace App\Http\Controllers;

use App\Events\IssueDetachedEvent;
use App\Http\Requests\UserRequest;
use App\Mediators\Mediator;
use App\Models\User;
use App\Repositories\RoleRepository;
use App\Services\IssueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    private Mediator $mediator;

    public function __construct(Mediator $mediator)
    {
        $this->mediator = $mediator;
    }

    /**
     * Show admin main page.
     *
     * @return View
     */
    public function index()
    {
        return view('admin.panel', [
            'usersPaginator' => $this->mediator->repository->getAllExceptAdminsPaginator(10)
        ]);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return View
     */
    public function createUser(RoleRepository $roleRepository)
    {
        $roles = $roleRepository->getAll();

        return view('admin.create-user', compact('roles'));
    }

    /**
     * Show the form for editing the user.
     *
     * @param User $user
     * @param RoleRepository $roleRepository
     * @return View
     */
    public function editUser(User $user, RoleRepository $roleRepository)
    {
        $roles = $roleRepository->getAll();

        return view('admin.edit-user', compact('user', 'roles'));
    }

    /**
     * Store a newly created user.
     *
     * @param User $user
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function storeUser(User $user, UserRequest $request)
    {
        $request->merge(['password' => Hash::make($request->input('password'))]);

        $this->mediator->service->create($request->all());

        return response()->json([], Response::HTTP_CREATED);
    }

    /**
     * Update the user.
     *
     * @param User $user
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function updateUser(User $user, UserRequest $request)
    {
        if ($request->input('password') === 'default') {

            $this->mediator->service->update($user, $request->except('password'));

        } else {

            $request->merge(['password' => Hash::make($request->input('password'))]);

            $this->mediator->service->update($user, $request->all());
        }

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Delete the user and detach corresponding issues if user is a manager.
     *
     * @param User $user
     * @param IssueService $issueService
     * @return JsonResponse
     */
    public function deleteUser(User $user, IssueService $issueService)
    {
        if ($user->isManager()) {
            //detach issues' from currently deleted user + generate event
            $issuesManaged = $user->issuesManaged()->get();
            foreach ($issuesManaged as $issueManaged) {
                $issueService->detachManager($issueManaged);
                IssueDetachedEvent::dispatch($issueManaged);
            }
        }

        //delete the user
        $this->mediator->service->delete($user->id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

}
