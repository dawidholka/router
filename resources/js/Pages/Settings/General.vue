<template>
    <SettingsLayout>
        <div class="card">
            <h5>{{ $t('settings.general') }}</h5>
            <div class="field">
                <label for="force_photo_upload">
                    Wymuszanie wgrania zdjęcia przed zmianą statusu dostarczenia
                </label>
                <SelectButton
                    id="force_photo_upload"
                    :options="options"
                    option-label="label"
                    option-value="value"
                    v-model="form.force_photo_upload"
                />
                <small v-if="form.errors.force_photo_upload" class="p-invalid">
                    {{ form.errors.force_photo_upload }}
                </small>
            </div>
            <div class="field">
                <label for="google_maps_api_key">
                    Google Maps Klucz API
                </label>
                <InputText
                    id="google_maps_api_key"
                    class="w-full"
                    v-model="form.google_maps_api_key"
                    :class="{'p-invalid': form.errors.google_maps_api_key}"
                />
                <small v-if="form.errors.google_maps_api_key" class="p-invalid">
                    {{ form.errors.google_maps_api_key }}
                </small>
            </div>
            <div class="field">
                <label for="routexl_username">
                    RouteXL Login
                </label>
                <InputText
                    id="routexl_username"
                    class="w-full"
                    v-model="form.routexl_username"
                    :class="{'p-invalid': form.errors.routexl_username}"
                />
                <small v-if="form.errors.routexl_username" class="p-invalid">
                    {{ form.errors.routexl_username }}
                </small>
            </div>
            <div class="field">
                <label for="routexl_password">
                    RouteXL Password
                </label>
                <InputText
                    id="routexl_password"
                    class="w-full"
                    v-model="form.routexl_password"
                    :class="{'p-invalid': form.errors.routexl_password}"
                />
                <small v-if="form.errors.routexl_password" class="p-invalid">
                    {{ form.errors.routexl_password }}
                </small>
            </div>
            <div class="field">
                <label for="company_lat">
                    Szerokość geograficzna siedziby
                </label>
                <InputText
                    id="company_lat"
                    class="w-full"
                    v-model="form.company_lat"
                    :class="{'p-invalid': form.errors.company_lat}"
                />
                <small v-if="form.errors.company_lat" class="p-invalid">
                    {{ form.errors.company_lat }}
                </small>
            </div>
            <div class="field">
                <label for="company_lng">
                    Długość geograficzna siedziby
                </label>
                <InputText
                    id="company_lng"
                    class="w-full"
                    v-model="form.company_lng"
                    :class="{'p-invalid': form.errors.company_lng}"
                />
                <small v-if="form.errors.company_lng" class="p-invalid">
                    {{ form.errors.company_lng }}
                </small>
            </div>
            <div class="field">
                <label for="bulk-delete">
                    Wyczyść dane aplikacji
                </label>
                <div class="flex flex-row">
                    <Dropdown
                        id="google_maps_api_key"
                        class="w-full"
                        :options="bulkDeleteOptions"
                        option-value="value"
                        option-label="label"
                        v-model="bulkDeleteForm.option"
                        :class="{'p-invalid': bulkDeleteForm.errors.option}"
                    />
                    <Button
                        label="Wyczyść"
                        class="ml-2"
                        :loading="bulkDeleteForm.processing"
                        @click="bulkDelete"
                    />
                </div>
            </div>
        </div>
        <div class="flex justify-content-end mt-3">
            <Button
                label="Zapisz"
                class="p-button-lg"
                :loading="form.processing"
                @click="submit"
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

export default {
    name: "General",
    components: {
        SettingsLayout,
        SelectButton,
        InputText,
        Button,
        Dropdown
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
                google_maps_api_key: this.settings.google_maps_api_key,
                force_photo_upload: this.settings.force_photo_upload,
                routexl_username: this.settings.routexl_username,
                routexl_password: this.settings.routexl_password,
                company_lat: this.settings.company_lat,
                company_lng: this.settings.company_lng,
            }),
            bulkDeleteForm: this.$inertia.form({
                option: null
            }),
            bulkDeleteOptions: [
                {value: 'all', label: 'Wszystkie'},
                {value: 'last-hour', label: 'Z ostatniej godziny'},
                {value: 'older-then-30-days', label: 'Starsze niż 30 dni'},
                {value: 'older-then-90-days', label: 'Starsze niż 90 dni'},
            ],
            options: [
                {value: true, label: 'Tak'},
                {value: false, label: 'Nie'},
            ]
        }
    },
    methods: {
        submit() {
            this.form.put(this.route('settings.general.update'), {
                onSuccess: () => {
                    this.flashSuccess('Zapisano ustawienia.');
                }
            })
        },
        bulkDelete() {
            this.bulkDeleteForm.post(this.route('bulk-destroy'), {
                onSuccess: () => {
                    this.flashSuccess('Wyczyszczono aplikacje.');
                }
            })

        }
    }
}
</script>

<style scoped>

</style>
