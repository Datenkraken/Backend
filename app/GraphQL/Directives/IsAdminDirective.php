<?php

namespace App\GraphQL\Directives;

use Closure;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;


class IsAdminDirective extends BaseDirective implements FieldMiddleware
{
	/**
     * Wrap around the final field resolver.
     *
     * @param  \Nuwave\Lighthouse\Schema\Values\FieldValue  $fieldValue
     * @param  \Closure  $next
	 *
     * @return \Nuwave\Lighthouse\Schema\Values\FieldValue
     */
	public function handleField(FieldValue $fieldValue, Closure $next) {
        if (Auth::check() && Auth::user()->is_admin) {
			return $next($fieldValue);
        }

		throw new AuthorizationException();
	}
}
