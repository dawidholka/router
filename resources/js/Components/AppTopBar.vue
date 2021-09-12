<template>
    <div class="layout-topbar">
        <button class="p-link layout-menu-button" @click="onMenuToggle">
            <span class="pi pi-bars"></span>
        </button>
        <div class="layout-topbar-icons">
            <button class="p-link" @click="$inertia.get(route('profile.show'))">
                <span class="layout-topbar-item-text">
                    User
                </span>
                <span class="layout-topbar-icon pi pi-user"></span>
            </button>
        </div>
    </div>
</template>

<script>
import InputText from 'primevue/inputtext';
import Button from "primevue/button";

export default {
    name: 'AppTopBar',
    components: {
        InputText,
        Button
    },
    emits: ['menu-toggle'],
    data() {
        return {
            searchForm: this.$inertia.form({
                query: null
            })
        };
    },
    methods: {
        onMenuToggle(event) {
            this.$emit('menu-toggle', event);
        },
        search() {
            this.searchForm.get(this.route('search'));
        }
    }
};
</script>

<style scoped>
.layout-topbar-search-icon{
    cursor: pointer;
}

.layout-topbar .layout-menu-button,
.layout-topbar .layout-topbar-icons button,
.layout-topbar .layout-menu-button:hover,
.layout-topbar .layout-topbar-icons button:hover,
.layout-topbar .layout-menu-button:focus,
.layout-topbar .layout-topbar-icons button:focus {
    background: transparent;
    border: none;
    outline: none;
    box-shadow: none;
}
</style>
