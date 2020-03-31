<?php

namespace App\GraphQL\Queries;

use App\Filter\AllUsersIntervalFilter;
use App\Models\DeletedUserRecord;
use App\Repositories\UserRepository;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UserCount
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field.
     * In this case, it is always `null`.
     * @param  mixed  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary
     * data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the
     * query itself, such as the execution state, the field name, path to the field from the
     * root, and more.
     *
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $allUsersFilter = new AllUsersIntervalFilter();
        $startDate = array_key_exists('startDate', $args) ? $args['startDate'] : '-1 month';
        $endDate = array_key_exists('endDate', $args) ? $args['endDate'] : 'now';

        $results = $allUsersFilter
            ->users($this->users->all())
            ->deletedUsers(DeletedUserRecord::all())->startTime($startDate)
            ->endTime($endDate)
            ->filter();

        $added = [];

        foreach ($results['added'] as $key => $item) {
            $added[] = ['date' => $key, 'value' => $item];
        }

        $deleted = [];

        foreach ($results['deleted'] as $key => $item) {
            $deleted[] = ['date' => $key, 'value' => $item];
        }

        $all = [];

        foreach ($results['all'] as $key => $item) {
            $all[] = ['date' => $key, 'value' => $item];
        }

        return [
            'deleted' => $deleted,
            'added' => $added,
            'all' => $all,
        ];
    }
}
