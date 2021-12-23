<template>
    <SettingsLayout>
        <div class="card">
            <h5>{{ $t('settings.updates') }}</h5>

            <p>{{ $t('settings.currentVersion') }}: {{ currentVersion }}</p>

            <div v-if="newVersion !== null && currentVersion !== newVersion">
                <p>{{ $t('settings.currentVersion') }}: {{ newVersion }}</p>
                <p>{{ $t('settings.newVersionInfo') }}</p>
            </div>

            <Button
                v-if="newVersion !== null && currentVersion !== newVersion"
                class="p-button-sm mt-3"
                :label="$t('settings.installUpdate')"
                :loading="installingUpdate"
                @click="installUpdate"
            />
            <Button
                v-else
                class="p-button-sm"
                :label="$t('settings.checkAvailableUpdates')"
                :loading="checkingVersion"
                @click="checkAvailableUpdates"
            />
        </div>

    </SettingsLayout>
</template>

<script>
import SettingsLayout from "../../Components/Settings/SettingsLayout";
import SelectButton from "primevue/selectbutton";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import Dropdown from "primevue/dropdown";
import FlashMessage from "../../Services/FlashMessage";
import InputNumber from "primevue/inputnumber";

export default {
    name: "General",
    components: {
        SettingsLayout,
        SelectButton,
        InputText,
        Button,
        Dropdown,
        InputNumber
    },
    mixins: [FlashMessage],
    props: {
        currentVersion: {
            type: String,
            required: true
        },
        newVersion: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            checkingVersion: false,
            installingUpdate: false,
        }
    },
    methods: {
        checkAvailableUpdates() {
            this.checkingVersion = true;
            this.$inertia.post(this.route('settings.update.check'), {}, {
                onSuccess: () => {
                    this.checkingVersion = false;
                }
            })
        },
        installUpdate() {
            this.installingUpdate = true;
            this.$inertia.post(this.route('settings.update.install'), {}, {
                onSuccess: () => {
                    this.installingUpdate = false;
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
