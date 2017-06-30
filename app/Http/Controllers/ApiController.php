<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kitano\Traits\Cacheable;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
//use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ApiController extends BaseController
{
    use ValidatesRequests, Cacheable;

    const ARTICLE_NOT_FOUND = 'Article not found';
    const ARTICLES_NOT_FOUND = 'No articles were found';
    const BAD_REQUEST = 'Bad Request';
    const COMMENT_DELETED = 'Comment Deleted';
    const COMMENT_UPDATED = 'Comment Updated';
    const COMMENTS_NOT_FOUND = 'No comments were found in this article';
    const FORBIDDEN = 'Forbidden';
    const INVALID_TOKEN = 'Invalid Token';
    const NOT_REGISTERED = 'Not registered';
    const UNAUTHORIZED = 'Unauthorized';
    const USER_ALREADY_CONFIRMED = 'Account already activated';
    const USER_CONFIRMED = 'Registration Confirmed';
    const USER_NOT_FOUND = 'User not found';
    const USER_UPDATED = 'User updated';
    const USERS_NOT_FOUND = 'No users were found';

    /** @var \Illuminate\Http\Request */
    protected $request;

    /** @var int */
    protected $statusCode = 200;


    /**
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get http status code

     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Successful creating
     *
     * @param   string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondCreated($message = 'Resource Created')
    {
        return $this->setStatusCode(HttpResponse::HTTP_CREATED)
                    ->respond(['message' => $message]);
    }

    /**
     * Bad Request
     *
     * @param   string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondBadRequest($message = 'Bad request')
    {
        return $this->setStatusCode(HttpResponse::HTTP_BAD_REQUEST)
                    ->respondWithError($message, 'HTTP_BAD_REQUEST');
    }

    /**
     * Forbidden
     *
     * @param   string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondForbidden($message = 'Forbidden')
    {
        return $this->setStatusCode(HttpResponse::HTTP_FORBIDDEN)
                    ->respondWithError($message, 'HTTP_FORBIDDEN');
    }

    /**
     * No Content
     *
     * @param   string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNoContent($message = 'No Content')
    {
        return $this->setStatusCode(HttpResponse::HTTP_NO_CONTENT)
                    ->respond(['message' => $message]);
    }

    /**
     * Not Found
     *
     * @param   string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound($message = 'Not Found')
    {
        return $this->setStatusCode(HttpResponse::HTTP_NOT_FOUND)
                    ->respondWithError($message, 'HTTP_NOT_FOUND');
    }

    /**
     * OK
     *
     * @param   array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondOk(array $data)
    {
        return $this->setStatusCode(HttpResponse::HTTP_OK)
                    ->respond($data);
    }

    /**
     * Unauthorized
     *
     * @param   string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondUnauthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(HttpResponse::HTTP_UNAUTHORIZED)
                    ->respondWithError($message, 'HTTP_UNAUTHORIZED');
    }

    /**
     * Unprocessable Entity
     *
     * @param   string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondUnprocessable($message = 'Unprocessable Entity')
    {
        return $this->setStatusCode(HttpResponse::HTTP_UNPROCESSABLE_ENTITY)
                    ->respondWithError($message, 'HTTP_UNPROCESSABLE_ENTITY');
    }

    /**
     * Updated
     *
     * @param   array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondUpdated(array $data)
    {
        return $this->setStatusCode(HttpResponse::HTTP_PARTIAL_CONTENT)
                    ->respond($data);
    }

    /**
     * Error response
     *
     * @param   string $message
     * @param   string $error
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message = '', $error = 'Error')
    {
        return $this->respond(['error' => strtolower($error), 'message' => $message]);
    }

    /**
     * Set http status code
     *
     * @param   int $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }


    /**
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respond(array $data)
    {
        return response()->json($data, $this->getStatusCode());
    }
}
