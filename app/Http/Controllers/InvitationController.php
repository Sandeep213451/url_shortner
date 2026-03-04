<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    /**
     * Show the invite form.
     */
    public function showForm()
    {
        $actor = auth()->user();

        if (!in_array($actor->role, [User::ROLE_SUPERADMIN, User::ROLE_ADMIN])) {
            abort(403);
        }

        $companies = Company::all();

        return view('invite', compact('companies'));
    }

    /**
     * Process the invitation and create the new user.
     */
    public function send(Request $request)
    {
        $actor = $request->user();

        // Only SuperAdmin and Admin can invite
        if (!in_array($actor->role, [User::ROLE_SUPERADMIN, User::ROLE_ADMIN])) {
            abort(403);
        }

        $data = $request->validate([
            User::NAME       => 'required|string|max:255',
            User::EMAIL      => 'required|email|unique:users,email',
            User::ROLE       => 'required|string|in:' . implode(',', [User::ROLE_ADMIN, User::ROLE_MEMBER]),
            User::COMPANY_ID => 'nullable|exists:companies,id',
        ]);

        $inviteRole = $data[User::ROLE];

        // SuperAdmin can invite an Admin in a new company
        if ($actor->role === User::ROLE_SUPERADMIN) {
            if ($inviteRole !== User::ROLE_ADMIN) {
                abort(403, 'SuperAdmin can only invite Admins.');
            }
            if (empty($data[User::COMPANY_ID])) {
                abort(403, 'SuperAdmin must specify a company when inviting an Admin.');
            }
        }

        // An Admin can invite another Admin or Member in their own company
        if ($actor->role === User::ROLE_ADMIN) {
            // Admin's invitees always belong to their own company
            $data[User::COMPANY_ID] = $actor->company_id;
        }

        $newUser = User::create([
            User::NAME       => $data[User::NAME],
            User::EMAIL      => $data[User::EMAIL],
            User::PASSWORD   => Hash::make(Str::random(12)),
            User::ROLE       => $inviteRole,
            User::COMPANY_ID => $data[User::COMPANY_ID] ?? null,
        ]);

        if ($request->expectsJson()) {
            return response()->json($newUser, 201);
        }

        return redirect('/short-urls')->with('success', 'User invited successfully.');
    }
}
