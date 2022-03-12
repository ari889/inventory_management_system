<div class="form-group {{ $col ?? '' }} {{ $required ?? '' }}">
    <label for="{{ $name }}">{{ $labelName }}</label>
    <select class="form-control {{ $class ?? '' }}"
    @if(!empty($onchange)) onchange="{{ $onchange }}" @endif
    id="{{ $name }}" name="{{ $name }}" @if(!empty($onchange)) onchange="{{ $onchange }}" @endif data-live-search-placeholder="Search" data-live-search="true" title="Choose one of the following">
        <option value=" ">Select Please</option>
        {{ $slot }}
    </select>
</div>
