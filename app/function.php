<?php
function isAdminCheck()
{
    return auth('web')->check() ? ( auth('admin')->check() ? true : false ) : false;
}

// check Url
if (!function_exists('checkUrl')) {    
    function checkUrl(): string
    {
        $uri = str_replace('//www.', '//', request()->getUri());
        
        if (strpos($uri, config('site.common.api.url')) !== false) {
            return 'api';
        }

        if (strpos($uri, config('site.common.admin.url')) !== false) {
            return 'admin';
        }
        return 'web';
    }
}

// set list seq (paging 있을때)
if (!function_exists('setListSeq')) {
    function setListSeq(object $data)
    {
        $count = 0;
        $total = $data->total();
        $perPage = $data->perPage();
        $currentPage = $data->currentPage();

        // seq 라는 순번 필드를 추가
        $data->getCollection()->transform(function ($data) use ($total, $perPage, $currentPage, &$count) {
            $data->seq = ($total - ($perPage * ($currentPage - 1))) - $count;
            $count++;
            return $data;
        });

        return $data;
    }
}

function excelEntity($array = [])
{
    // 특수문자 때문에
    foreach ($array as $item) {
        $newRow[] = html_entity_decode($item);
    }

    return $newRow ?? [];
}
?>