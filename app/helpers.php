<?php

if (!function_exists('sortUrl')) {
    function sortUrl($field, $currentField, $currentDirection)
    {
        $direction = ($currentField === $field && $currentDirection === 'asc') ? 'desc' : 'asc';
        return request()->fullUrlWithQuery(['sort' => $field, 'direction' => $direction]);
    }
}

if (!function_exists('sortIcon')) {
    function sortIcon($field, $currentField, $currentDirection)
    {
        if ($field !== $currentField) return '';
        return $currentDirection === 'asc' ? '↑' : '↓';
    }
}