<template>
    <settings-layout>
        <div class="card">
            <h5>{{ $t('settings.osrmSettings') }}</h5>
            <div class="field">
                <label for="url">
                    {{ $t('settings.osrmUrl') }}
                </label>
                <InputText
                    id="url"
                    class="w-full"
                    v-model="form.url"
                    :class="{'p-invalid': form.errors.url}"
                />
                <small v-if="form.errors.url" class="p-invalid">
                    {{ form.errors.url }}
                </small>
            </div>
            <div class="field">
                <label for="is_cloud_run">
                    {{ $t('settings.osrmCloudRun') }}
                </label>
                <SelectButton
                    id="is_cloud_run"
                    :options="options"
                    option-label="label"
                    option-value="value"
                    v-model="form.is_cloud_run"
                />
                <small v-if="form.errors.is_cloud_run" class="p-invalid">
                    {{ form.errors.is_cloud_run }}
                </small>
            </div>
            <div class="field">
                <label for="google_json_key">
                    {{ $t('settings.osrmJsonKey') }}
                </label>
                <Textarea
                    id="google_json_key"
                    v-model="form.google_json_key"
                    class="w-full"
                    rows="20"
                    :class="{'p-invalid': form.errors.google_json_key}"
                />
                <small v-if="form.errors.google_json_key" class="p-invalid">
                    {{ form.errors.google_json_key }}
                </small>
            </div>
        </div>
        <div class="flex justify-content-end mt-3">
            <Button
                :label="$t('common.save')"
                class="p-button-lg"
                :loading="form.processing"
                @click="submit"
            />
        </div>
    </settings-layout>
</template>

<script>
import SettingsLayout from "../../Components/Settings/SettingsLayout";
import SelectButton from "primevue/selectbutton";
import InputText from "primevue/inputtext";
import FlashMessage from "../../Services/FlashMessage";
import Button from "primevue/button";
import Textarea from "primevue/textarea";

export default {
    name: "OSRM",
    components: {
        SettingsLayout,
        SelectButton,
        InputText,
        Button,
        Textarea
    },
    mixins: [FlashMessage],
    props: {
        settings: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            form: this.$inertia.form({
                url: this.settings.url,
                is_cloud_run: this.settings.is_cloud_run,
                google_json_key: this.settings.google_json_key,
            }),
            options: [
                {value: true, label: this.$t('common.yes')},
                {value: false, label: this.$t('common.no')},
            ]
        }
    },
    methods: {
        submit() {
            this.form.put(this.route('settings.osrm.update'), {
                onSuccess: () => {
                    this.flashSuccess(this.$t('settings.settingsSaved'));
                }
            })
        },
    }
}
</script>

<style scoped>

</style>
