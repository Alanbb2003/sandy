<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_number' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:15'],
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['nullable', 'string', 'max:255'],
        ],[
            // Custom messages
            'name.required' => 'Harap masukkan nama Anda.',
            'email.required' => 'Alamat email diperlukan.',
            'email.email' => 'Harap masukkan alamat email yang valid.',
            'email.unique' => 'Email ini sudah digunakan.',
            'password.required' => 'password diperlukan.',
            'password.min' => 'password harus terdiri dari minimal :min karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'phone_number.required' => 'Nomor telepon diperlukan.',
            'phone_number.regex' => 'Harap masukkan format nomor telepon yang valid.',
            'phone_number.min' => 'Nomor telepon harus memiliki setidaknya :min digit.',
            'phone_number.max' => 'Nomor telepon tidak boleh lebih dari :max digit.',
            'firstName.required' => 'Nama depan diperlukan.',
            'lastName.string' => 'Nama belakang harus berupa kata. ',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        try {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'firstName'=>$data['firstName'],
                'lastName'=>$data['lastName'],
                'noHp'=>$data['phone_number'],
                'tanggalLahir'=>null,
                'role'=>0
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
