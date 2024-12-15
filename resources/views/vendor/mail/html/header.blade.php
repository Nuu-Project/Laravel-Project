@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
                <img src="{{ asset('images/book-4-fix.png') }}" class="logo" alt="聯大二手書交易網icon">
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>
