<?php

namespace App\GraphQL\Queries;

use App\Models\Datamining\LocationCoordinates;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AllCoords
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $coords = LocationCoordinates::orderBy('timestamp', 'asc')->get();
        $result = [];

        foreach ($coords as $coord) {
            $result[] = [
                            'u' => $coord->user_id,
                            't' => $coord->timestamp->getTimestamp(),
                            'la' => $coord->latitude,
                            'lo' => $coord->longitude
                        ];
        }

        return $result;
    }
}
