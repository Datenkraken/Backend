<?php

namespace App\GraphQL\Queries;

use App\Models\Datamining\LocationCoordinates;
use App\Models\ProcessedData\UserEncounter;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UserEncounters
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $encounters = UserEncounter::get();
        $result = [];
        foreach ($encounters as $encounter) {
            $participants = $encounter->participants();
            $result[] = [
                            'user1' => $participants[0],
                            'user2' => $participants[1],
                            'timestamp' => floor(( $encounter->start_time->getTimestamp() + $encounter->end_time->getTimestamp()) / 2),
                        ];
        }
        usort($result, [$this, 'cmp']);
        return $result;
    }

    function cmp($a, $b) {
        if ($a['timestamp'] == $b['timestamp']) return 0;
        return ($a['timestamp'] < $b['timestamp']) ? -1 : 1;
    }
}
