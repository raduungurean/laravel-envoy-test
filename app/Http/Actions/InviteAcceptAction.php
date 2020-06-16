<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use App\Invite;
use App\User;
use Illuminate\Http\Request;
use Validator;
use DB;

class InviteAcceptAction extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'hash' => 'required',
            'email' => 'required|email|unique:users,email|max:100',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()],
                400
            );
        }

        $invite = Invite::with('group')
            ->with('user')
            ->where('hash', $request->input('hash'))
            ->first();

        if (!$invite) {
            return response()->json(
                ['errors' => ['general' => 'Hash not available']],
                400
            );
        }

        // TODO: profile picture
        // TODO: move in repository
        // and use transactions
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->email_verified_at = now();
        $user->save();
        $playerId = $user->id;

        if ($playerId) {
            DB::table('user_group')->insert(
                ['user_id' => $playerId, 'group_id' => $invite->group_id, ]
            );
            $invite->accepted = 'yes';
            $invite->save();
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }
}
