<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Hash;

use App\User;
use App\Role;
use App\SecurityQuestion;
use App\Shclinic;

use App\Api\HealthNetworkClient;


class AccountController extends Controller
{
	// View currently logged-in user's profile
	public function profile() {
		$roles = Role::pluck('rolename', 'role_id');
		$questions = SecurityQuestion::pluck('question', 'sq_id');

		return view('account.profile', compact('roles', 'questions'));
	}

	// View a list of all users
	public function users() {
		$users = User::users();
		$addr = $this->getAddrDropdowns();
		$cities = $addr['cities'];
		$provinces = $addr['provinces'];
		$regions = $addr['regions'];

		return view('account.users', compact('users', 'cities', 'provinces', 'regions'));
	}

	// View create a new user or edit an existing user
	// $mode = 1 for create, $mode = 2 for edit
	public function user($mode, $id = 0) {
		$roles = Role::roles();
		$questions = SecurityQuestion::pluck('question', 'sq_id');
		$user = array();
		$addr = $this->getAddrDropdowns();
		$cities = $addr['cities'];
		$provinces = $addr['provinces'];
		$regions = $addr['regions'];

		if ($mode == 1) {
			$user = new User();

			return view('account.user', compact(
				'roles', 'questions', 'user', 'mode',
				'cities', 'provinces', 'regions'
			));
		}
		else if ($mode == 2) {
			$user = User::where("id", $id)->first();

			return view('account.user', compact(
				'roles', 'questions', 'user', 'mode',
				'cities', 'provinces', 'regions'
			));
		}
	}

	public function clinic(Request $request){
		$context = $this->getAddrDropdowns();
		$context['res'] = '';
		$shc = Shclinic::all();
		$shc = $shc->first();
		if ($shc==null) {
			$shc = new Shclinic();
			$shc->shc_id = 0;  // 0 for unvalidated shc
		}
		if ($request->isMethod('post')){
			$data = Input::all();
			$shc->fill($data);
			$image = $shc->uploadFile($request, $directory='shc_image');
			if ($image){
				if($shc->image){
					$shc->deleteImage();
				}
				$shc->image = $image;
			}
			if ($shc->wallet_addr == ''){
				$hnClient = new HealthNetworkClient();
				$shc->wallet_addr = $hnClient->register($shc->hp_id);
			}
			$shc->save();
		}

		$context['shc'] = $shc;
		return view('account.clinic', $context);
	}

	// Update current logged-in user's information
	public function updateProfile() {

		// Getting all post data
		$data = Input::all();

		// Applying validation rules.
		$rules = array(
			'name' => 'required|max:255',
		);

		$update_data = array(
			'name' => Input::get('name')
		);

		if (Input::get('password') !== "") {
			$rules["password"] = 'required|min:6';
			$rules["password_confirmation"] = 'required|min:6|same:password';
			$update_data["password"] = Hash::make(Input::get('password'));
		}

		if (Auth::user()->role == config("constants.SHC_ADMIN") || Auth::user()->role == config("constants.CENTRAL_ADMIN")) {
			$rules["question"] = 'required';
			$rules["answer"] = 'required';

			$update_data["sq_id"] = Input::get('question');
			$update_data["answer"] = Input::get('answer');
		}

		$validator = Validator::make($data, $rules);

		if ($validator->fails()) {
			// If validation fails redirect back to My Account page.
			return Redirect::to('/account/profile')->withErrors($validator);
		}
		else {
			User::where('id', Auth::user()->id)
				->update($update_data);

			return Redirect::to('/account/profile')->with('message', 'Profile successfully updated!');
		}
	}

	// Update an existing user's information
	public function updateUser($id) {

		// Getting all post data
		$data = Input::all();
		$role = Input::get('role');

		// Applying validation rules.
		$rules = array(
			'name' => 'required|max:255',
			'role' => 'required'
		);

		$update_data = array(
			'name' => Input::get('name'),
			'role' => $role
		);

		if (Input::get('password') !== "") {
			$rules["password"] = 'required|min:6';
			$rules["password_confirmation"] = 'required|min:6|same:password';
			$update_data["password"] = Hash::make(Input::get('password'));
		}

		if ($role == config("constants.SHC_ADMIN") || $role == config("constants.CENTRAL_ADMIN")) {
			$rules["sq_id"] = 'required';
			$rules["answer"] = 'required';

			$update_data["sq_id"] = Input::get('sq_id');
			$update_data["answer"] = Input::get('answer');
		}

		$validator = Validator::make($data, $rules);

		if ($update_data['role'] > 3){
			$update_data['region'] = Input::get('region');
			$update_data['province'] = Input::get('province');
			$update_data['municipality'] = Input::get('municipality');
		}
		if ($validator->fails()) {
			// If validation falis redirect back to login.
			return Redirect::to('/account/user/2/' . $id)->withErrors($validator);
		}
		else {
			User::where('id', $id)
				->update($update_data);

			return $this->users();
		}
	}

	// Create a new user
	public function create() {

		// Getting all post data
		$data = Input::all();
		$role = Input::get('role');

		// Applying validation rules.
		$rules = array(
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:user',
			'password' => 'required|min:6',
			'password_confirmation' => 'required|min:6|same:password'
		);

		if ($role == config("constants.SHC_ADMIN") || $role == config("constants.CENTRAL_ADMIN")) {
			$rules["sq_id"] = 'required';
			$rules["answer"] = 'required';
		}

		$validator = Validator::make($data, $rules);

		if ($validator->fails()) {
			// If validation falis redirect back to login.
			return Redirect::to('/account/user/1')->withInput(Input::except('password'))->withErrors($validator);
		}
		else {
			$userdata = array(
				'name' => Input::get('name'),
				'email' => Input::get('email'),
				'password' => Hash::make(Input::get('password')),
				'role' => Input::get('role')
			);


			if ($role == config("constants.SHC_ADMIN") || $role == config("constants.CENTRAL_ADMIN")) {
				$userdata["sq_id"] = Input::get('sq_id');
				$userdata["answer"] = Input::get('answer');
			}

			if ($userdata['role'] > 3) {  // Provincial, regional, municipal, SHC user roles
				$userdata['region'] = Input::get('region');
				$userdata['province'] = Input::get('province');
				$userdata['municipality'] = Input::get('municipality');
			}

			User::insert($userdata);
			return $this->users();
		}
	}

	// Delete a user
	public function delete($id) {
		//TODO: Are you sure you want to blabla?
		//TODO: Mass delete

		User::where('id', $id)->delete();

		return Redirect::to('/account/users')->with('message', 'User ' . $id . ' deleted!');
		return $this->users();
	}

	public function register() {

		// Getting all post data
		$data = Input::all();
		return $data;

		// Applying validation rules.
		$rules = array(
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:user',
			'password' => 'required|min:5|confirmed',
		);

		$validator = Validator::make($data, $rules);

		if ($validator->fails()) {
			// If validation falis redirect back to login.
			return Redirect::to('/account/edit')->withInput(Input::except('password'))->withErrors($validator);
		}
		else {
			$userdata = array(
				'name' => Input::get('name'),
				'email' => Input::get('email'),
				'password' => bcrypt(Input::get('password')),
				'role' => 2
			);

			User::create($userdata);
			return Redirect::to('/account/edit');
		}

		/*
		// doing login.
		if (Auth::validate($userdata)) {
		if (Auth::attempt($userdata)) {
		return Auth::user();
		return Redirect::intended('/');
		}
		} 
		else {
		// if any error send back with message.
		Session::flash('error', 'Something went wrong'); 
		return Redirect::to('login');
		}
		*/
	}

	public function showEmailForm() {
		return view('auth.passwords.email');
	}

	public function checkEmail() {
		// Getting all post data
		$data = Input::all();
		$email = Input::get('email');

		// Applying validation rules.
		$rules = array(
			'email' => 'required|email|max:255',
		);

		$validator = Validator::make($data, $rules);

		if ($validator->fails()) {
			// If validation falis redirect back to login.
			return Redirect::to('/password/email')->withInput(Input::except('password'))->withErrors($validator);
		}
		else {
			$count = User::where('email', $email)->count();
			$role = User::where('email', $email)->pluck('role')->first();

			if($count == 0) {
				return Redirect::to('/password/email')->with('message', 'No matching email!');
			}
			else {
				if ($role == config("constants.SHC_ADMIN") || $role == config("constants.CENTRAL_ADMIN")) {
					return Redirect::route('resetForm', array('email' => $email));
					$questions = SecurityQuestion::pluck('question', 'sq_id');

					return view('auth.passwords.reset', compact('questions', 'email'));
				}
				else {
					return Redirect::to('/password/email')->with('message', "Please contact your administrator for resetting your password.");
				}
			}
		}
	}
	
	public function showResetForm($email) {
		$questions = SecurityQuestion::pluck('question', 'sq_id');

		return view('auth.passwords.reset', compact('questions', 'email'));
	}

	public function resetPassword() {
		// Getting all post data
		$data = Input::all();
		$email = Input::get('email');

		// Applying validation rules.
		$rules = array(
			'email' => 'required|email|max:255',
			'answer' => 'required|max:255',
			'password' => 'required|min:6',
			'password_confirmation' => 'required|min:6|same:password',
		);

		$validator = Validator::make($data, $rules);

		if ($validator->fails()) {
			// If validation falis redirect back to login.
			return Redirect::route('resetForm', array('email' => $email))->withInput(Input::except('password'))->withErrors($validator);
		}
		else {

			$count = User::where('email', $email)
					->where('sq_id', Input::get('question'))
					->where('answer', Input::get('answer'))
					->count();

			$role = User::where('email', $email)->pluck('role')->first();

			if($count == 0) {
				return Redirect::route('resetForm', array('email' => $email))->with('message', 'Security question/answer does not match!');
			}
			else {
				User::where('email', $email)
					->update(['password' => Hash::make(Input::get('password'))]);
			}

			return Redirect::to('/login')->with('passwordmessage', 'Password successfully updated!');
		}

	}
	
}