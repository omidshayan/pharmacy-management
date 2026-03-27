<?php
// pagination start
function paginate($data, $perPage)
{
    $totalRows = count($data);
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $totalPages = ceil($totalRows / $perPage);
    $currentPage = min($currentPage, $totalPages);
    $currentPage = max($currentPage, 1);
    $currentRow = ($currentPage - 1) * $perPage;
    $data = array_slice($data, $currentRow, $perPage);
    return $data;
}

function paginateView($data, $perPage)
{
    $totalRows = count($data);
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $totalPages = ceil($totalRows / $perPage);
    $currentPage = min($currentPage, $totalPages);
    $currentPage = max($currentPage, 1);
    $paginateView = '';
    $paginateView .= ($currentPage != 1) ? '<li class="page-item"><a href="' . paginateUrl(1) . '" class="page-link">' . _first . '</a></li>' : '';

    $paginateView .= (($currentPage - 2) >= 1) ? '<li class="page-item"><a href="' . paginateUrl($currentPage - 2) . '" class="page-link">' . ($currentPage - 2) . '</a></li>' : '';

    $paginateView .= (($currentPage - 1) >= 1) ? '<li class="page-item"><a href="' . paginateUrl($currentPage - 1) . '" class="page-link">' . ($currentPage - 1) . '</a></li>' : '';

    $paginateView .= '<li class="page-item"><a href="' . paginateUrl($currentPage) . '" class="page-link active-page">' . ($currentPage) . '</a></li>';

    $paginateView .= (($currentPage + 1) <= $totalPages) ? '<li class="page-item"><a href="' . paginateUrl($currentPage + 1) . '" class="page-link">' . ($currentPage + 1) . '</a></li>' : '';

    $paginateView .= (($currentPage + 2) <= $totalPages) ? '<li class="page-item"><a href="' . paginateUrl($currentPage + 2) . '" class="page-link">' . ($currentPage + 2) . '</a></li>' : '';

    $paginateView .= ($currentPage != $totalPages) ? '<li class="page-item"><a href="' . paginateUrl($totalPages) . '" class="page-link">' . _end . '</a></li>' : '';

    return $paginateView;
}

function paginateUrl($page)
{
    $urlArray = explode('?', currentUrl());
    if (isset($urlArray[1])) {

        $_GET['page'] = $page;
        $getVariables = array_map(function ($value, $key) {
            return $key . '=' . $value;
        }, $_GET, array_keys($_GET));
        return $urlArray[0] . '?' . implode('&', $getVariables);
    } else {
        return currentUrl() . '?page=' . $page;
    }
}
// pagination end