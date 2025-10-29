<template>
    <TopBar />

    <div class="overflow-x-hidden overflow-y-auto pb-20">
        <slot />
    </div>

    <BottomBar />
</template>

<script lang="ts" setup>
import { initFlowbite } from 'flowbite';
import { nextTick, onMounted } from 'vue';
import BottomBar from './app/BottomBar.vue';
import TopBar from './app/TopBar.vue';

onMounted(() => {
    nextTick(() => {
        initFlowbite();
    });
});
</script>
