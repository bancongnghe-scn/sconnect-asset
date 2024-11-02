<?php

namespace App\Support\GraphqlQueries;

class OrganizationQueries
{
    public static function getOrganizationList(array $filters): string
    {
        $ids      = implode(',', $filters['ids'] ?? []);
        $status   = implode(',', $filters['status'] ?? []);

        return "{
            OrganizationListing(
                ids: \"$ids\",
                status: \"$status\",
            ) {
                id
                name
                manager_id
                status
                parent_id
          }
        }";
    }
}
