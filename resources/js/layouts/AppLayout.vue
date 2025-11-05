<template>
    <div
        class="h-screen w-full overflow-y-auto bg-[url('/assets/background.jpg')] bg-cover bg-center bg-no-repeat"
    >
        <TopBar />

        <div class="overflow-x-hidden pb-20">
            <slot />
        </div>

        <BottomBar />
    </div>
</template>

<script lang="ts" setup>
import { initNotyf, useNotyf } from '@/composables/useNotyf';
import { usePage } from '@inertiajs/vue3';
import { initFlowbite } from 'flowbite';
import { nextTick, onMounted } from 'vue';
import BottomBar from './app/BottomBar.vue';
import TopBar from './app/TopBar.vue';

const notyf = useNotyf();
const page = usePage();

if (page.props.notyf) {
    if (page.props.notyf) {
        notyf[page.props.notyf.type](page.props.notyf.message);
    }
}

onMounted(() => {
    nextTick(() => {
        initFlowbite();
        initNotyf();
    });
});
</script>
