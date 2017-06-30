<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AdminController;

class AccountsController extends AdminController
{
    /**
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        parent::setResource('Admin');

        $this->middleware(['auth:admin', 'sanitize']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.accounts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate($this->request, $this->createRules());

        $data = array_except($this->request->all(), ['_method', '_token']);
        $data['password'] = bcrypt($data['password']);

        $new = Admin::create($data);

        $this->forgetQuery('admins');
        $this->forget();

        return redirect()->route('admin::accounts.index')->with($this->successSaveStatus($new->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account = Admin::findOrFail($id);

        return view('admin.accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $account = Admin::findOrFail($id);

        return view('admin.accounts.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate($this->request, $this->updateRules($id));

        $account = Admin::findOrFail($id);
        $account->update($this->request->all());

        $this->forgetQuery('admins');

        return redirect()->route('admin::accounts.index')->with($this->successUpdateStatus($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);

        if ($admin->boss) {
            return back()->with($this->failFileDestroyStatus($id));
        }

        $admin->destroy($id);

        $this->forgetQuery('admins');

        return redirect()->route('admin::accounts.index')
                         ->with($this->successDestroyStatus($id));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getChangepw()
    {
        return view('admin.accounts.change-password');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postChangepw()
    {
        $admin = Admin::findOrFail(auth()->user()->id);

        $this->validate($this->request, $this->changePwRules($admin->password));

        $admin->password = bcrypt($this->request->input('password'));
        $admin->save();

        return redirect()->route('admin::accounts.index')->with($this->successUpdateStatus(auth()->user()->id));
    }

    /**
     * @param string $pw
     *
     * @return array
     */
    private function changePwRules($pw)
    {
        return [
            'old_password' => [
                "hash:{$pw}"
            ],
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'same:password',
        ];
    }

    /**
     * @param int $id
     *
     * @return array
     */
    private function updateRules($id)
    {
        return [
            'name'  => [
                'required',
                'min:5',
                'max:20',
                'regex:/^[\pL\s]+$/u',
            ],
            'email' => 'required|email|max:255',
            Rule::unique('admins')->ignore($id),
        ];
    }

    /**
     * @return array
     */
    private function createRules()
    {
        return [
            'name'  => [
                'required',
                'min:5',
                'max:20',
                'regex:/^[\pL\s]+$/u',
            ],
            'email' => 'required|email|max:255|unique:admins',
            'password' => 'required|min:6|confirmed',
        ];
    }
}
