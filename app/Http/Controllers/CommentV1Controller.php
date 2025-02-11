<?php

namespace App\Http\Controllers;

use App\Services\CommentV1Service;
use App\Support\Constants\SOfficeConstant;
use Illuminate\Http\Request;

class CommentV1Controller extends Controller
{
    public function __construct(
        protected CommentV1Service $commentV1Service,
    ) {

    }

    public function commentCreate(Request $request)
    {
        $request->validate([
            'type'      => 'required|integer',
            'target_id' => 'required|integer',
        ]);

        try {
            $this->commentV1Service->commentCreate($request->all());

            return response_success(['action_type' => SOfficeConstant::CM_CREATE_TYPE]);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }
}
