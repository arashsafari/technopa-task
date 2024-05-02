<?php

use App\Services\ResponseService;

if (!function_exists('apiResponse')) {
    /**
     * api response
     *
     * @return ResponseService
     */
    function apiResponse(): ResponseService
    {
        return app('response');
    }
}
