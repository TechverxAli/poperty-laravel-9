<?php

namespace App\Traits;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ApiResponseTrait
{
    /**
     * @param mixed $data
     * @param string $message
     * @param bool $status
     * @param int $code
     * @return JsonResponse
     */
    public static function response(mixed $data = [], string $message = 'OK', bool $status = true, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => $status,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function handleException($request, $exception): JsonResponse
    {
        if ($exception instanceof ValidationException) {
            return $this->response(message: $exception->getMessage(), status: false, code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if ($exception instanceof ModelNotFoundException) {
            $modelName = strtolower(class_basename($exception->getModel()));

            return $this->response(
                message: "Does not exists any {$modelName} with the specified id"
                , status: false, code: Response::HTTP_NOT_FOUND);
        }
        if ($exception instanceof AuthenticationException) {
            return $this->response(message: 'Unauthenticated', status: false, code: Response::HTTP_FORBIDDEN);
        }
        if ($exception instanceof AuthorizationException) {
            return $this->response(message: $exception->getMessage(), status: false, code: Response::HTTP_FORBIDDEN);
        }
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->response(
                message: 'The specified method for the request is invalid', status: false, code: Response::HTTP_METHOD_NOT_ALLOWED
            );
        }
        if ($exception instanceof NotFoundHttpException) {
            return $this->response(
                message: 'The specified URL cannot be found'
                , status: false, code: Response::HTTP_NOT_FOUND);
        }
        if ($exception instanceof HttpException) {
            return $this->response(
                message: $exception->getMessage(), status: false, code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        if ($exception instanceof QueryException) {
            $errorCode = $exception->errorInfo[1];
            if ($errorCode == 1451) {
                return $this->response(
                    message: 'Cannot remove this resource permanently. It is related with any other resource'
                    , status: false, code: Response::HTTP_CONFLICT);
            }
        }

        return $this->response(message: $exception, status: false, code: Response::HTTP_INTERNAL_SERVER_ERROR);
    }

}
