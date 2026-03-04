@extends('layouts.app')

@section('content')
    <h1>Generate Short URL</h1>
    <div style="max-width: 480px;">
        <form action="{{ route('short-urls.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="original_url">Long URL</label>
                <input type="url" name="original_url" id="original_url" required
                       placeholder="https://example.com/very/long/url"
                       value="{{ old('original_url') }}">
            </div>
            <div class="form-group">
                <label for="code">Custom Code <span style="font-weight:normal; color:#888;">(optional)</span></label>
                <input type="text" name="code" id="code"
                       placeholder="e.g. my-link"
                       value="{{ old('code') }}">
                <small>If left blank, a random code will be generated.</small>
            </div>
            <button type="submit" class="btn btn-primary">Generate</button>
            <a href="{{ route('short-urls.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
