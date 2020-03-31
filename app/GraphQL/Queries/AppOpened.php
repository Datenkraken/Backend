<?php

namespace App\GraphQL\Queries;

use App\Filter\AppOpenedFilter;
use App\Models\Datamining\AppEvent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AppOpened
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field.
     * In this case, it is always `null`.
     * @param  mixed  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that
     * is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query
     * itself, such as the execution state, the field name, path to the field from the root, and more.
     *
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $filter = new AppOpenedFilter();
        $startDate = array_key_exists('startDate', $args) ? $args['startDate'] : '-1 month';
        $endDate = array_key_exists('endDate', $args) ? $args['endDate'] : 'now';

        $filter_result = $filter
            ->startTime($startDate)
            ->endTime($endDate)
            ->appEvents(AppEvent::all())
            ->filter();

        $result = [];

        foreach ($filter_result as $key => $item) {
            $result[] = ['date' => $key, 'value' => $item];
        }

        return $result;
    }
}
