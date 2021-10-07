<template>
    <div class="card">
        <Menu
            class="w-full"
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
                    command: () => {
                        this.$inertia.get(this.route('settings.general'))
                    }
                },
                {
                    label: 'Ustawienia Importu',
                    icon: 'pi pi-file-excel',
                    command: () => {
                        this.$inertia.get(this.route('settings.import'))
                    }
                },
            ]
        };
    }
};
</script>

<style scoped>

</style>
