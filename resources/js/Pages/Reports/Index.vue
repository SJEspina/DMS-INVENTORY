<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    summary: {
        type: Object,
        default: () => ({
            todaySale: 0,
            todayTotalOrders: 0,
            monthlySales: 0,
            monthlyExpense: 0,
        }),
    },
    orderStatistics: {
        type: Array,
        default: () => [],
    },
    salesOverview: {
        type: Array,
        default: () => [],
    },
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP",
        minimumFractionDigits: 2,
    }).format(value || 0);
};

const maxOrders = Math.max(
    ...props.orderStatistics.map((item) => item.orders),
    1,
);
const maxSales = Math.max(...props.salesOverview.map((item) => item.sales), 1);

const getLinePoints = () => {
    if (!props.salesOverview.length) return "";

    return props.salesOverview
        .map((item, index) => {
            const x =
                (index / (props.salesOverview.length - 1 || 1)) * 660 + 20;
            const y = 220 - (item.sales / maxSales) * 180;
            return `${x},${y}`;
        })
        .join(" ");
};
</script>

<template>
    <Head title="Reports" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Reports Overview
                </h2>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <!-- Summary Cards -->
                <div
                    class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4"
                >
                    <div class="rounded-2xl bg-white p-5 shadow">
                        <p class="text-sm text-gray-500">Today's Sale</p>
                        <h3 class="mt-2 text-2xl font-bold text-green-600">
                            {{ formatCurrency(summary.todaySale) }}
                        </h3>
                    </div>

                    <div class="rounded-2xl bg-white p-5 shadow">
                        <p class="text-sm text-gray-500">
                            Today's Total Orders
                        </p>
                        <h3 class="mt-2 text-2xl font-bold text-blue-600">
                            {{ summary.todayTotalOrders }}
                        </h3>
                    </div>

                    <div class="rounded-2xl bg-white p-5 shadow">
                        <p class="text-sm text-gray-500">Monthly Sales</p>
                        <h3 class="mt-2 text-2xl font-bold text-amber-500">
                            {{ formatCurrency(summary.monthlySales) }}
                        </h3>
                    </div>

                    <div class="rounded-2xl bg-white p-5 shadow">
                        <p class="text-sm text-gray-500">Monthly Expense</p>
                        <h3 class="mt-2 text-2xl font-bold text-red-500">
                            {{ formatCurrency(summary.monthlyExpense) }}
                        </h3>
                    </div>
                </div>

                <!-- Charts -->
                <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                    <!-- Order Statistics -->
                    <div class="rounded-2xl bg-white p-6 shadow">
                        <h3 class="mb-6 text-lg font-semibold text-gray-800">
                            Order Statistics
                        </h3>

                        <div class="flex h-72 items-end justify-between gap-3">
                            <div
                                v-for="item in orderStatistics"
                                :key="item.label"
                                class="flex h-full flex-1 flex-col items-center justify-end"
                            >
                                <div
                                    class="flex h-full w-full items-end justify-center"
                                >
                                    <div
                                        class="w-2 rounded-t-xl bg-blue-500"
                                        :style="{
                                            height: `${(item.orders / maxOrders) * 100}%`,
                                        }"
                                    ></div>
                                </div>
                                <p class="mt-3 text-xs text-gray-500">
                                    {{ item.label }}
                                </p>
                                <p class="text-xs font-medium text-gray-700">
                                    {{ item.orders }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Overview -->
                    <div class="rounded-2xl bg-white p-6 shadow">
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">
                                Sales Overview
                            </h3>
                            <p class="mt-1 text-2xl font-bold text-green-600">
                                {{ formatCurrency(summary.monthlySales) }}
                            </p>
                        </div>

                        <div class="relative h-72 w-full">
                            <svg viewBox="0 0 700 250" class="h-full w-full">
                                <polyline
                                    fill="none"
                                    stroke="#22c55e"
                                    stroke-width="4"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    :points="getLinePoints()"
                                />
                            </svg>

                            <div
                                class="mt-2 grid grid-cols-7 text-center text-xs text-gray-500"
                            >
                                <div
                                    v-for="item in salesOverview"
                                    :key="item.label"
                                >
                                    {{ item.label }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
