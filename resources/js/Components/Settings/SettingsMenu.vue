<template>
    <div class="card">
        <Menu
            :model="menuItems"
        />
    </div>
</template>

<script>
import Menu from 'primevue/menu';

export default {
    name: 'SettingsMenu',
    components: {
        Menu
    },
    data() {
        return {
            menuItems: [
                {
                    label: 'Ustawienia Główne',
                    icon: 'pi pi-cog',
                },
            ]
        };
    }
};
</script>

<style scoped>

</style>
