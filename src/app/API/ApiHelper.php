<?php

namespace App\API;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait ApiHelper
{
	/**
	 * Return no content response
	 *
	 * @return Response
	 */
	public function noContent()
	{
		$response = new Response();

		return $response->setStatusCode(204);
	}

	/**
	 * Respond with a created response and associate a location if provided.
	 *
	 * @param null $location
	 * @param null $content
	 * @return Response
	 */
	public function created($location = null, $content = null)
	{
		$response = new Response($content);
		$response->setStatusCode(201);

		if (!is_null($location)) {
			$response->header('Location', $location);
		}
		return $response;
	}

	public function authenticated($data)
	{
		if (isset($data->error)) {
			return $this->error($data->message, 401);
		} else {
			return $this->succeed([
				'token_type' => $data->token_type,
				'expires_in' => $data->expires_in,
				'access_token' => $data->access_token,
				'refresh_token' => $data->refresh_token,
			], 200);
		}
	}

	public function tokenRefreshed($data)
	{
		if (isset($data->error)) {
			return $this->error($data->message, 400);
		} else {
			return $this->succeed([
				'token_type' => $data->token_type,
				'expires_in' => $data->expires_in,
				'access_token' => $data->access_token,
				'refresh_token' => $data->refresh_token,
			], 200);
		}
	}

	/**
	 * @param Model $model
	 * @return array
	 */
	public function renderJson(Model $model)
	{
		return [
			'result' => true,
			'data' => $model,
		];
	}

	/**
	 * @param LengthAwarePaginator $paginator
	 * @return array
	 */
	public function renderPaginatorJson(LengthAwarePaginator $paginator)
	{
		return [
			'result' => true,
			'pagination' => [
				'total' => $paginator->total(),
				'per_page' => $paginator->perPage(),
				'current_page' => $paginator->currentPage(),
				'last_page' => $paginator->lastPage(),
				'next_page_url' => $paginator->nextPageUrl(),
				'prev_page_url' => $paginator->previousPageUrl(),
				'from' => $paginator->firstItem(),
				'to' => $paginator->lastItem(),
			],
			"data" => $paginator->items(),
		];
	}

	/**
	 * Return an error response.
	 *
	 * @param string $message
	 * @param int $statusCode
	 * @param int $clientErrorCode
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Symfony\Component\HttpKernel\Exception\HttpException
	 */
	public function error($message, $statusCode, $clientErrorCode = 0)
	{
		return response()->json([
			'result' => false,
			'error' => $message,
			'errors' => null,
			'error_code' => $clientErrorCode
		], $statusCode);
	}

	/**
	 * Return an error response.
	 *
	 * @param string $message
	 * @param array $errors
	 * @param int $statusCode
	 * @param int $clientErrorCode
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function errors($message, $errors, $statusCode, $clientErrorCode = 0)
	{
		return response()->json([
			'result' => false,
			'error' => $message,
			'errors' => $errors,
			'error_code' => $clientErrorCode
		], $statusCode);
	}

	/**
	 * Return an error response.
	 *
	 * @param $data
	 * @param int $statusCode
	 * @return \Illuminate\Http\JsonResponse
	 * @internal param string $message
	 */
	public function succeed($data, $statusCode)
	{
		return response()->json([
			'result' => true,
			'data' => $data
		], $statusCode);
	}

	/**
	 * Return a 404 not found error.
	 *
	 * @param string $message
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Symfony\Component\HttpKernel\Exception\HttpException
	 */
	public function errorNotFound($message = 'Not Found')
	{
		return $this->error($message, 404);
	}

	/**
	 * Return a 400 bad request error.
	 *
	 * @param string $message
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Symfony\Component\HttpKernel\Exception\HttpException
	 */
	public function errorBadRequest($message = 'Bad Request')
	{
		return $this->error($message, 400);
	}

	/**
	 * Return a 403 forbidden error.
	 *
	 * @param string $message
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Symfony\Component\HttpKernel\Exception\HttpException
	 */
	public function errorForbidden($message = 'Forbidden')
	{
		return $this->error($message, 403);
	}

	/**
	 * Return a 500 internal server error.
	 *
	 * @param string $message
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Symfony\Component\HttpKernel\Exception\HttpException
	 */
	public function errorInternal($message = 'Internal Error')
	{
		return $this->error($message, 500);
	}

	/**
	 * Return a 401 unauthorized error.
	 *
	 * @param string $message
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Symfony\Component\HttpKernel\Exception\HttpException
	 */
	public function errorUnauthorized($message = 'Unauthorized')
	{
		return $this->error($message, 401);
	}

	/**
	 * Return a 405 method not allowed error.
	 *
	 * @param string $message
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Symfony\Component\HttpKernel\Exception\HttpException
	 */
	public function errorMethodNotAllowed($message = 'Method Not Allowed')
	{
		return $this->error($message, 405);
	}
}
