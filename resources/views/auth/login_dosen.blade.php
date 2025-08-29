<x-guest-layout>
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Login Dosen</h2>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('dosen.login.store') }}">
        @csrf

        <!-- Login / Username Dosen -->
        <div>
            <x-input-label for="Login" :value="__('ID Dosen (Login)')" />
            <x-text-input id="Login" class="block mt-1 w-full" type="text" name="Login" :value="old('Login')" required autofocus />
            <x-input-error :messages="$errors->get('Login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
