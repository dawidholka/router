<template>
    <div class="speeddial-circle-demo">
        <SpeedDial
            :model="speedDialMenu"
            :radius="120"
            direction="up-left"
            type="quarter-circle"
            buttonClass="p-button-success"
            :tooltipOptions="{position: 'left'}"
            showIcon="pi pi-pencil" hideIcon="pi pi-times"
            :hideOnClickOutside="false"
        />
    </div>
</template>

<script>
import SpeedDial from "primevue/speeddial";

export default {
    name: "DrawingToolbar",
    components: {
        SpeedDial
    },
    data() {
        return {
            speedDialMenu: [
                {
                    label: this.$t('zones.addPolygon'),
                    icon: 'pi pi-fw pi-plus',
                    command: () => {
                        this.$emit('drawingmode_changed', 'polygon');
                    }
                },
                {
                    label: this.$t('common.deleteSelected'),
                    icon: 'pi pi-fw pi-trash',
                    command: () => {
                        this.$emit('delete_selection');
                    }
                },
                {
                    label: this.$t('common.edit'),
                    icon: 'pi pi-fw pi-pencil',
                    command: () => {
                        console.log('edit not supported yet');
                    }
                },
                {
                    label: this.$t('common.save'),
                    icon: 'pi pi-fw pi-save',
                    command: () => {
                        this.$emit('save');
                    }
                }
            ],
        }
    }
}
</script>

<style lang="scss" scoped>
::v-deep(.speeddial-circle-demo) {
    .p-speeddial-circle {
        top: calc(50% - 2rem);
        left: calc(50% - 2rem);
    }

    .p-speeddial-semi-circle {
        &.p-speeddial-direction-up {
            left: calc(50% - 2rem);
            bottom: 0;
        }

        &.p-speeddial-direction-down {
            left: calc(50% - 2rem);
            top: 0;
        }

        &.p-speeddial-direction-left {
            right: 0;
            top: calc(50% - 2rem);
        }

        &.p-speeddial-direction-right {
            left: 0;
            top: calc(50% - 2rem);
        }
    }

    .p-speeddial-quarter-circle {
        &.p-speeddial-direction-up-left {
            position: fixed;
            right: 50px;
            bottom: 50px;
        }

        &.p-speeddial-direction-up-right {
            left: 0;
            bottom: 0;
        }

        &.p-speeddial-direction-down-left {
            right: 0;
            top: 0;
        }

        &.p-speeddial-direction-down-right {
            left: 0;
            top: 0;
        }
    }
}
</style>
