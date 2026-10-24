<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import ColorLogo from '@/Components/Logo/ColorLogo.vue';
import { checkRolePermission } from '@/Library/helpers.js';

defineProps({ title: String });

const page = usePage();
const mobileMenuOpen = ref(false);
const rolePermissions = computed(() => page.props.role_permissions || {});
const roles = computed(() => Object.keys(rolePermissions.value));
const navigation = computed(() => [
    { label: 'Dashboard', route: 'dashboard', permission: null, icon: 'dashboard' },
    { label: 'Beranda', route: 'home', permission: 'view home', icon: 'home' },
    { label: 'Layanan', route: 'service', permission: 'view service', icon: 'service' },
    { label: 'Proyek', route: 'project', permission: 'view project', icon: 'project' },
    { label: 'Tentang Kami', route: 'about_us', permission: 'view about us', icon: 'about' },
    { label: 'Kontak', route: 'contact_us', permission: 'view contact us', icon: 'contact' },
].filter((item) => !item.permission || checkRolePermission(rolePermissions.value, [], [item.permission])));

const isActive = (routeName) => route().current(routeName) || route().current(`${routeName}.*`);
const logout = () => router.post(route('logout'));
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50/70 via-white to-sky-50/60">
        <Head :title="title" />
        <Banner />
        <div v-if="mobileMenuOpen" class="fixed inset-0 z-40 bg-slate-950/50 backdrop-blur-sm lg:hidden" @click="mobileMenuOpen = false" />

        <aside :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-hero shadow-2xl transition-transform duration-200 lg:translate-x-0">
            <div class="flex h-20 items-center justify-between border-b border-white/10 px-6">
                <Link :href="route('dashboard')" class="flex items-center gap-3" @click="mobileMenuOpen = false">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white p-2 shadow-lg"><ColorLogo class="h-full w-full" /></span>
                    <span><span class="block text-sm font-bold tracking-wide">GARUDA</span><span class="block text-xs text-slate-400">Content Management</span></span>
                </Link>
                <button class="rounded-lg p-2 text-slate-400 hover:bg-white/10 hover:text-white lg:hidden" aria-label="Tutup menu" @click="mobileMenuOpen = false">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-6">
                <p class="mb-3 px-3 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">Menu utama</p>
                <nav class="space-y-1.5">
                    <Link v-for="item in navigation" :key="item.route" :href="route(item.route)" @click="mobileMenuOpen = false"
                        class="group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition"
                        :class="isActive(item.route) ? 'bg-white text-blue-800 shadow-lg shadow-blue-950/20' : 'text-blue-100/80 hover:bg-white/10 hover:text-white'">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg" :class="isActive(item.route) ? 'bg-white/15' : 'bg-white/5 group-hover:bg-white/10'">
                            <svg v-if="item.icon === 'dashboard'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12 12 3l9 9v8a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-8Z" /></svg>
                            <svg v-else-if="item.icon === 'home'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5h16v14H4zM4 9h16M8 5v4" /></svg>
                            <svg v-else-if="item.icon === 'service'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3 4 7l8 4 8-4-8-4Zm-8 9 8 4 8-4M4 17l8 4 8-4" /></svg>
                            <svg v-else-if="item.icon === 'project'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7h18v13H3zM8 7V4h8v3" /></svg>
                            <svg v-else-if="item.icon === 'about'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0-10v6m0-10h.01" /></svg>
                            <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5h18v14H3zM3 7l9 7 9-7" /></svg>
                        </span>
                        {{ item.label }}
                    </Link>
                </nav>
            </div>

            <div class="border-t border-white/10 p-4">
                <div class="mb-3 flex items-center gap-3 rounded-xl bg-white/5 p-3">
                    <img :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name" class="h-10 w-10 rounded-full object-cover ring-2 ring-white/10">
                    <div class="min-w-0 flex-1"><p class="truncate text-sm font-semibold">{{ $page.props.auth.user.name }}</p><p class="truncate text-xs capitalize text-slate-400">{{ roles.join(', ') || 'User' }}</p></div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <Link :href="route('profile.show')" class="rounded-lg bg-white/5 px-3 py-2 text-center text-xs font-medium text-slate-300 hover:bg-white/10 hover:text-white">Profil</Link>
                    <button type="button" class="rounded-lg bg-white/5 px-3 py-2 text-xs font-medium text-slate-300 hover:bg-rose-500/20 hover:text-rose-200" @click="logout">Keluar</button>
                </div>
            </div>
        </aside>

        <div class="lg:pl-72">
            <header class="sticky top-0 z-30 border-b border-blue-100 bg-white/90 shadow-sm shadow-blue-100/30 backdrop-blur">
                <div class="flex h-20 items-center gap-4 px-4 sm:px-6 lg:px-8">
                    <button class="rounded-xl border border-slate-200 p-2.5 text-slate-600 hover:bg-slate-50 lg:hidden" aria-label="Buka menu" @click="mobileMenuOpen = true">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <div class="min-w-0 flex-1"><p class="text-xs font-medium uppercase tracking-wider text-blue-700">Garuda CMS</p><h1 class="truncate text-xl font-bold text-slate-900">{{ title }}</h1></div>
                    <div class="hidden items-center gap-3 sm:flex">
                        <div class="text-right"><p class="text-sm font-semibold text-slate-800">{{ $page.props.auth.user.name }}</p><p class="text-xs text-slate-500">{{ $page.props.auth.user.email }}</p></div>
                        <img :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name" class="h-10 w-10 rounded-full object-cover ring-2 ring-slate-100">
                    </div>
                </div>
            </header>
            <main><slot /></main>
        </div>
    </div>
</template>
