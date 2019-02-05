<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;

class ApiController extends Controller
{
    const HTTP_NOT_FOUND = 404;
    const HTTP_INTERNAL_SERVER_ERROR = 500;
    const HTTP_CREATED = 201;
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    /**
     * @var integer
     */
    protected $statusCode = 200;

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param  string $message
     * @return mixed
     */
    public function respondNotFound($message = 'Not Found!')
    {
        return $this->setStatusCode(self::HTTP_NOT_FOUND)->respondWithError($message);
    }

    /**
     * @param  string $message
     * @return mixed
     */
    public function respondInternalError($message = 'Internal Error!')
    {
        return $this->setStatusCode(self::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message);
    }

    /**
     * @param  string $message
     * @return mixed
     */
    public function respondCreated($message)
    {
        return $this->setStatusCode(self::HTTP_CREATED)->respond([
            'message' => $message
        ]);
    }

    /**
     * @param  string $message
     * @return mixed
     */
    public function respondOk($message)
    {
        return $this->respond([
            'data' => [
                'message' => $message,
                'status_code' => $this->getStatusCode()
            ]
        ]);
    }

    /**
     * @param  string $message
     * @return mixed
     */
    public function respondUnprocessableEntity($message)
    {
        return $this->setStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message);
    }

    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers); 
    }

    public function respondWithError($message)
    {
        return $this->respond([
            'error' => [
                'message' => $message,
                'status_code' => $this->getStatusCode()
            ]
        ]);
    }

    /**
     * @param  LengthAwarePaginator $lessons
     * @param  $data
     * @return mixed
     */
    protected function respondWithPagination(LengthAwarePaginator $lessons, $data)
    {
        $data = array_merge($data, [
            'paginator' => [
                'total_count' => $lessons->total(),
                'total_pages' => ceil($lessons->total() / $lessons->perPage()),
                'current_page' => $lessons->currentPage(),
                'limit' => $lessons->perPage()
            ]
        ]);

        return $this->respond($data);
    }
}
