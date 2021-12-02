<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Requests\UserRequest;
use App\Mediators\Mediator;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;


class AdminController extends Controller
{
    private Mediator $mediator;

    public function __construct(Mediator $mediator)
    {
        $this->mediator = $mediator;
    }


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
    public function createUser()
    {
        $roles = Role::all();

        return view('admin.create-user', compact('roles'));
    }

    /**
     * Show the form for editing the user.
     *
     * @param User $user
     * @return View
     */
    public function editUser(User $user)
    {
        $roles = Role::all();

        return view('admin.edit-user', compact('user', 'roles'));
    }

    /**
     * tore a newly created resource in storage.
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
     * Update the specified resource in storage.
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
     * Delete the user.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function deleteUser(User $user)
    {
        $this->mediator->service->delete($user->id);
        $this->mediator->repository->resetCache($user->id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

}
