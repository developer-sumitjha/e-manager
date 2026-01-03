@extends('admin.settings.layout')

@section('settings-title', 'API Configuration')
@section('settings-description', 'Setup API keys, rate limits, and third-party integrations')
@section('settings-icon', 'code')
@section('settings-group', 'general')

@section('settings-content')
<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    <input type="hidden" name="group" value="api">

    <div class="settings-form-grid">
        @foreach($settings as $key => $setting)
        <div class="form-group">
            <label for="{{ $key }}" class="form-label">
                {{ $setting['label'] }}
                @if(in_array($setting['type'], ['text', 'email', 'number']))
                    <span class="text-danger">*</span>
                @endif
            </label>
            
            @if($setting['description'])
            <p class="form-description">{{ $setting['description'] }}</p>
            @endif

            @if($setting['type'] == 'text' || $setting['type'] == 'email' || $setting['type'] == 'number')
                <input type="{{ $setting['type'] }}" 
                       name="{{ $key }}" 
                       id="{{ $key }}" 
                       class="form-control" 
                       value="{{ $setting['value'] }}"
                       {{ in_array($setting['type'], ['text', 'email']) ? 'required' : '' }}>
            
            @elseif($setting['type'] == 'password')
                <input type="password" 
                       name="{{ $key }}" 
                       id="{{ $key }}" 
                       class="form-control" 
                       value="{{ $setting['value'] }}"
                       placeholder="••••••••">
            
            @elseif($setting['type'] == 'textarea')
                <textarea name="{{ $key }}" 
                          id="{{ $key }}" 
                          class="form-control" 
                          rows="4">{{ $setting['value'] }}</textarea>
            
            @elseif($setting['type'] == 'select')
                <select name="{{ $key }}" 
                        id="{{ $key }}" 
                        class="form-select">
                    @if($setting['options'])
                        @foreach($setting['options'] as $option)
                            <option value="{{ $option }}" {{ $setting['value'] == $option ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    @endif
                </select>
            
            @elseif($setting['type'] == 'checkbox')
                <div class="form-check form-switch">
                    <input type="checkbox" 
                           name="{{ $key }}" 
                           id="{{ $key }}" 
                           class="form-check-input" 
                           value="1"
                           {{ $setting['value'] ? 'checked' : '' }}>
                    <label class="form-check-label" for="{{ $key }}">
                        Enable
                    </label>
                </div>
            @endif
        </div>
        @endforeach
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Settings
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Save Changes
        </button>
    </div>
</form>
@endsection

