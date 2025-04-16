@props(['users'])

<x-table.gray-200>
    <x-thead.user />
    <x-tbody>
        @foreach ($users as $user)
            <tr>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <img class="h-10 w-10 rounded-full"
                                src="{{ asset('images/account.png') }}" alt="{{ $user->name }}">
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>

                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                        @if ($user->hasRole('admin'))
                            管理者
                        @else
                            使用者
                        @endif
                    </div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                    @if ($user->time_limit && \Carbon\Carbon::parse($user->time_limit)->isFuture())
                        <div class="flex items-center space-x-2">
                            <x-button.red-short
                                onclick="showSuspendDialog({{ $user->id }}, {{ json_encode($user->name) }})">
                                停用
                            </x-button.red-short>
                            <form action="{{ route('admin.users.active', ['user' => $user->id]) }}"
                                method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <x-button.blue-short title="停用到期時間：{{ $user->time_limit }}">
                                    ({{ \Carbon\Carbon::parse($user->time_limit)->diffForHumans() }})
                                    啟用
                                </x-button.blue-short>
                            </form>
                        </div>
                    @else
                        <x-button.red-short
                            onclick="showSuspendDialog({{ $user->id }}, {{ json_encode($user->name) }})">
                            停用
                        </x-button.red-short>
                    @endif
                </td>
            </tr>
        @endforeach
    </x-tbody>
</x-table.gray-200>
