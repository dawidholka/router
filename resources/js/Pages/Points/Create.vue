<template>
    <div>
        <AppLayout>
            <div class="grid">
                <div class="col-6 col-offset-3">
                    <div class="card">
                        <h5 v-if="!point">Dodawanie nowego punktu</h5>
                        <h5 v-else>Edycja punktu</h5>
                        <div class="formgrid grid">
                            <div class="field col">
                                <label for="name">Nazwa*</label>
                                <InputText
                                    id="name"
                                    class="w-full"
                                    v-model="form.name"
                                    :class="{'p-invalid': form.errors.name}"
                                />
                            </div>
                            <div class="field col">
                                <label for="street">Ulica*</label>
                                <InputText
                                    id="street"
                                    class="w-full"
                                    v-model="form.street"
                                    :class="{'p-invalid': form.errors.street}"
                                />
                            </div>
                        </div>
                        <div class="formgrid grid">
                            <div class="field col">
                                <label for="building_number">Nr domu*</label>
                                <InputText
                                    id="building_number"
                                    class="w-full"
                                    v-model="form.building_number"
                                    :class="{'p-invalid': form.errors.building_number}"
                                />
                            </div>
                            <div class="field col">
                                <label for="apartament">Numer mieszkania</label>
                                <InputText
                                    id="apartament"
                                    class="w-full"
                                    v-model="form.apartament"
                                    :class="{'p-invalid': form.errors.apartament}"
                                />
                            </div>
                            <div class="field col">
                                <label for="city">Miasto*</label>
                                <InputText
                                    id="city"
                                    class="w-full"
                                    v-model="form.city"
                                    :class="{'p-invalid': form.errors.city}"
                                />
                            </div>
                        </div>
                        <div class="formgrid grid">
                            <div class="field col">
                                <label for="intercom">Kod do domofonu</label>
                                <InputText
                                    id="intercom"
                                    class="w-full"
                                    v-model="form.intercom"
                                    :class="{'p-invalid': form.errors.intercom}"
                                />
                            </div>
                            <div class="field col">
                                <label for="delivery_time">Czas dostawy</label>
                                <InputText
                                    id="apartament"
                                    class="w-full"
                                    v-model="form.delivery_time"
                                    :class="{'p-invalid': form.errors.delivery_time}"
                                />
                            </div>
                            <div class="field col">
                                <label for="phone">Telefon</label>
                                <InputText
                                    id="city"
                                    class="w-full"
                                    v-model="form.phone"
                                    :class="{'p-invalid': form.errors.phone}"
                                />
                            </div>
                        </div>
                        <div class="field">
                            <label for="note">Notatka</label>
                            <Textarea
                                id="note"
                                class="w-full"
                                rows="5"
                                v-model="form.note"
                            />
                        </div>
                        <div class="formgrid grid">
                            <div class="field col">
                                <label for="lat">Szerokość geograficzna</label>
                                <InputText
                                    id="lat"
                                    class="w-full"
                                    v-model="form.lat"
                                    :class="{'p-invalid': form.errors.lat}"
                                />
                            </div>
                            <div class="field col">
                                <label for="long">Długość geograficzna</label>
                                <InputText
                                    id="long"
                                    class="w-full"
                                    v-model="form.long"
                                    :class="{'p-invalid': form.errors.long}"
                                />
                            </div>
                            <div class="field col">
                                <label for="lock_geo">Zablokuj zmianę</label>
                                <Dropdown
                                    id="lock_geo"
                                    class="w-full"
                                    v-model="form.lock_geo"
                                    :options="lockGeoOptions"
                                    option-label="name"
                                    option-value="value"
                                    placeholder="Wybierz"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-content-end mt-3">
                        <Button
                            label="Anuluj"
                            class="p-button-secondary p-button-outlined p-button-lg mr-3"
                            @click="cancel"
                        />
                        <Button
                            label="Zapisz"
                            class="p-button-lg"
                            :loading="form.processing"
                            @click="submit"
                        />
                    </div>
                </div>
            </div>
        </AppLayout>
    </div>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Dropdown from "primevue/dropdown";

export default {
    name: "Create",
    components: {
        AppLayout,
        Button,
        InputText,
        Textarea,
        Dropdown
    },
    props: {
        point: {
            type: Object,
            default: null
        }
    },
    data() {
        return {
            form: this.$inertia.form({
                'name': this.point?.name ?? null,
                'street': this.point?.street ?? null,
                'building_number': this.point?.building_number ?? null,
                'city': this.point?.city ?? null,
                'apartament': this.point?.apartament ?? null,
                'intercom': this.point?.intercom ?? null,
                'delivery_time': this.point?.delivery_time ?? null,
                'phone': this.point?.phone ?? null,
                'lat': this.point?.lat ?? null,
                'long': this.point?.long ?? null,
                'note': this.point?.note ?? null,
                'lock_geo': this.point?.lock_geo ?? false,
                'quantity': this.point?.quantity ?? null,
            }),
            lockGeoOptions: [
                {value: false, name: 'Nie'},
                {value: true, name: 'Tak'},
            ]
        }
    },
    methods: {
        cancel() {
            this.$inertia.get(this.route('points.index'));
        },
        submit() {
            if (this.point) {
                this.form.put(this.route('points.update', this.point.id), {
                    onSuccess: () => {

                    }
                })
            } else {
                this.form.post(this.route('points.store'), {
                    onSuccess: () => {

                    }
                })
            }
        }
    }
}
</script>

<style scoped>

</style>
