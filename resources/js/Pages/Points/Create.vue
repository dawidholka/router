<template>
    <div>
        <AppLayout>
            <div class="grid">
                <div class="col-6 col-offset-3">
                    <div class="card">
                        <h5 v-if="!point">{{ $t('points.addPoint') }}</h5>
                        <h5 v-else>{{ $t('points.editPoint') }}</h5>
                        <div class="formgrid grid">
                            <RouterInputText
                                class="col"
                                name="name"
                                :label="$t('common.name')"
                                v-model="form.name"
                                :error="form.errors.name"
                            />
                            <RouterInputText
                                class="col"
                                name="street"
                                :label="$t('common.street')"
                                v-model="form.street"
                                :error="form.errors.street"
                            />
                        </div>
                        <div class="formgrid grid">
                            <RouterInputText
                                class="col"
                                name="building_number"
                                :label="$t('common.buildingNumber')"
                                v-model="form.building_number"
                                :error="form.errors.building_number"
                            />
                            <RouterInputText
                                class="col"
                                name="apartament"
                                :label="$t('common.apartment')"
                                v-model="form.apartament"
                                :error="form.errors.apartament"
                            />
                            <RouterInputText
                                class="col"
                                name="city"
                                :label="$t('common.city')"
                                v-model="form.city"
                                :error="form.errors.city"
                            />
                        </div>
                        <div class="formgrid grid">
                            <RouterInputText
                                class="col"
                                name="intercom"
                                :label="$t('common.intercom')"
                                v-model="form.intercom"
                                :error="form.errors.intercom"
                            />
                            <RouterInputText
                                class="col"
                                name="delivery_time"
                                :label="$t('common.deliveryTime')"
                                v-model="form.delivery_time"
                                :error="form.errors.delivery_time"
                            />
                            <RouterInputText
                                class="col"
                                name="phone"
                                :label="$t('common.phone')"
                                v-model="form.phone"
                                :error="form.errors.phone"
                            />
                        </div>
                        <div class="field">
                            <label for="note">{{ $t('common.note') }}</label>
                            <Textarea
                                id="note"
                                class="w-full"
                                rows="5"
                                v-model="form.note"
                            />
                            <small v-if="form.errors.note" class="p-invalid">
                                {{ form.errors.note }}
                            </small>
                        </div>
                        <div class="formgrid grid">
                            <RouterInputText
                                class="col"
                                name="lat"
                                :label="$t('common.lat')"
                                v-model="form.lat"
                                :error="form.errors.lat"
                            />
                            <RouterInputText
                                class="col"
                                name="long"
                                :label="$t('common.lng')"
                                v-model="form.long"
                                :error="form.errors.long"
                            />
                            <div class="field col">
                                <label for="lock_geo">{{ $t('common.lockGeo') }}</label>
                                <Dropdown
                                    id="lock_geo"
                                    class="w-full"
                                    v-model="form.lock_geo"
                                    :options="lockGeoOptions"
                                    option-label="name"
                                    option-value="value"
                                    placeholder="Wybierz"
                                />
                                <small v-if="form.errors.lock_geo" class="p-invalid">
                                    {{ form.errors.lock_geo }}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-content-end mt-3">
                        <Button
                            :label="$t('common.cancel')"
                            class="p-button-secondary p-button-outlined p-button-lg mr-3"
                            @click="cancel"
                        />
                        <Button
                            :label="$t('common.save')"
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
import RouterInputText from "../../Components/RouterInputText";

export default {
    name: "Create",
    components: {
        AppLayout,
        Button,
        InputText,
        Textarea,
        Dropdown,
        RouterInputText
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
