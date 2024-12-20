<?php

namespace App\Http\Csp;

use Spatie\Csp\Policies\Policy;
use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Value;

class CustomPolicy extends Policy
{
    public function configure()
    {

        $this->addDirective(Directive::SCRIPT, Keyword::SELF)
            ->addDirective(Directive::SCRIPT, 'ajax.googleapis.com')
            ->addDirective(Directive::SCRIPT, 'cdn.jsdelivr.net')
            ->addDirective(Directive::SCRIPT, 'code.jquery.com')
            ->addDirective(Directive::SCRIPT, 'www.google.com')
            ->addDirective(Directive::SCRIPT, 'www.gstatic.com')
            ->addDirective(Directive::SCRIPT, 'testing.dec.ft.capital')
            ->addDirective(Directive::SCRIPT, 'dev.ft.capital')
            ->addDirective(Directive::SCRIPT, 'dec.ft.capital')
            ->addDirective(Directive::STYLE, Keyword::SELF)
            ->addDirective(Directive::STYLE, 'fonts.googleapis.com')
            ->addDirective(Directive::FONT, 'fonts.gstatic.com')
            ->addDirective(Directive::FONT, 'fonts.googleapis.com')
            ->addDirective(Directive::IMG, Keyword::SELF)
            ->addDirective(Directive::IMG, 'data:')
            ->addNonceForDirective(Directive::SCRIPT)
            ->addDirective(Directive::STYLE_SRC_ELEM, Keyword::SELF)
            ->addDirective(Directive::STYLE_SRC_ELEM, 'fonts.googleapis.com')
            ->addNonceForDirective(Directive::STYLE)
            ->addDirective(Directive::FONT, ["'self'", "data:"])
            ->addDirective(Directive::MANIFEST, 'self')
            ->addDirective(Directive::SCRIPT, 'www.recaptcha.net')
            ->addDirective(Directive::SCRIPT, 'www.google.com/*')
            ->addDirective(Directive::FRAME, 'www.google.com')
            ->addDirective(Directive::FRAME, 'www.gstatic.com')
            ->addDirective(Directive::FRAME, 'www.recaptcha.net')
            ->addDirective(Directive::CONNECT, 'www.google.com')
            ->addDirective(Directive::CONNECT, 'www.gstatic.com')
            ->addDirective(Directive::CONNECT, 'www.recaptcha.net')
            ->addDirective(Directive::SCRIPT, Keyword::STRICT_DYNAMIC);
      //      $this->addDirective(Directive::SCRIPT, 'https://cryptofamily.api-us1.com');
            $this->addDirective(Directive::UPGRADE_INSECURE_REQUESTS);
    }
}
