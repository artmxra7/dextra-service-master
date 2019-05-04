<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use DateTime;
use Auth;
use DB;
use Mail;
use Validator;
use App\Http\Requests;
use App\Repositories\UserRepository;
use Illuminate\Auth\Passwords\PasswordBroker;
use App\Http\Controllers\AppBaseController as Controller;

use App\Models\User;

class AuthController extends Controller
{
    /** @var  userRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    public function login() {

        $validator = Validator::make(request()->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation failed', 422, $validator->errors()->getMessages());
        }

        if ( Auth::attempt(['email' => request('email'), 'password' => request('password')]) ) {

            $api_token = bcrypt( auth()->user()->email . time() );

            $this->userRepository->update(compact('api_token'), auth()->id());

            $user = User::with('company')->find(auth()->id());

            return $this->sendResponse(compact(
                'api_token',
                'user'
            ), 'Logged in successfully');

        } else {

            return $this->sendError('These credentials do not match our records.', 404);

        }
    }

    public function register() {

        $validator = Validator::make(request()->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation failed', 422, $validator->errors()->getMessages());
        }

        $api_token = bcrypt( request('email') . time() );

        $user = $this->userRepository->create([
            'name' => request('name'),
            'email' => request('email'),
            'api_token' => $api_token,
            'verification_code' => str_random(5),
            'password' => bcrypt(request('password')),
        ]);

        if ($user) {

            Mail::queue('auth.emails.api.activation', [ 'code' => $user->verification_code ], function ($m) {
                $m->from(env('MAIL_USERNAME'), env('APP_NAME'));
                $m->to(request('email'), request('name'))->subject('Aktifkan Akun Kamu');
            });

            return $this->sendResponse(compact(
                'api_token'
                ,'user'
            ), 'Registration user successfully');

        } else {

            return $this->sendError('Registration user failed', 422);

        }

    }

    public function logout() {
        $api_token = '';

        if (auth('api')->id()) {
            $user = $this->userRepository->update(compact('api_token'), auth('api')->id());
        }

        return $this->sendResponse(null, 'Logged out successfully');

    }

    public function activate() {

        $validator = Validator::make(request()->all(), [
            'verification_code' => 'required',
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation failed', 422, $validator->errors()->getMessages());
        }

        $check = $this->userRepository->findWhere([
                    'email'=> request('email'),
                    'verification_code'=> request('verification_code')
                ])->first();

        if ($check) {
            $user = $this->userRepository->update( ['verification_code' => ''], $check->id );
            return $this->sendResponse(null, 'Account already active');
        } else {
            return $this->sendError('Verification code not found.', 404);
        }
    }

    public function forgot() {

        $validator = Validator::make(request()->all(), [
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation failed', 422, $validator->errors()->getMessages());
        }

        $check = $this->userRepository->findByField('email', request('email'))->first();

        if ($check) {

            $reset_code = str_random(5);

            DB::table('password_resets')->where('email', $check->email)->delete();
            DB::table('password_resets')->insert([
                'email' => $check->email,
                'token' => $reset_code,
                'created_at' => new DateTime
            ]);

            Mail::queue('auth.emails.api.password', [ 'code' => $reset_code ], function ($m) use ($check) {
                $m->from(env('MAIL_USERNAME'), env('APP_NAME'));
                $m->to(request('email'), $check->name)->subject(env('APP_NAME') . ' Reset Code');
            });

            return $this->sendResponse(compact('reset_code'), 'Reset code has been send, please check your email.');

        } else {

            return $this->sendError("That email address doesn't match any user accounts. ", 404);

        }
    }

    public function reset() {

        $validator = Validator::make(request()->all(), [
            'email' => 'required',
            'reset_code' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation failed', 422, $validator->errors()->getMessages());
        }

        $checkCode = DB::table('password_resets')->where([
            'email' => request('email'),
            'token' => request('reset_code')
        ])->first();


        if ($checkCode) {

            $user = $this->userRepository->findByField('email', request('email'))->first();

            if ($user) {

                $this->userRepository->update([ 'password' => bcrypt(request('password')) ], $user->id);

                DB::table('password_resets')->where('email', $user->email)->delete();

                return $this->sendResponse(null, 'Your password has been reset!');

            } else {

                return $this->sendError("The email address doesn't match with any account. ", 404);

            }


        } else {

            return $this->sendError("The reset code doesn't match with email address. ", 404);

        }
    }

    public function store_fcm_token($id) {

        $validator = Validator::make(request()->all(), [
            'fcm_token' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data'    => $validator->errors()->getMessages(),
                'message' => 'Validation failed',
            ];
        }

        $user = User::where('id', $id)->update([
            'fcm_token' => request('fcm_token'),
        ]);

        if ($user) {

            $user = User::find($id);
            return [
                'success' => true,
                'data'    => $user,
                'message' => 'Saved success',
            ];

        } else {

            return [
                'success' => false,
                'data'    => '',
                'message' => 'Saved failed',
            ];

        }

    }

    public function storeLocation(Request $request) {
        $user = User::find(auth('api')->id());

        if ($user) {
            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;
            $user->save();
        } else {
            return $this->sendError("User not found", 404);
        }

        return $this->sendResponse(NULL, 'Location has been saved');
    }
}
