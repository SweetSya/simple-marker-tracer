<template>
    <AppLayout>
        <div class="p-4">
            <div
                class="mb-4 rounded-xl border border-primary-200 bg-primary-50 p-4 shadow-sm"
            >
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="mt-1 text-gray-600">
                    Welcome to SMT (Simple Mapping Tracer) Dashboard.
                </p>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import DataTablesCore from 'datatables.net-dt';
import 'datatables.net-responsive-dt';
import DataTable from 'datatables.net-vue3';

defineOptions({
    components: {
        AppLayout,
    },
});

DataTable.use(DataTablesCore);
</script>
