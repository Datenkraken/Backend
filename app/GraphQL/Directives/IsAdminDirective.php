<?php

namespace App\GraphQL\Directives;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Nuwave\Lighthouse\Support\Contracts\Directive;


class IsAdminDirective implements Directive, FieldMiddleware
{

    public function name(): string
    {
        return "isAdmin";
    }

    /**
     * Wrap around the final field resolver.
     *
     * @param FieldValue $fieldValue
     * @param Closure $next
     *
     * @return FieldValue
     */
    public function handleField(FieldValue $fieldValue, Closure $next): FieldValue {
        $previousResolver = $fieldValue->getResolver();

        $fieldValue->setResolver(
            function ($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) use ($previousResolver) {
                if (Auth::check() && Auth::user()->is_admin) {
                    return $previousResolver($root, $args, $context, $resolveInfo);
                }

                throw new AuthorizationException("You are not an admin");
            }
        );

        return $next($fieldValue);
    }
}
