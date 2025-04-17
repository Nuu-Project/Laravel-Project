@props(['product'])
@php
    use App\Enums\Tagtype;
    
    $gradeTag = $product->tags->firstWhere('type', Tagtype::Grade->value);
    $semesterTag = $product->tags->firstWhere('type', Tagtype::Semester->value);
    $categoryTag = $product->tags->firstWhere('type', Tagtype::Category->value);
@endphp

<div class="flex items-center justify-between mb-8">
    <x-h.h6>年級 :
        <x-span.font-semibold>
            {{ $gradeTag ? $gradeTag->name : '無' }}
            {{ $semesterTag ? $semesterTag->name : '學期:無' }}
        </x-span.font-semibold>
    </x-h.h6>
    <x-h.h6>課程 :
        <x-span.font-semibold>
            {{ $categoryTag ? $categoryTag->name : '無' }}
        </x-span.font-semibold>
    </x-h.h6>
</div> 