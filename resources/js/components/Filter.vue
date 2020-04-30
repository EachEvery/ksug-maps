<template>
    <div class="leading-tight text-left px-5 h-full flex flex-col relative">
        <div class="flex items-center mb-5 mt-5 md:mt-0">
            <h1 class="font-display text-lg md:text-3xl font-black uppercase">
                FILTER LOCATIONS
            </h1>
            <clickable
                class="uppercase font-mono ml-3"
                @click.stop="clearAll"
                v-if="validFilters.length || filters.length > 1"
                >Reset</clickable
            >
        </div>

        <div class="flex mb-4 relative" v-for="(filter, i) in filters" :key="i">
            <selectbox :options="days" v-model="filter.day" class="w-32"
                >Day</selectbox
            >

            <selectbox
                :options="roles"
                v-model="filter.role"
                class="flex-grow ml-4"
                >Role</selectbox
            >

            <div
                class="ml-4 w-8 flex items-center absolute right-0 top-0 -mt-5 -mr-4 md:mr-0 md:mt-0 md:static"
                style="transform: scale(.9)"
            >
                <clickable
                    v-if="filters.length !== 1 && i !== filters.length - 1"
                    class="w-8 h-8 rounded-full bg-white shadow flex items-center justify-center"
                    @click.stop="removeFilter(i)"
                >
                    <plus-icon
                        class="w-4 h-4"
                        style="transform: rotate(45deg); color: rgba(0,0,0,.6)"
                    />
                </clickable>

                <clickable
                    v-if="i === filters.length - 1"
                    @click.stop="addFilter"
                    :disabled="filters.length === 4"
                    :class="{ 'opacity-25': filters.length === 4 }"
                    class="w-8 h-8 rounded-full bg-black flex items-center justify-center"
                >
                    <plus-icon class="w-4 h-4 text-white" />
                </clickable>
            </div>
        </div>
    </div>
</template>
<script>
import clickable from "./Clickable";
import filterButton from "./FilterButton";
import selectbox from "./SelectBox";
import plusIcon from "./PlusIcon";
import closeIcon from "./CloseIcon";
import { getStoryColor } from "../functions/ksug";
import { mapGetters, mapMutations, mapState } from "vuex";

let filterStub = {
    role: undefined,
    day: undefined
};

export default {
    components: {
        clickable,
        filterButton,
        selectbox,
        plusIcon,
        closeIcon
    },

    data() {
        return {
            activeFilter: "day",
            activeCollection: "days",
            state: "default",
            filters: [...this.$store.state.filters]
        };
    },

    computed: {
        ...mapState(["stories"]),
        ...mapGetters(["roles", "names", "days", "validFilters"]),
        filterItems({ activeCollection }) {
            let items = this[activeCollection].map(item => {
                return item.trim();
            });

            return [...new Set(items)].sort();
        }
    },

    watch: {
        filters: {
            deep: true,
            handler(filters) {
                this.setFilters(filters);
            }
        }
    },
    methods: {
        ...mapMutations(["setFilters", "clearFilters"]),

        removeFilter(i) {
            this.filters.splice(i, 1);
        },

        clearAll() {
            this.filters = [{ ...filterStub }];
        },

        addFilter() {
            this.filters.push({ ...filterStub });
        },

        itemSelected(item) {
            return (
                this.filters.find(
                    f => f.key === this.activeFilter && f.value === item
                ) !== undefined
            );
        },
        setActiveFilter(e, filter, collection) {
            this.activeFilter = filter;
            this.activeCollection = collection;
        }
    }
};
</script>
