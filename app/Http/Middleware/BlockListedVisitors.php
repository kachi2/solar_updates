<?php

namespace App\Http\Middleware;

use App\Models\BlockedVisitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Turns away IP + browser pairs on the block list (see BlockedVisitor) before
 * the session, rate limiter or any page code runs. One file-cache read per
 * request; no database query. The admin area and the Flutterwave payment
 * return are never blocked, matching the page-limit exemptions.
 */
class BlockListedVisitors
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin', 'admin/*') || $request->routeIs('verify.pay', 'confirm.payment')) {
            return $next($request);
        }

        if (BlockedVisitor::isBlocked($request)) {
            // Plain response on purpose: the error views query the database.
            return response('Access denied.', 403, ['Content-Type' => 'text/plain']);
        }

        return $next($request);
    }
}
