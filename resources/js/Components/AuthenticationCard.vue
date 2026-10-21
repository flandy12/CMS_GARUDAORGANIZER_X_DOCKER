<template>
    <div class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden bg-gradient-to-br from-blue-950 via-blue-800 to-sky-600 px-4 py-8">
        <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-sky-300/20 blur-3xl" />
        <div class="absolute -bottom-32 -left-20 h-96 w-96 rounded-full bg-blue-300/20 blur-3xl" />
        <div class="relative flex w-full max-w-md flex-col items-center">
            <div class="flex w-48 items-center justify-center rounded-2xl bg-white px-6 py-3 shadow-xl shadow-blue-950/20">
            <slot name="logo" />
            </div>

            <div class="mt-6 w-full overflow-hidden rounded-2xl border border-white/60 bg-white p-6 shadow-2xl shadow-blue-950/25 sm:p-8">
                <slot />
            </div>
            <p class="mt-6 text-center text-xs text-blue-100">Garuda Organizer Content Management System</p>
        </div>
    </div>
</template>
