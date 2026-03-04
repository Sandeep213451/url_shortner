@extends('layouts.app')

@section('content')
    @php $user = auth()->user(); @endphp

    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
        <h1 style="border: none; margin: 0; padding: 0;">
            @if($user->role === \App\Models\User::ROLE_SUPERADMIN)
                All Companies' Short URLs
            @elseif($user->role === \App\Models\User::ROLE_ADMIN)
                {{ optional($user->company)->name ?? 'My Company' }}'s Short URLs
            @elseif($user->role === \App\Models\User::ROLE_MEMBER)
                My Short URLs
            @else
                Generated Short URLs
            @endif
        </h1>
        @if(in_array($user->role, [\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_MEMBER]))
            <a href="{{ route('short-urls.create') }}" class="btn btn-primary">+ Generate</a>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                @if($user->role === \App\Models\User::ROLE_SUPERADMIN)
                    <th>Company</th>
                @endif
                <th>Short URL</th>
                <th>Long URL</th>
                <th>Hits</th>
                <th>Created By</th>
                <th>Created On</th>
            </tr>
        </thead>
        <tbody>
            @forelse($urls as $url)
                <tr>
                    @if($user->role === \App\Models\User::ROLE_SUPERADMIN)
                        <td>{{ optional($url->company)->name ?? 'N/A' }}</td>
                    @endif
                    <td>
                        <a href="{{ url('/' . $url->code) }}" target="_blank">
                            {{ request()->getSchemeAndHttpHost() }}/{{ $url->code }}
                        </a>
                    </td>
                    <td title="{{ $url->original_url }}">
                        {{ \Illuminate\Support\Str::limit($url->original_url, 45) }}
                    </td>
                    <td>{{ $url->hits ?? 0 }}</td>
                    <td>{{ optional($url->user)->name ?? 'N/A' }}</td>
                    <td>{{ $url->created_at->format('d M y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $user->role === \App\Models\User::ROLE_SUPERADMIN ? 6 : 5 }}" style="color: #888; text-align: center;">
                        No short URLs found based on your permissions.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
