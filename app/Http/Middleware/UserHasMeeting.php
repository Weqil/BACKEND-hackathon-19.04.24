<?php

namespace App\Http\Middleware;

use App\Models\Meeting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserHasMeeting
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $meeting = Meeting::find($request->route("meeting_id"));
        $meetingUsers = $meeting->users()->where("user_id", auth()->user()->id);

        if(empty($meetingUsers)){
            abort(403, "Access denied");
        }
        return $next($request);
    }
}
