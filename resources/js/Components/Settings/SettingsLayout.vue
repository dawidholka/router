<template>
    <AppLayout>
        <div class="grid">
            <div class="p-md-3 col-12 md:col-3">
                <SettingsMenu/>
            </div>
            <div class="p-md-9 ol-12 md:col-9">
                <slot></slot>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import SettingsMenu from './SettingsMenu';
import AppLayout from '@/Layouts/AppLayout';

export default {
    name: 'SettingsLayout',
    components: {
        SettingsMenu,
        AppLayout,
    }
};
</script>
