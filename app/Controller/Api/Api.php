<?php

namespace App\Controller\Api;

class Api
{
    public static function getDetails($request)
    {
        return [
            'name' => 'CodeRockr backend test',
            'version' => 'v1.0.0',
            'author' => 'Lucas Santos',
            'email' => 'lucasvinicius.bs03@gmail.com'
        ];
    }


    public static function getPagination($request, $pagination)
    {
        $queryParams = $request->getQueryParams();
        $pages = $pagination->getPages();
        $currentUrl = $request->getRouter()->getCurrentUrl();
        
        $currentPage = isset($queryParams['page']) ? (int)$queryParams['page'] : 1;
        $pageCount = !empty($pages) ? count($pages) : 1;        
        $previousPageUrl = '';
        $nextPageUrl = '';
        if($currentPage == $pageCount && $pageCount > 1)
        {
            $previousPageUrl = $currentUrl.'?page='.$currentPage - 1;
            $nextPageUrl = $currentUrl;
        }
        if($currentPage < $pageCount)
        {
            $previousPageUrl = $currentPage > 1
                ? $currentUrl.'?page='.$currentPage - 1
                : $currentUrl;
            $nextPageUrl = $currentUrl.'?page='.$currentPage + 1;
        }        

        return [
            'currentPage' => $currentPage,
            'pageCount' => $pageCount,
            'previousPageUrl' => $previousPageUrl,
            'nextPageUrl' => $nextPageUrl
        ];
    }
}