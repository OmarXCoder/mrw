<?php
namespace App\Traits;

use Illuminate\Validation\Validator;

trait ApiJsonResponse
{
    /**
     * HTTP status code
     *
     * @var int
     */
    protected $statusCode = 200;

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function respond($data = [], array $headers = [])
    {
        return response()->json($data, $this->statusCode, $headers);
    }

    public function respondWithSuccess(string $message = null, array $data = null)
    {
        return $this->respond([
            'success' => [
                'message' => $message ?? 'OK',
                'data'    => $data
            ],
        ]);
    }

    public function respondWithError(string $message = null, array $data = null)
    {
        return $this->respond([
            'error' => [
                'message' => $message ?? 'Error!',
                'data'    => $data
            ]
        ]);
    }

    public function respondNotFound(string $message = null)
    {
        return $this->setStatusCode(404)->respondWithError($message ?? 'Not Found');
    }

    public function respondUnauthorized(string $message = null)
    {
        return $this->setStatusCode(401)->respondWithError($message ?? 'Unauthorized');
    }

    public function respondForbidden(string $message = null)
    {
        return $this->setStatusCode(403)->respondWithError($message ?? 'Forbidden');
    }

    public function respondValidationErrors(Validator $validator)
    {
        return $this->setStatusCode(422)
            ->respondWithError(
                'Unprocessable Entity',
                array_map(fn ($field) => $field[0], $validator->errors()->toArray())
            );
    }

    public function respondBadRequest(string $message = null)
    {
        return $this->setStatusCode(400)->respondWithError($message ?? 'Bad Request');
    }

    public function respondInternalError(string $message = null)
    {
        return $this->setStatusCode(500)->respondWithError($message ?? 'Internal Server Error');
    }

    public function respondInvalidQuery(string $message = null)
    {
        return $this->setStatusCode(500)->respondWithError($message ?? 'Query Exception');
    }

    public function respondResourceCreated($data, $location = null)
    {
        $headers = $location ? ['location' => $location] : [];

        return $this->setStatusCode(201)->respond(['data' => $data], $headers);
    }

    public function respondResourceDeleted($id)
    {
        return $this->setStatusCode(200)->respondWithSuccess('Resource deleted', ['id' => $id]);
    }

    public function respondNoContent()
    {
        return $this->setStatusCode(204)->respond();
    }
}
