<?php

namespace App\Http\Controllers;

use App\Services\ContractAppendixService;

class ContractAppendixController extends Controller
{
    public function __construct(
        protected ContractAppendixService $contractAppendixService,
    ) {

    }

    public function index()
    {

    }
}
