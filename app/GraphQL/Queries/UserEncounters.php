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
            $result[] = [
                            'user1' => $encounter->user_id1,
                            'user2' => $encounter->user_id2,
                            'timestamp' => ($encounter->start_time->getTimestamp()
                                + $encounter->start_time->getTimestamp()) / 2,
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
