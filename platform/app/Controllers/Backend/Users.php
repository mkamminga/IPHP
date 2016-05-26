<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\User;
use App\Http\Models\Group;
use App\Http\Traits\ReturnAssoc;
use Validator;
use Config;

class Users extends Controller
{
    use ReturnAssoc;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $users = User::with('group');
        $inputs = $request->all();

        if (isset($inputs['username']) && !empty($inputs['username'])) {
            $users = $users->withUsernameLike($inputs['username']);
        }

        $users = $users->paginate(5);

        return view('cms.users.overview', ['users' => $users])->withInput($inputs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $groups = $this->getProcessedGroups();
        $countries = $this->getProcessedCountries();

        return view('cms.users.create', ['groups' => $groups, 'countries' => $countries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        $validator = $this->getValidator($requestData);
        if (!$validator->fails()) {
            $user = new User();
            $user->username = $requestData['username'];
            $user->password = bcrypt($requestData['password']);
            $user->group_id = $requestData['group_id'];
            $user->active   = $requestData['active'];
            $user->name     = $requestData['name'];
            $user->lastname     = $requestData['lastname'];
            $user->country     = $requestData['country'];
            $user->city     = $requestData['city'];
            $user->address     = $requestData['address'];
            $user->zip     = $requestData['zip'];
            $user->email     = $requestData['email'];

            if ($user->save()){
                return redirect()->route('beheer.users.index');
            } else {
                $validator->errors()->add('main', 'Er is een onbekende fout opgetreden tijdens het opslaan!');
            }
        }

        return redirect()->route('beheer.users.create')
                ->withErrors($validator)
                ->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('cms.users.delete', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $groups = $this->getProcessedGroups();
        $countries = $this->getProcessedCountries();
        $user = User::find($id);

        return view('cms.users.edit', ['groups' => $groups, 'user' => $user, 'countries' => $countries]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $user = User::find($id);

        $requestData = $request->all();
        $validator = $this->getValidator($requestData, $id);
        if (!$validator->fails()) {
            $user->username = $requestData['username'];
            if (!empty($requestData['password'])) {
                $user->password = bcrypt($requestData['password']);
            }
            $user->group_id = $requestData['group_id'];
            $user->active   = $requestData['active'];
            $user->name     = $requestData['name'];
            $user->lastname     = $requestData['lastname'];
            $user->country     = $requestData['country'];
            $user->city     = $requestData['city'];
            $user->address     = $requestData['address'];
            $user->zip     = $requestData['zip'];
            $user->email     = $requestData['email'];

            if ($user->save()){
                return redirect()->route('beheer.users.index');
            } else {
                $validator->errors()->add('main', 'Er is een onbekende fout opgetreden tijdens het opslaan!');
            }
        }

        return redirect()->route('beheer.users.edit', [$id])
                ->withErrors($validator)
                ->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if (($user = User::find($id))) {
            $user->delete();
        }

        return redirect()->route('beheer.users.index');
    }

    private function getValidator (array $requestData, $id = 0) {
        $rules = [
            'username' => 'required|unique:users,username,'. $id,
            'password' => 'min:4|max:24|confirmed',
            'group_id' => 'required',
            'active'   => 'required',
            'name'     => 'required|min:2',
            'lastname' => 'required|min:2',
            'country'   => 'required',
            'city'   => 'required|min:4',
            'address'   => 'required|min:4',
            'zip'       => 'required|min:4',
            'email'     => 'required|email'
        ];

        if ($id == 0) {
            $rules['password'] = 'required|' . $rules['password'];
        }

        return Validator::make($requestData, $rules);   
    }

    private function getProcessedGroups () {
        $groups = Group::all();

        $return =[];

        foreach ($groups as $group) {
            $return[$group->id] = $group->description;
        }

        return $return;
    }

    /**
     * [getProcessedVat description]
     * @return [type] [description]
     */
    private function getProcessedCountries() {
        $countries = Config::get('static_values.countries');

        return $this->getAssocValues($countries);
    }
}
