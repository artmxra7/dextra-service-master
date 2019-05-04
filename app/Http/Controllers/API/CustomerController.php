<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController as Controller;
use App\Http\Requests\CreateCustomerRequest;
use App\Repositories\CompanyRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Models\User;

class CustomerController extends Controller
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    public function __construct(UserRepository $userRepository, CompanyRepository $companyRepository)
    {
        $this->userRepository = $userRepository;
        $this->companyRepository = $companyRepository;
    }

    public function index()
    {
        $users = $this->userRepository->getUserByType('member', [
            'user_sales_id' => auth('api')->id()
        ]);

        return $this->sendResponse($users, 'success');
    }

    public function show($id)
    {
        $customer = $this->userRepository->with('company')->findWithoutFail($id);

        if (empty($customer)) {
            return $this->sendError('Customer not found.', 404);
        }

        return $this->sendResponse($customer, "success");
    }

    public function store(Request $request)
    {
        $person = (object) $request->person;
        $company = (object) $request->company;

        $customer = $this->userRepository->create([
            'name'          => $person->name,
            'email'         => $person->email,
            'password'      => bcrypt($person->password),
            'phone'         => $person->phone,
            'address'       => $person->address,
            'role'          => 'member',
            'city'          => $person->city,
            'user_sales_id' => $person->user_sales_id
        ]);

        if ($customer) {
            $company = $this->companyRepository->create([
                'name'                => $company->name,
                'sector_business'     => $company->sector_business,
                'user_position_title' => $company->user_position_title,
                'phone'               => $company->phone,
                'email'               => $company->email,
                'address'             => $company->address,
                'user_member_id'      => $customer->id,
                // 'photo' => str_slug($request->company_name) . '.png',
            ]);

            // $request->file('photo')->move(
            //     storage_path('app/public/company'), $company->photo
            // );

            $customer->company_id = $company->id;
            $customer->update();
        }


        if (empty($company) || empty($customer)) {
            return $this->sendError('Create customer account failed.', 404);
        }

        return $this->sendResponse([], "Create customer account success.");
    }

    public function update($id, Request $request)
    {
        $person = (object) $request->person;
        $company = (object) $request->company;

        $customer = User::where('id', $id)->update([
            'name'          => $person->name,
            'phone'         => $person->phone,
            'address'       => $person->address,
            'city'          => $person->city,
        ]);

        if ($customer) {
            $company = $this->companyRepository->create([
                'name'                => $company->name,
                'sector_business'     => $company->sector_business,
                'user_position_title' => $company->user_position_title,
                'phone'               => $company->phone,
                'email'               => $company->email,
                'address'             => $company->address,
                'user_member_id'      => $id,
                // 'photo' => str_slug($request->company_name) . '.png',
            ]);

            // $request->file('photo')->move(
            //     storage_path('app/public/company'), $company->photo
            // );

            $customer = User::where('id', $id)->update([
                'company_id' => $company->id,
            ]);
        }


        if (empty($company) || empty($customer)) {
            return $this->sendError('Update customer account failed.', 404);
        }

        $result = User::with('company')->find($id);

        return $this->sendResponse($result, "Update customer account success.");
    }
}
