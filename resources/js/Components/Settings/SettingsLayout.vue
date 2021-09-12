<template>
    <AppLayout>
        <div class="p-grid">
            <div class="p-md-3 p-col-12">
                <SettingsMenu/>
            </div>
            <div class="p-md-9 p-col-12">
                <slot></slot>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import SettingsMenu from '@/Components/Settings/SettingsMenu';
import AppLayout from '@/Layouts/AppLayout';

export default {
    name: 'SettingsLayout',
    components: {
        SettingsMenu,
        AppLayout,
    }
};
</script>
