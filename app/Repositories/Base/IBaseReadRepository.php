<?php

namespace App\Repositories\Base;

/**
 * Interface BaseReadRepositoryInterface.
 */
interface IBaseReadRepository
{
    /**
     * Get all.
     */
    public function all();

    /**
     * find by id.
     */
    public function find($id, $columns = ['*']);

    /**
     * Query option.
     */
    public function query($options = [], $order = []);

    /**
     * Get by option.
     */
    public function get($options = [], $columns = ['*']);

    /**
     * Get first.
     */
    public function first($options = [], $columns = ['*']);

    /**
     * Order data.
     *
     * @param null|mixed $query
     */
    public function order($order = [], $query = null);

    /**
     * Paginate data.
     */
    public function paginate($options, $page = 1, $limit = 20, $order = []);

    /**
     * Query option.
     *
     * @param null|mixed $query
     */
    public function queryOptions($options = [], $query = null);

    /**
     * Query paginate.
     */
    public function queryPaginate($query, int $page = 1, int $limit = 20);

    /**
     * Paginate query.
     */
    public function paginatedQuery($query, $page = 1, $limit = 20);
}
