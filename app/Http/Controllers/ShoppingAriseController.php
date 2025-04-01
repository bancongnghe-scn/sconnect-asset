<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShoppingAriseRequest;
use App\Services\ShoppingAriseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingAriseController extends Controller
{
    public function __construct(
        protected ShoppingAriseService $shoppingAriseService,
    ) {
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->hasAnyRole(['accounting_director', 'hr_director'])) {
            return view('assets.shopping_arise.master.list');
        }
        if ($user->hasRole('manager_organization')) {
            return view('assets.shopping_arise.organization.list');
        } else {
            return view('assets.shopping_arise.company.list');
        }
    }

    public function createShoppingArise(CreateShoppingAriseRequest $request)
    {
        //        Auth::user()->canPer('shopping_arise.create');
        try {
            $result = $this->shoppingAriseService->createShoppingArise($request->validated());
            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function getListShoppingArise(Request $request)
    {
        $request->validate([
            'name'       => 'nullable|string',
            'start_time' => 'nullable|date',
            'end_time'   => 'nullable|date',
            'status'     => 'nullable|integer',
            'type'       => 'required|integer',
            'page'       => 'nullable|integer',
            'limit'      => 'nullable|integer',
        ]);

        //        Auth::user()->canPer('shopping_arise.view');

        try {
            $result = $this->shoppingAriseService->getListShoppingArise($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function deleteShoppingArise(Request $request)
    {
        $request->validate([
            'id' => 'required|array',
        ]);

        //        Auth::user()->canPer('shopping_arise.delete');
        try {
            $result = $this->shoppingAriseService->deleteShoppingArise($request->get('id'));
            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function findShoppingArise($id)
    {
        //        Auth::user()->canPer('shopping_arise.view');

        try {
            $result = $this->shoppingAriseService->findShoppingArise($id);

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function updateShoppingArise(Request $request, $id)
    {
        //        Auth::user()->canPer('shopping_arise.update');
        try {
            $result = $this->shoppingAriseService->updateShoppingArise($request->all(), $id);
            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function managerSendShoppingArise($id)
    {
        //        Auth::user()->canPer('shopping_arise.manager_send');
        try {
            $result = $this->shoppingAriseService->managerSendShoppingArise($id);
            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function hrProcessingShoppingArise($id)
    {
        //        Auth::user()->canPer('shopping_arise.hr_processing');
        try {
            $result = $this->shoppingAriseService->hrProcessingShoppingArise($id);
            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function syntheticShoppingArise($id)
    {
        //        Auth::user()->canPer('shopping_arise.hr_synthetic');
        try {
            $result = $this->shoppingAriseService->syntheticShoppingArise($id);
            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function sendApprovalShoppingArise($id)
    {
        Auth::user()->canAnyPer(['shopping_arise.hr_synthetic', 'shopping_arise.hr_send_approval', 'shopping_arise.account_send_approval']);
        try {
            $result = $this->shoppingAriseService->sendApprovalShoppingArise($id);
            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function completeShoppingArise($id)
    {
        Auth::user()->canPer('shopping_arise.hr_processing');
        try {
            $result = $this->shoppingAriseService->completeShoppingArise($id);
            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }
}
