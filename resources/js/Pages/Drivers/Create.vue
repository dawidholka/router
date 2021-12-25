<template>
    <div>
        <AppLayout>
            <div class="grid">
                <div class="col-6 col-offset-3">
                    <div class="card">
                        <h5 v-if="!driver">{{ $t('drivers.addDriver') }}</h5>
                        <h5 v-else>{{ $t('drivers.editDriver') }}</h5>
                        <RouterInputText
                            name="name"
                            :label="$t('common.name')"
                            v-model="form.name"
                            :error="form.errors.name"
                        />
                        <RouterInputText
                            name="login"
                            :label="$t('common.login')"
                            v-model="form.login"
                            :error="form.errors.login"
                        />
                        <RouterInputText
                            name="password"
                            type="password"
                            :label="$t('common.password')"
                            v-model="form.password"
                            :error="form.errors.password"
                        />
                        <div class="field">
                            <label for="color">{{ $t('common.color') }}</label>
                            <Dropdown
                                id="color"
                                class="w-full"
                                :options="colors"
                                option-value="value"
                                option-label="name"
                                :placeholder="$t('common.chooseOption')"
                                v-model="form.color"
                                :class="{'p-invalid': form.errors.color}"
                            >
                                <template #option="slotProps">
                                    <div :style="{'color': slotProps.option.name}">
                                        <span>{{ slotProps.option.name }}</span>
                                    </div>
                                </template>
                            </Dropdown>
                            <small v-if="form.errors.color" class="p-invalid">
                                {{ form.errors.color }}
                            </small>
                        </div>
                        <RouterInputText
                            name="lat"
                            :label="$t('drivers.driverLat')"
                            v-model="form.lat"
                            :error="form.errors.lat"
                        />
                        <RouterInputText
                            name="long"
                            :label="$t('drivers.driverLng')"
                            v-model="form.long"
                            :error="form.errors.long"
                        />

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
import Dropdown from "primevue/dropdown";
import FlashMessage from "../../Services/FlashMessage";
import RouterInputText from "../../Components/RouterInputText";

export default {
    name: "Create",
    components: {
        RouterInputText,
        AppLayout,
        Button,
        InputText,
        Dropdown,
    },
    mixins: [FlashMessage],
    props: {
        driver: {
            type: Object,
            default: null
        },
        colors: {
            type: Array,
            default: []
        }
    },
    data() {
        return {
            form: this.$inertia.form({
                'login': this.driver?.login ?? null,
                'password': null,
                'name': this.driver?.name ?? null,
                'color': this.driver?.color ?? null,
                'lat': this.driver?.lat ?? null,
                'long': this.driver?.long ?? null,
            })
        }
    },
    methods: {
        cancel() {
            this.$inertia.get(this.route('drivers.index'));
        },
        submit() {
            if (this.driver) {
                this.form.put(this.route('drivers.update', this.driver.id), {
                    onSuccess: () => {
                        this.flashSuccess(this.$t('drivers.driverSaved'));
                    }
                })
            } else {
                this.form.post(this.route('drivers.store'), {
                    onSuccess: () => {
                        this.flashSuccess(this.$t('drivers.driverAdded'));
                    }
                })
            }
        }
    }
}
</script>

<style scoped>

</style>
