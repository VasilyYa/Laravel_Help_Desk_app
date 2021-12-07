<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Jobs\CommentWasWrittenJob;
use App\Jobs\IssueChangeStatusJob;
use App\Mediators\Mediator;
use App\Models\Comment;
use App\Repositories\IssueRepository;
use App\Services\IssueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    private Mediator $mediator;

    public function __construct(Mediator $mediator)
    {
        $this->mediator = $mediator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response('stub');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return response('stub');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CommentRequest $request
     * @param IssueRepository $issueRepository
     * @param IssueService $issueService
     * @return JsonResponse
     */
    public function store(CommentRequest $request, IssueRepository $issueRepository, IssueService $issueService)
    {
        //store a comment
        $comment = $this->mediator->service->create($request->all());

        $issue = $issueRepository->getById($request->input('issue_id'));

        //actualize an issue - need for relevance control !!!
        $issueService->setUpdatedAtToNow($issue);

        //change issue status and notify the users (business logic):
        if (auth()->user()->isClient()) { // or $comment->author->isClient()

            //notify the manager about new comments from client
            if ($issue->isAttached()) {
                CommentWasWrittenJob::dispatch($issue, $issue->manager);
            }

            if ($issue->status->isWaitForClientAnswer()) {

                $issueService->setStatusWaitForManagerAnswer($issue);
            }

        } elseif (
            auth()->user()->isManager() // or $comment->author->isManager()
            && ($issue->status->isWaitForManagerAnswer() || $issue->status->isOpened())
        ) {

            $issueService->setStatusWaitForClientAnswer($issue);

            //notify the client about changing issue status
            if (!is_null($issue->client)) { //soft delete user checking
                IssueChangeStatusJob::dispatch($issue, $issue->client);
            }
        }

        return response()->json(
            [],
            Response::HTTP_CREATED,
            ['Location' => route('commentsShow', ['comment' => $comment->id])]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Comment $comment
     * @return Response
     */
    public function show(Comment $comment)
    {
        return response('stub');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return response('stub');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CommentRequest $request
     * @param int $id
     * @return Response
     */
    public function update(CommentRequest $request, $id)
    {
        return response('stub');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        return response('stub');
    }

}
