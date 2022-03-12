<div class="form-group {{ $col ?? '' }} {{ $required ?? '' }}">
    <label for="{{ $name }}">{{ $labelName }}</label>
    <input type="{{ $type ?? 'text' }}" class="form-control {{ $class ?? '' }}" id="{{ $name }}" name="{{ $name }}" placeholder="{{ $placeholder ?? '' }}" value="{{ $value ?? '' }}" @if(!empty($onkeyup)) onkeyup="{{ $onkeyup ?? '' }}" @endif />
</div>