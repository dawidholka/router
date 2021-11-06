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
                    label: this.$t('settings.general'),
                    icon: 'pi pi-cog',
                    command: () => {
                        this.$inertia.get(this.route('settings.general'))
                    }
                },
                {
                    label: this.$t('settings.import'),
                    icon: 'pi pi-file-excel',
                    command: () => {
                        this.$inertia.get(this.route('settings.import'))
                    }
                },
                {
                    label: this.$t('settings.updates'),
                    icon: 'pi pi-download',
                    command: () => {
                        this.$inertia.get(this.route('settings.update'))
                    }
                },
                {
                    label: 'OSRM',
                    icon: 'pi pi-globe',
                    command: () => {
                        this.$inertia.get(this.route('settings.osrm'));
                    }
                }
            ]
        };
    }
};
</script>

<style scoped>

</style>
