<?php

namespace App\Http\Controllers;

use App\Actions\ParseDomainAction;
use Illuminate\Http\Request;

class ParserController extends Controller
{
    public function parse(Request $request)
    {
        if(!$domain = $request->get('domain')){
            return [
                'param domain is required'
            ];
        }
        return app(ParseDomainAction::class)->run($domain);
    }
}
