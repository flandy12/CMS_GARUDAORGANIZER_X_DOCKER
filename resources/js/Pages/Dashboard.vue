<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';

defineProps({
    modules: { type: Array, default: () => [] },
    summary: { type: Object, default: () => ({}) },
});
const user = usePage().props.auth.user;
const colorClasses = {
    indigo: 'bg-indigo-50 text-indigo-700 ring-indigo-100', sky: 'bg-sky-50 text-sky-700 ring-sky-100',
    amber: 'bg-amber-50 text-amber-700 ring-amber-100', emerald: 'bg-emerald-50 text-emerald-700 ring-emerald-100', rose: 'bg-rose-50 text-rose-700 ring-rose-100',
};
const formatDate = (value) => value ? new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).format(new Date(value)) : 'Belum ada perubahan';
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="px-4 py-8 sm:px-6 lg:px-8 lg:py-10"><div class="mx-auto max-w-7xl">
            <section class="relative overflow-hidden rounded-3xl bg-slate-950 px-6 py-8 text-white shadow-xl shadow-slate-200 sm:px-10 sm:py-10">
                <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full bg-indigo-500/20 blur-3xl" />
                <div class="relative flex flex-col justify-between gap-8 md:flex-row md:items-end">
                    <div><span class="inline-flex rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-indigo-200 ring-1 ring-white/10">Pusat pengelolaan konten</span><h2 class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl">Selamat datang, {{ user.name.split(' ')[0] }}.</h2><p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300 sm:text-base">Kelola seluruh konten website Garuda dari satu tempat. Pilih modul di bawah untuk mulai memperbarui informasi.</p></div>
                    <Link v-if="modules.length" :href="route(modules[0].route)" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-slate-900 shadow-lg transition hover:-translate-y-0.5 hover:bg-indigo-50">Kelola konten <span>→</span></Link>
                </div>
            </section>

            <section class="mt-6 grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm font-medium text-slate-500">Modul dapat diakses</p><p class="mt-2 text-3xl font-bold text-slate-900">{{ summary.accessible_modules || 0 }}</p></div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm font-medium text-slate-500">Konten aktif</p><p class="mt-2 text-3xl font-bold text-slate-900">{{ summary.active_content || 0 }}</p></div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm font-medium text-slate-500">Akses Anda</p><div class="mt-3 flex flex-wrap gap-2"><span v-for="role in summary.roles" :key="role" class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold capitalize text-indigo-700 ring-1 ring-indigo-100">{{ role }}</span></div></div>
            </section>

            <section class="mt-10">
                <div class="mb-5"><h3 class="text-xl font-bold text-slate-900">Kelola konten</h3><p class="mt-1 text-sm text-slate-500">Modul yang tersedia sesuai hak akses akun Anda.</p></div>
                <div v-if="modules.length" class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                    <Link v-for="module in modules" :key="module.route" :href="route(module.route)" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-100/50">
                        <div class="flex items-start justify-between gap-4"><span class="flex h-12 w-12 items-center justify-center rounded-xl ring-1" :class="colorClasses[module.color]"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5h16v14H4zM4 9h16M8 5v4" /></svg></span><span class="rounded-full bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ module.active_count === null ? 'N/A' : `${module.active_count} aktif` }}</span></div>
                        <h4 class="mt-5 text-lg font-bold text-slate-900 group-hover:text-indigo-700">{{ module.name }}</h4><p class="mt-2 min-h-[40px] text-sm leading-5 text-slate-500">{{ module.description }}</p>
                        <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4"><span class="text-xs text-slate-400">{{ formatDate(module.updated_at) }}</span><span class="text-sm font-bold text-indigo-600">Buka →</span></div>
                    </Link>
                </div>
                <div v-else class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center"><p class="font-semibold text-slate-700">Belum ada modul yang dapat diakses.</p><p class="mt-1 text-sm text-slate-500">Hubungi administrator untuk menambahkan permission akun Anda.</p></div>
            </section>
        </div></div>
    </AppLayout>
</template>
