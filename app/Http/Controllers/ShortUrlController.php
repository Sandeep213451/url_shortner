<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === User::ROLE_SUPERADMIN) {
            // SuperAdmin can see the list of all short urls for every company
            $urls = ShortUrl::with('user', 'company')->get();
        } elseif ($user->role === User::ROLE_ADMIN) {
            // Admin can only see the list of all short urls created in their own company
            $urls = ShortUrl::where(ShortUrl::COMPANY_ID, $user->company_id)->with('user')->get();
        } elseif ($user->role === User::ROLE_MEMBER) {
            // Member can only see the list of all short urls created by themselves
            $urls = ShortUrl::where(ShortUrl::USER_ID, $user->id)->get();
        } else {
            $urls = collect();
        }

        if ($request->expectsJson()) {
            return response()->json($urls);
        }

        return view('short_urls.index', compact('urls'));
    }

    public function create()
    {
        $user = auth()->user();

        // SuperAdmin cannot create short urls
        if ($user->role === User::ROLE_SUPERADMIN) {
            abort(403, 'SuperAdmin cannot create short URLs.');
        }

        return view('short_urls.create');
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // SuperAdmin cannot create short urls
        if ($user->role === User::ROLE_SUPERADMIN) {
            abort(403, 'SuperAdmin cannot create short URLs.');
        }

        $data = $request->validate([
            ShortUrl::CODE        => 'nullable|string|unique:short_urls,code',
            ShortUrl::ORIGINAL_URL => 'required|url',
        ]);

        if (empty($data[ShortUrl::CODE])) {
            $data[ShortUrl::CODE] = Str::random(6);
        }

        $data[ShortUrl::USER_ID]    = $user->id;
        $data[ShortUrl::COMPANY_ID] = $user->company_id;

        $shortUrl = ShortUrl::create($data);

        if ($request->expectsJson()) {
            return response()->json($shortUrl, 201);
        }

        return redirect('/short-urls')->with('success', 'Short URL created successfully.');
    }
}
