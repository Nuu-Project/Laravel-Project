@props(['id', 'name', 'placeholder' => '', 'rows' => 4, 'maxlength' => null, 'value' => ''])

<textarea
    class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
    id="{{ $id }}" 
    name="{{ $name }}" 
    placeholder="{{ $placeholder }}" 
    rows="{{ $rows }}" 
    @if($maxlength) maxlength="{{ $maxlength }}" @endif
>{{ $value }}</textarea> 