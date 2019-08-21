<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10.03.19
 * Time: 20:13
 */

namespace App\Entities\traits;


trait Pagination
{
    private $perPage = 4;

    /**
     * Method getting count items per page
     *
     * @param int|null $perPage
     * @return int
     */
    private function getPerPage(?int $perPage): int
    {
        return empty($perPage) ? $this->perPage : abs($perPage);
    }

    /**
     * Method getting offset for pagination query
     *
     * @param int|null $currentPage
     * @return int
     */
    private function getOffset(?int $currentPage): int
    {
        return $currentPage > 0 ? ($currentPage - 1) * $this->perPage : 0;
    }
}