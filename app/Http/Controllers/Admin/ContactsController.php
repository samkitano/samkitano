<?php

namespace App\Http\Controllers\Admin;

use App\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

class ContactsController extends AdminController
{
    /**
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        parent::setResource('Contact');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.contacts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin.contacts.show')
               ->with('contact', Contact::findOrFail($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Contact::destroy($id);

        $this->forgetQuery('contacts');

        return redirect()->route('admin::contacts.index')
                         ->with($this->successDestroyStatus($id));
    }

    /**
     * Mark message as read
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markRead()
    {
        $contact = Contact::findOrFail($this->request->id);

        if (! $contact->read) {
            $contact->read = true;
            $contact->save();

            $this->forgetQuery('contacts');
        }

        return response()->json(ICON_CHECK_MARK_GREEN);
    }
}
