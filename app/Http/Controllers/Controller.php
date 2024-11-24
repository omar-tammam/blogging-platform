<?php

namespace App\Http\Controllers;

use App\Enum\HttpStatusCodeEnum;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use JetBrains\PhpStorm\ArrayShape;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    /**
     * @param  $payload
     * @param int $statusCode
     * @param string|null $message
     * @return JsonResponse
     */
    protected function response($payload, int $statusCode, string $message = null): JsonResponse
    {
        if(is_null($message)){
            switch ($statusCode) {
                case HttpStatusCodeEnum::CREATED:
                    $message = trans('common.resource_added_successfully');
                    break;
                case HttpStatusCodeEnum::INTERNAL_SERVER_ERROR:
                    $message = trans('common.something_went_wrong');
                    break;
                case HttpStatusCodeEnum::UNAUTHORIZED:
                    $message = trans('common.unauthorised');
                    break;
                default:
                    $message = null;
                    break;
            }
        }
        $request = Request()->segment(1);
        // has version
        $version = str_contains($request, 'v') ? substr($request, 1) : 1;

        $response = [
            "version" => $version,
            "data" => $payload,
            "code" => $statusCode
        ];
        if ($message) {
            $response["message"] = $message;
        }
        return response()->json($response, $statusCode, [], JSON_INVALID_UTF8_IGNORE);
    }


    /**
     * @param object $data
     * @param object $paginator
     * @return array
     */
    #[ArrayShape(['items' => "object", "pagination" => "array"])]
    protected function formatPaginationData(object $data, object $paginator): array
    {
        return [
            'items' => $data,
            "pagination" => [
                "total" => $paginator->total(),
                "perPage" => $paginator->perPage(),
                "currentPage" => $paginator->currentPage(),
                "nextPage" => $paginator->nextPageUrl(),
                "previousPage" => $paginator->previousPageUrl()
            ]];
    }

    /**
     * @param object $data
     * @param int $total
     * @param int $perPage
     * @param int $page
     * @return array
     */
    #[ArrayShape(['items' => "object", "pagination" => "array"])]
    protected function formatRandomPaginationData(object $data, int $total=0, int $perPage = 10 , int $page = 1 ): array
    {
        return [
            'items' => $data,
            "pagination" => [
                "total" => $total,
                "perPage" => $perPage,
                "currentPage" => $page,
                "nextPage" => null,
                "previousPage" => null
            ]];
    }

}
