@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
                <div style="text-align: center;">
                    <img src="{{ asset('images/book-4-fix.png') }}" class="logo" alt="聯大二手書網icon">
                    <div style="margin-top: 10px;">聯大二手書網</div>
                </div>
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>
