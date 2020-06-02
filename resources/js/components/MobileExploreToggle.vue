<template>
    <div
        class="flex justify-between items-center cursor-pointer md:hidden px-8"
        v-on="$listeners"
    >
        <slot />
    </div>
</template>
