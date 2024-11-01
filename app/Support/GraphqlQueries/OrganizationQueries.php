<?php

namespace App\Support\GraphqlQueries;

class OrganizationQueries
{
    public static function getOrganizationList(array $filters): string
    {
        $ids   = implode(',', $filters['ids'] ?? []);
        $page  = $filters['page'] ?? 1;
        $limit = $filters['limit'] ?? 10;

        return "{
            OrganizationListing(
                ids: \"$ids\",
                page: $page,
                limit: $limit
            ) {
                data {
                  id
                  name
                  manager_id
                }
                total
                from
                to
                per_page
                current_page
                last_page
          }
        }";
    }
}
