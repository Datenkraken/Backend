<?php

namespace App\GraphQL\Directives;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateWithCurrentUserDirective extends BaseDirective implements FieldResolver
{
    public function resolveField(FieldValue $fieldValue)
    {
        return $fieldValue->setResolver(
            function (
                $root,
                array $args,
                GraphQLContext $context,
                ResolveInfo $resolveInfo) {
                $modelClass = $this->getModelClass();
                $models = [];
                foreach ($args['input'] as $item) {
                    /** @var \Illuminate\Database\Eloquent\Model $model */
                    $model = new $modelClass();
                    $model->fill($item);

                    $model->user()->associate(Auth::user());
                    $model->save();
                    array_push($models, $model);
                }

                return $models;
            }
        );
    }
}
