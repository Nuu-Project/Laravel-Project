@props(['product'])
@php
    use App\Enums\Tagtype;

    $gradeTag = $product->tags->firstWhere('type', Tagtype::Grade->value);
    $semesterTag = $product->tags->firstWhere('type', Tagtype::Semester->value);
    $categoryTag = $product->tags->firstWhere('type', Tagtype::Category->value);
@endphp

<div>
    <x-h.h6>年級 :
        <x-span.font-semibold>
            @php
                $gradeTag = $product->tags->firstWhere('type', Tagtype::Grade->value);
                $semesterTag = $product->tags->firstWhere('type', Tagtype::Semester->value);
            @endphp
            {{ $gradeTag ? $gradeTag->name : '無' }}
            {{ $semesterTag ? $semesterTag->name : '學期:無' }}
        </x-span.font-semibold>
    </x-h.h6>
    
    <x-h.h6>課程 :
        <x-span.font-semibold>
            @php
                $categoryTag = $product->tags->firstWhere('type', Tagtype::Category->value);
            @endphp
            {{ $categoryTag ? $categoryTag->name : '無' }}
        </x-span.font-semibold>
    </x-h.h6>

    <x-h.h6>科目 :
        <x-span.font-semibold>
            @php
                $subjectTag = $product->tags->firstWhere('type', Tagtype::Subject->value);
            @endphp
            {{ $subjectTag ? $subjectTag->name : '無' }}
        </x-span.font-semibold>
    </x-h.h6>
</div>
