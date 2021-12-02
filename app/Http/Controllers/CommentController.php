<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Mediators\Mediator;
use App\Models\Comment;
use App\Repositories\IssueRepository;
use App\Services\IssueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CommentRequest $request
     * @return JsonResponse
     */
    public function store(CommentRequest $request)
    {
        //store a comment
        $comment = $this->mediator->service->create($request->all());

        //change issue status
        $issueRepository = app(IssueRepository::class);
        $issueService = app(IssueService::class);
        $issue = $issueRepository->getById($request->input('issue_id'));
        if(auth()->user()->isManager()) {
            $issueService->setStatusWaitForClientAnswer($issue);
        } else {
            $issueService->setStatusWaitForManagerAnswer($issue);
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
     * @return JsonResponse
     */
    public function show(Comment $comment)
    {
        return response()->json(['data' => $comment]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}