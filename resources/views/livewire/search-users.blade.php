<div>

    <div class="mb-4">
        <form>
            <label class="sr-only" for="search">{{ __('common.search_users') }}</label>
            <input wire:model.debounce.500ms="search" class="w-full px-4 py-2 border rounded-lg" type="text"
                name="search" id="search" placeholder="{{ __('users.searchbox') }}" autofocus>
        </form>
    </div>

    <div class="overflow-x-auto border rounded-lg">

        <table class="min-w-full border-b border-gray-300">

            <thead>
                <tr>
                    <th class="px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
                        {{ __('common.name') }}</th>
                    <th class="px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
                        {{ __('common.email') }}</th>
                    <th class="px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
                        {{ __('common.status') }}</th>
                    <th class="px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
                        &nbsp;
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-300">
                @foreach ($users as $user)
                    <tr x-data class="hover:bg-blue-50" role="button"
                        @click="window.location = $el.querySelectorAll('a')[1].href">
                        <td class="px-6 py-3 text-sm">
                            <div class="font-bold">{{ $user->name }}</div>
                            <div class="text-gray-400">{{ $user->uniqueid }}</div>
                        </td>
                        <td class="px-6 py-3 text-sm"><a class="hover:underline text-blue-600"
                                href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                        <td class="px-6 py-3 text-sm">
                            <div>
                                <x-pils.user-status :user="$user" />
                            </div>
                            <div>
                                <x-pils.user-manager :user="$user" />
                            </div>
                            <div>
                                <x-pils.user-admin :user="$user" />
                            </div>
                        </td>
                        <td class="px-6 py-3 text-sm"><a class="hover:underline text-blue-600"
                                href="{{ route('users.show', $user) }}">{{ __('common.show') }}</a></td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot class="border-t border-gray-300">
                <tr>
                    <td colspan="4">{{ $users->links('livewire::tailwind') }}</td>
                </tr>
            </tfoot>

        </table>

    </div>

</div>
