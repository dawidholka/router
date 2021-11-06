<template>
    <SettingsLayout>
        <div class="card">
            <h5>{{ $t('settings.import') }}</h5>
            <div class="field">
                <label for="start_row">
                    {{ $t('settings.startRowIndex') }}
                </label>
                <InputNumber
                    id="start_row"
                    class="w-full"
                    v-model="form.start_row"
                    :class="{'p-invalid': form.errors.start_row}"
                />
                <small v-if="form.errors.start_row" class="p-invalid">
                    {{ form.errors.start_row }}
                </small>
            </div>
            <p>Podaj nazwy kolumny np. litery A, B, C takie jak w pliku .xlsx.</p>
            <div class="field">
                <label for="point_name">
                    {{ $t('settings.column') }} - {{ $t('common.pointName') }}
                </label>
                <InputText
                    id="point_name"
                    autocomplete="off"
                    class="w-full"
                    v-model="form.point_name"
                    :class="{'p-invalid': form.errors.point_name}"
                />
                <small v-if="form.errors.point_name" class="p-invalid">
                    {{ form.errors.point_name }}
                </small>
            </div>
            <div class="field">
                <label for="point_street">
                    Kolumna - Ulica
                </label>
                <InputText
                    id="point_street"
                    autocomplete="off"
                    class="w-full"
                    v-model="form.point_street"
                    :class="{'p-invalid': form.errors.point_street}"
                />
                <small v-if="form.errors.point_street" class="p-invalid">
                    {{ form.errors.point_street }}
                </small>
            </div>
            <div class="field">
                <label for="point_building_number">
                    Kolumna - Numer budynku
                </label>
                <InputText
                    id="point_building_number"
                    autocomplete="off"
                    class="w-full"
                    v-model="form.point_building_number"
                    :class="{'p-invalid': form.errors.point_building_number}"
                />
                <small v-if="form.errors.point_building_number" class="p-invalid">
                    {{ form.errors.point_building_number }}
                </small>
            </div>
            <div class="field">
                <label for="point_apartment">
                    Kolumna - Numer mieszkania
                </label>
                <InputText
                    id="point_building_number"
                    autocomplete="off"
                    class="w-full"
                    v-model="form.point_apartment"
                    :class="{'p-invalid': form.errors.point_apartment}"
                />
                <small v-if="form.errors.point_apartment" class="p-invalid">
                    {{ form.errors.point_apartment }}
                </small>
            </div>
            <div class="field">
                <label for="point_city">
                    Kolumna - Miasto
                </label>
                <InputText
                    id="point_city"
                    autocomplete="off"
                    class="w-full"
                    v-model="form.point_city"
                    :class="{'p-invalid': form.errors.point_city}"
                />
                <small v-if="form.errors.point_city" class="p-invalid">
                    {{ form.errors.point_city }}
                </small>
            </div>
            <div class="field">
                <label for="point_postcode">
                    Kolumna - Kod pocztowy
                </label>
                <InputText
                    id="point_postcode"
                    autocomplete="off"
                    class="w-full"
                    v-model="form.point_postcode"
                    :class="{'p-invalid': form.errors.point_postcode}"
                />
                <small v-if="form.errors.point_postcode" class="p-invalid">
                    {{ form.errors.point_postcode }}
                </small>
            </div>
            <div class="field">
                <label for="point_intercom">
                    Kolumna - Kod do domofonu
                </label>
                <InputText
                    id="point_intercom"
                    autocomplete="off"
                    class="w-full"
                    v-model="form.point_intercom"
                    :class="{'p-invalid': form.errors.point_intercom}"
                />
                <small v-if="form.errors.point_intercom" class="p-invalid">
                    {{ form.errors.point_intercom }}
                </small>
            </div>
            <div class="field">
                <label for="point_phone">
                    Kolumna - Telefon
                </label>
                <InputText
                    id="point_phone"
                    autocomplete="off"
                    class="w-full"
                    v-model="form.point_phone"
                    :class="{'p-invalid': form.errors.point_phone}"
                />
                <small v-if="form.errors.point_phone" class="p-invalid">
                    {{ form.errors.point_phone }}
                </small>
            </div>
            <div class="field">
                <label for="point_note">
                    Kolumna - Notatka
                </label>
                <InputText
                    id="point_note"
                    autocomplete="off"
                    class="w-full"
                    v-model="form.point_note"
                    :class="{'p-invalid': form.errors.point_note}"
                />
                <small v-if="form.errors.point_note" class="p-invalid">
                    {{ form.errors.point_note }}
                </small>
            </div>
            <div class="field">
                <label for="waypoint_content">
                    Kolumna - Zawartość
                </label>
                <InputText
                    id="waypoint_content"
                    autocomplete="off"
                    class="w-full"
                    v-model="form.waypoint_content"
                    :class="{'p-invalid': form.errors.waypoint_content}"
                />
                <small v-if="form.errors.waypoint_content" class="p-invalid">
                    {{ form.errors.waypoint_content }}
                </small>
                <small v-else>
                    Możesz podać wiele kolumn oddzielonych spacją, zostaną one połączone.
                </small>
            </div>
            <div class="field">
                <label for="waypoint_quantity">
                    Kolumna - Ilość paczek
                </label>
                <InputText
                    id="waypoint_quantity"
                    autocomplete="off"
                    class="w-full"
                    v-model="form.waypoint_quantity"
                    :class="{'p-invalid': form.errors.waypoint_quantity}"
                />
                <small v-if="form.errors.waypoint_quantity" class="p-invalid">
                    {{ form.errors.waypoint_quantity }}
                </small>
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
        settings: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            form: this.$inertia.form({
                start_row: this.settings.start_row,
                point_name: this.settings.point_name,
                point_street: this.settings.point_street,
                point_building_number: this.settings.point_building_number,
                point_apartment: this.settings.point_apartment,
                point_city: this.settings.point_city,
                point_postcode: this.settings.point_postcode,
                point_intercom: this.settings.point_intercom,
                point_phone: this.settings.point_phone,
                point_note: this.settings.point_note,
                waypoint_content: this.settings.waypoint_content,
                waypoint_quantity: this.settings.waypoint_quantity,
            }),
            options: [
                {value: true, label: this.$t('common.yes')},
                {value: false, label: this.$t('common.no')},
            ]
        }
    },
    methods: {
        submit() {
            this.form.put(this.route('settings.import'), {
                onSuccess: () => {
                    this.flashSuccess(this.$t('settings.settingsSaved'));
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
