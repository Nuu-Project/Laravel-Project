@props(['users'])

<x-table.gray-200>
    <x-thead.roles />
    <x-tbody>
        @foreach ($users as $user)
            @if ($user->hasRole('admin'))
                <tr class="hover:bg-gray-50">
                    <td
                        class="px-6 py-4 whitespace-normal sm:whitespace-nowrap text-center sm:text-left">
                        <input type="checkbox" name="selected_ids[]" value="{{ $user->id }}"
                            class="role-checkbox form-checkbox h-4 w-4 text-blue-600"
                            data-role="admin">
                    </td>
                    <x-td>
                        {{ $user->name }}
                    </x-td>
                    <x-td>
                        {{ $user->email }}
                    </x-td>
                </tr>
            @endif
        @endforeach
    </x-tbody>
</x-table.gray-200> 