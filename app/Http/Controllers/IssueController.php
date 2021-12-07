<?php

namespace App\Http\Controllers;

use App\Events\IssueCreatedEvent;
use App\Http\Requests\AttachRequest;
use App\Http\Requests\IssueRequest;
use App\Jobs\CommentWasWrittenJob;
use App\Mediators\Mediator;
use App\Models\Issue;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class IssueController extends Controller
{
    private Mediator $mediator;

    public function __construct(Mediator $mediator)
    {
        $this->mediator = $mediator;
    }

    /**
     * Display a listing of the issues.
     *
     * @return View
     */
    public function index()
    {
        $issuesPaginator = $this->mediator->repository->getAllPaginatorOrdDescByUpdated(10);

        return view('issues.list', compact('issuesPaginator'));
    }

    /**
     * Display a listing of the issues for specific user (user with specific role).
     *
     * @return View
     */
    public function listForUser()
    {
        return view('issues.list',
            ['issuesPaginator' => $this->mediator->repository->getAllForUserOnPage(10, auth()->user())]
        );
    }

    /**
     * Display a listing of the free (not attached) issues.
     *
     * @return View
     */
    public function listNotAttached()
    {
        return view('issues.list', [
            'issuesPaginator' => $this->mediator->repository->getNotAttachedOnPage(10)
        ]);
    }

    /**
     * Show the form for creating a new issue.
     *
     * @return View
     */
    public function create()
    {
        return view('issues.create');
    }

    /**
     * Store a newly created resource in storage + generate event.
     *
     * @param IssueRequest $request
     * @return JsonResponse
     */
    public function store(IssueRequest $request)
    {
        $request->merge(['status_id' => 1, 'client_id' => auth()->user()->id]); //for safety reasons
        $issue = $this->mediator->service->create($request->all());

        IssueCreatedEvent::dispatch($issue);

        $this->mediator->repository->flushTaggedCache();

        return response()->json(
            [],
            Response::HTTP_CREATED,
            ['Location' => route('issuesShow', ['issue' => $issue->id])]
        );
    }

    /**
     * Attach the issue to the manager + queue up notification job.
     *
     * @param AttachRequest $request
     * @param Issue $issue
     * @return JsonResponse
     */
    public function attach(AttachRequest $request, Issue $issue)
    {
        //attach the issue
        $this->mediator->service->update($issue, [
            'manager_id' => $request->input('manager_id'),
        ]);

        //change issue status
        $this->mediator->service->setStatusWaitForManagerAnswer($issue);

        //notify the manager about attachment (like new comments were written by client)
        if(!auth()->user()->isManager()) {
            CommentWasWrittenJob::dispatch($issue, $issue->manager);
        }

        $this->mediator->repository->flushTaggedCache();

        return response()->json([],Response::HTTP_OK);
    }

    /**
     * Display the issue.
     *
     * @param Issue $issue
     * @param UserRepository $userRepository
     * @return View
     */
    public function show(Issue $issue, UserRepository $userRepository)
    {
        $managers = $userRepository->getAllManagers();
        $attachedManager = $userRepository->getManagerOf($issue);

        return view('issues.show', compact(
            'issue',
            'managers',
            'attachedManager'
        ));
    }

    /**
     * Show the form for editing the issue.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return response('stub');
    }

    /**
     * Update the issue.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        return response('stub');
    }

    /**
     * Remove the issue from storage.
     *
     * @param Issue $issue
     * @return JsonResponse
     */
    public function destroy(Issue $issue)
    {
        $this->mediator->service->setStatusClosed($issue);
        $this->mediator->service->delete($issue->id);

        $this->mediator->repository->flushTaggedCache();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

}
