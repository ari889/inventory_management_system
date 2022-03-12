<div class="form-group {{ $col ?? '' }} {{ $required ?? '' }}">
    <label for="{{ $name }}">{{ $labelName }}</label>
    <textarea class="form-control {{ $class ?? '' }}" @if(!empty($onchange)) onchange="{{ $onchange }}" @endif id="{{ $name }}" name="{{ $name }}" placeholder="{{ $placeholder ?? '' }}">{{ $value ?? '' }}</textarea>
</div>
