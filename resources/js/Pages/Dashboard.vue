<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    orders: {
        type: Array,
        default: () => [],
    },
    supplies: {
        type: Array,
        default: () => [],
    },
});

const today = new Date();
const currentDay = today.toISOString().split("T")[0];
const currentMonth = today.getMonth();
const currentYear = today.getFullYear();

const currentMonthOrders = computed(() =>
    props.orders.filter((order) => {
        if (!order.created_at) return false;

        const orderDate = new Date(order.created_at);

        return (
            orderDate.getMonth() === currentMonth &&
            orderDate.getFullYear() === currentYear
        );
    }),
);

const pendingOrders = computed(
    () =>
        currentMonthOrders.value.filter((order) => order.status === "Pending")
            .length,
);

const ongoingOrders = computed(
    () =>
        currentMonthOrders.value.filter((order) => order.status === "Ongoing")
            .length,
);

const completedOrders = computed(
    () =>
        currentMonthOrders.value.filter((order) => order.status === "Done")
            .length,
);

const totalOrders = computed(() => currentMonthOrders.value.length);

const dailySales = computed(() =>
    props.orders
        .filter((order) => {
            if (!order.created_at) return false;
            return (
                new Date(order.created_at).toISOString().split("T")[0] ===
                currentDay
            );
        })
        .reduce((total, order) => total + Number(order.price || 0), 0),
);

const monthlySales = computed(() =>
    currentMonthOrders.value.reduce(
        (total, order) => total + Number(order.price || 0),
        0,
    ),
);

const monthlySalesData = computed(() => {
    const data = Array(12).fill(0);

    props.orders.forEach((order) => {
        if (!order.created_at) return;

        const date = new Date(order.created_at);

        if (date.getFullYear() === currentYear) {
            data[date.getMonth()] += Number(order.price || 0);
        }
    });

    return data;
});

const stats = computed(() => [
    {
        title: "Pending Orders",
        value: pendingOrders.value,
        color: "bg-yellow-500",
        textColor: "text-yellow-600",
        bgLight: "bg-yellow-50",
    },
    {
        title: "Ongoing Orders",
        value: ongoingOrders.value,
        color: "bg-blue-500",
        textColor: "text-blue-600",
        bgLight: "bg-blue-50",
    },
    {
        title: "Completed Orders",
        value: completedOrders.value,
        color: "bg-green-500",
        textColor: "text-green-600",
        bgLight: "bg-green-50",
    },
    {
        title: "Total Orders",
        value: totalOrders.value,
        color: "bg-purple-500",
        textColor: "text-purple-600",
        bgLight: "bg-purple-50",
    },
]);

const recentOrders = computed(() => props.orders.slice(0, 5));

const lowStocks = computed(() =>
    props.supplies.filter((supply) => supply.stock <= 3),
);

const upcomingDeadlines = computed(() => {
    const now = new Date();
    const twoDaysLater = new Date();
    twoDaysLater.setDate(now.getDate() + 2);

    return props.orders.filter((order) => {
        if (!order.deadline || order.status === "Done") return false;

        const deadline = new Date(order.deadline);
        return deadline >= now && deadline <= twoDaysLater;
    });
});

const statusClass = (status) => {
    if (status === "Pending") return "bg-yellow-100 text-yellow-700";
    if (status === "Ongoing") return "bg-blue-100 text-blue-700";
    if (status === "Done") return "bg-green-100 text-green-700";
    return "bg-gray-100 text-gray-700";
};

const formatDate = (date) => {
    if (!date) return "No date";
    return new Date(date).toLocaleDateString();
};

const formatPrice = (price) => `₱${Number(price || 0).toFixed(2)}`;

const monthLabels = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "May",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
];

const maxSales = computed(() => Math.max(...monthlySalesData.value, 1));

const getLinePoints = computed(() => {
    if (!monthlySalesData.value.length) return "";

    const width = 760;
    const height = 220;
    const paddingX = 10;
    const paddingY = 20;

    return monthlySalesData.value
        .map((sale, index) => {
            const x =
                paddingX +
                (index / (monthlySalesData.value.length - 1 || 1)) *
                    (width - paddingX * 2);

            const y =
                height -
                paddingY -
                (sale / maxSales.value) * (height - paddingY * 2);

            return `${x},${y}`;
        })
        .join(" ");
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <main
                    class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4"
                >
                    <div
                        v-for="stat in stats"
                        :key="stat.title"
                        class="rounded-2xl bg-white p-5 shadow transition hover:-translate-y-1 hover:shadow-lg"
                    >
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm text-gray-500">
                                    {{ stat.title }}
                                </p>
                                <h2
                                    class="mt-2 text-3xl font-bold"
                                    :class="stat.textColor"
                                >
                                    {{ stat.value }}
                                </h2>
                            </div>

                            <div class="rounded-xl p-3" :class="stat.bgLight">
                                <div
                                    class="h-3 w-3 rounded-full"
                                    :class="stat.color"
                                ></div>
                            </div>
                        </div>

                        <div class="mt-4 h-2 rounded-full bg-gray-200">
                            <div
                                class="h-2 w-2/3 rounded-full"
                                :class="stat.color"
                            ></div>
                        </div>
                    </div>

                    <div
                        class="col-span-1 rounded-2xl bg-white p-6 shadow md:col-span-2 xl:col-span-3"
                    >
                        <div class="mb-6 flex items-start justify-between">
                            <h2 class="text-lg font-semibold text-gray-800">
                                Sales Overview
                            </h2>

                            <div class="text-right">
                                <p class="text-sm text-gray-500">This Month</p>
                                <p class="text-2xl font-bold text-blue-600">
                                    {{ formatPrice(monthlySales) }}
                                </p>
                            </div>
                        </div>

                        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="rounded-2xl bg-green-50 p-5">
                                <p class="text-sm text-gray-500">Daily Sales</p>
                                <h3
                                    class="mt-2 text-2xl font-bold text-green-600"
                                >
                                    {{ formatPrice(dailySales) }}
                                </h3>
                            </div>

                            <div class="rounded-2xl bg-blue-50 p-5">
                                <p class="text-sm text-gray-500">
                                    Monthly Sales
                                </p>
                                <h3
                                    class="mt-2 text-2xl font-bold text-blue-600"
                                >
                                    {{ formatPrice(monthlySales) }}
                                </h3>
                            </div>
                        </div>

                        <div class="w-full">
                            <div class="h-72 w-full">
                                <svg
                                    viewBox="0 0 760 220"
                                    preserveAspectRatio="none"
                                    class="h-full w-full"
                                >
                                    <polyline
                                        fill="none"
                                        stroke="#3B82F6"
                                        stroke-width="4"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        :points="getLinePoints"
                                    />
                                </svg>
                            </div>

                            <div
                                class="mt-3 grid grid-cols-12 text-center text-xs text-gray-500"
                            >
                                <div v-for="label in monthLabels" :key="label">
                                    {{ label }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-white p-5 shadow">
                        <h2 class="mb-3 font-semibold">Alerts</h2>

                        <div v-if="upcomingDeadlines.length > 0">
                            <p class="mb-2 text-sm font-medium text-red-600">
                                ⚠ Upcoming Deadlines
                            </p>

                            <div class="max-h-48 overflow-y-auto pr-1">
                                <div
                                    v-for="order in upcomingDeadlines"
                                    :key="order.id"
                                    class="mb-2 rounded-lg bg-red-50 p-3"
                                >
                                    <p
                                        class="text-sm font-semibold text-gray-800"
                                    >
                                        {{ order.customer }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Deadline:
                                        {{ formatDate(order.deadline) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div v-if="lowStocks.length > 0">
                            <p
                                class="mt-3 mb-2 text-sm font-medium text-yellow-600"
                            >
                                ⚠ Low Stocks
                            </p>

                            <div class="max-h-48 overflow-y-auto pr-1">
                                <div
                                    v-for="item in lowStocks"
                                    :key="item.id"
                                    class="mb-2 rounded-lg bg-yellow-50 p-3"
                                >
                                    <p
                                        class="text-sm font-semibold text-gray-800"
                                    >
                                        {{ item.name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Stock: {{ item.stock }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <p
                            v-if="
                                upcomingDeadlines.length === 0 &&
                                lowStocks.length === 0
                            "
                            class="text-sm text-gray-400"
                        >
                            No alerts found
                        </p>
                    </div>

                    <div
                        class="col-span-1 overflow-hidden rounded-xl bg-white shadow md:col-span-2 xl:col-span-4"
                    >
                        <div class="p-6">
                            <h2 class="mb-4 font-semibold">Recent Orders</h2>

                            <div class="overflow-x-auto">
                                <table class="min-w-full border-collapse">
                                    <thead>
                                        <tr
                                            class="border-b text-left text-sm text-gray-500"
                                        >
                                            <th class="px-4 py-3">
                                                Date Created
                                            </th>
                                            <th class="px-4 py-3">Deadline</th>
                                            <th class="px-4 py-3">Customer</th>
                                            <th class="px-4 py-3">Price</th>
                                            <th class="px-4 py-3">Product</th>
                                            <th class="px-4 py-3">Qty</th>
                                            <th class="px-4 py-3">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="order in recentOrders"
                                            :key="order.id"
                                            class="border-b text-sm"
                                        >
                                            <td class="px-4 py-3">
                                                {{
                                                    formatDate(order.created_at)
                                                }}
                                            </td>
                                            <td class="px-4 py-3">
                                                {{
                                                    order.deadline
                                                        ? formatDate(
                                                              order.deadline,
                                                          )
                                                        : "No deadline"
                                                }}
                                            </td>
                                            <td class="px-4 py-3">
                                                {{ order.customer }}
                                            </td>
                                            <td
                                                class="px-4 py-3 font-medium text-green-600"
                                            >
                                                {{ formatPrice(order.price) }}
                                            </td>
                                            <td class="px-4 py-3">
                                                {{ order.product }}
                                            </td>
                                            <td class="px-4 py-3">
                                                {{ order.qty }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span
                                                    class="rounded-full px-3 py-1 text-xs font-medium"
                                                    :class="
                                                        statusClass(
                                                            order.status,
                                                        )
                                                    "
                                                >
                                                    {{ order.status }}
                                                </span>
                                            </td>
                                        </tr>

                                        <tr v-if="recentOrders.length === 0">
                                            <td
                                                colspan="7"
                                                class="px-4 py-6 text-center text-gray-500"
                                            >
                                                No orders found.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
