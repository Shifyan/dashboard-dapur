<x-filament-panels::page>
<x-filament::card>
    <div style="display: flex; flex-direction: column; gap: 1rem;">

        {{-- Card: Informasi Akun --}}
        <div style="
            border-radius: 0.75rem;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        " class="bg-white dark:bg-gray-900 dark:border-white/10">
            <div style="padding: 1rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.06);" class="dark:border-white/10">
                <p style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7280;">
                    Informasi Akun
                </p>
            </div>

            {{-- Username Row --}}
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 4rem; padding: 1.1rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);" class="dark:border-white/10">
                <div style="display: flex; align-items: center; gap: 0.875rem; flex: 1;">
                    <div style="
                        width: 2.5rem; height: 2.5rem; border-radius: 0.5rem;
                        background: #eff6ff;
                        display: flex; align-items: center; justify-content: center;
                        flex-shrink: 0;
                    ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#3b82f6" style="width: 1.2rem; height: 1.2rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                    <div>
                        <p style="font-size: 0.78rem; color: #9ca3af; margin-bottom: 0.15rem;">Username</p>
                        <p style="font-size: 0.95rem; font-weight: 600;" class="text-gray-900 dark:text-white">
                            {{ auth()->user()->username }}
                        </p>
                    </div>
                </div>
                <div style="flex-shrink: 0;">
                    {{ ($this->editUsernameAction)(['username' => auth()->user()->username]) }}
                </div>
            </div>

            {{-- Email Row --}}
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 4rem; padding: 1.1rem 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.875rem; flex: 1;">
                    <div style="
                        width: 2.5rem; height: 2.5rem; border-radius: 0.5rem;
                        background: #f0fdf4;
                        display: flex; align-items: center; justify-content: center;
                        flex-shrink: 0;
                    ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#22c55e" style="width: 1.2rem; height: 1.2rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                    </div>
                    <div>
                        <p style="font-size: 0.78rem; color: #9ca3af; margin-bottom: 0.15rem;">Email</p>
                        <p style="font-size: 0.95rem; font-weight: 600;" class="text-gray-900 dark:text-white">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
                <div style="flex-shrink: 0;">
                    {{ ($this->editEmailAction)(['email' => auth()->user()->email]) }}
                </div>
            </div>
        </div>

        {{-- Card: Keamanan --}}
        <div style="
            border-radius: 0.75rem;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        " class="bg-white dark:bg-gray-900 dark:border-white/10">
            <div style="padding: 1rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.06);" class="dark:border-white/10">
                <p style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7280;">
                    Keamanan
                </p>
            </div>

            {{-- Password Row --}}
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 4rem; padding: 1.1rem 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.875rem; flex: 1;">
                    <div style="
                        width: 2.5rem; height: 2.5rem; border-radius: 0.5rem;
                        background: #fff7ed;
                        display: flex; align-items: center; justify-content: center;
                        flex-shrink: 0;
                    ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f97316" style="width: 1.2rem; height: 1.2rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    </div>
                    <div>
                        <p style="font-size: 0.78rem; color: #9ca3af; margin-bottom: 0.15rem;">Password</p>
                        <p style="font-size: 0.95rem; font-weight: 600; letter-spacing: 0.15em;" class="text-gray-900 dark:text-white">
                            ••••••••
                        </p>
                    </div>
                </div>
                <div style="flex-shrink: 0;">
                    {{ $this->editPasswordAction }}
                </div>
            </div>
        </div>

    </div>
</x-filament::card>

    <x-filament-actions::modals />
</x-filament-panels::page>
