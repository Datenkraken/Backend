<?php

namespace App\Csp;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Policy as CspPolicy;

class Policy extends CspPolicy
{
    public function configure()
    {
        $this
            ->addDirective(Directive::BASE, Keyword::SELF)
            ->addDirective(Directive::CONNECT, Keyword::SELF)
            ->addDirective(Directive::DEFAULT, Keyword::SELF)
            ->addDirective(Directive::FORM_ACTION, Keyword::SELF)
            ->addDirective(Directive::IMG, [ Keyword::SELF, Keyword::UNSAFE_INLINE, 'data:' ])
            ->addDirective(Directive::MEDIA, Keyword::SELF)
            ->addDirective(Directive::OBJECT, Keyword::NONE)
            ->addDirective(Directive::SCRIPT, [ Keyword::STRICT_DYNAMIC, Keyword::UNSAFE_INLINE, Keyword::UNSAFE_EVAL ])
            ->addDirective(Directive::STYLE, [ Keyword::SELF, Keyword::UNSAFE_INLINE ])
            ->addNonceForDirective(Directive::SCRIPT);
    }
}
