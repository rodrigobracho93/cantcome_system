<x-app-layout>
    <x-slot name="header">Perfil</x-slot>

    <div class="max-w-3xl space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            @include('profile.partials.update-password-form')
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
