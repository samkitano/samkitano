<?php

namespace App\Http\Controllers\Api;

use Mail;
use App\User;
use App\Contact;
use App\Mail\SiteContact;
use App\Mail\SiteContactResponder;
use App\Http\Controllers\ApiController;

class ContactsController extends ApiController
{
    //use ValidatesRequests;

    /** @var array */
    protected $contactData = [];

    /** @var array */
    protected $savedData = [];


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke()
    {
        /**
         * Would be nice to perform validation before anything else,
         * but frontend doesn't really knows a given user's email
         * so, first we will need to check if the sender is a
         * registered user, either by a passed slug, or by
         * email (if the sender was not authenticated).
         */
        $this->checkSenderIsUser()
             ->validateRequest()
             ->setData()
             ->saveContact()
             ->sendAdminEmail()
             ->autoRespond();

        return $this->respondCreated();
    }

    /**
     * @return $this
     */
    protected function checkSenderIsUser()
    {
        // sender may be a registered user, let's find out
        $slug = $this->request->input('isuser');

        isset($slug)
            ? $this->fixRequestForAuthenticatedSender($slug)
            : $this->fixRequestForUnauthenticatedSender();

        return $this;
    }

    /**
     * @return void
     */
    protected function fixRequestForUnauthenticatedSender()
    {
        // sender may be a registered user, let's find out
        $email = $this->request->email;
        $user = isset($email) ? User::findByEmail($email) : null;

        if (! is_null($user)) {
            $this->request->user_id = $user->id;
        }
    }

    /**
     * @param $slug
     *
     * @return void
     */
    protected function fixRequestForAuthenticatedSender($slug)
    {
        $user = User::findBySlug($slug);

        if (! is_null($user)) {
            $this->request->email = $user->email;
            $this->request->user_id = $user->id;
        }
    }

    /**
     * @return $this
     */
    protected function setData()
    {
        $this->contactData = [
            'name' => $this->request->name,
            'email' => $this->request->email,
            'message' => $this->request->body,
            'user_id' => $this->request->user_id
        ];

        return $this;
    }

    /**
     * @return $this
     */
    protected function validateRequest()
    {
        $inputs = array_except($this->request->input(), ['_method', '_token', 'isuser']);

        $rules = [];

        foreach ($inputs as $input => $val) {
            $rules[$input] = $this->getRules($input);
        }

        $this->validate($this->request, $rules);

        return $this;
    }

    /**
     * @param $field
     *
     * @return mixed
     */
    protected function getRules($field)
    {
        $rules = [
            'isuser' => [
                'required'
            ],
            'name'  => [
                'required',
                'min:5',
                'max:150',
                'regex:/^[\pL\s.-]+$/u',
            ],
            'email' => [
                'required',
                'email',
            ],
            'body' => [
                'required',
                'min:6'
            ],
        ];

        return $rules[$field];
    }

    /**
     * @return $this
     */
    protected function sendAdminEmail()
    {
        $email = new SiteContact($this->savedData);

        Mail::to(env('MAIL_TO_ADMIN'))->send($email);

        return $this;
    }

    /**
     * @return $this
     */
    protected function autoRespond()
    {
        $email = new SiteContactResponder($this->contactData);

        Mail::to($this->contactData['email'])->send($email);

        return $this;
    }

    /**
     * @return $this
     */
    protected function saveContact()
    {
        $this->savedData = Contact::create($this->contactData)->toArray();

        $this->forgetQuery('contacts');

        return $this;
    }
}
