<template>
    <div>
        <AppLayout>
            <div class="grid">
                <div class="col-6 col-offset-3">
                    <div class="card">
                        <h5 v-if="!driver">Dodaj kierowcę</h5>
                        <h5 v-else>Edytuj kierowcę</h5>
                        <div class="field">
                            <label for="name">Nazwa</label>
                            <InputText
                                id="name"
                                class="w-full"
                                v-model="form.name"
                                :class="{'p-invalid': form.errors.name}"
                            />
                            <small v-if="form.errors.name" class="p-invalid">
                                {{ form.errors.name }}
                            </small>
                        </div>
                        <div class="field">
                            <label for="login">Login</label>
                            <InputText
                                id="login"
                                class="w-full"
                                v-model="form.login"
                                :class="{'p-invalid': form.errors.login}"
                            />
                            <small v-if="form.errors.login" class="p-invalid">
                                {{ form.errors.login }}
                            </small>
                        </div>
                        <div class="field">
                            <label for="password">Hasło</label>
                            <InputText
                                id="password"
                                class="w-full"
                                type="password"
                                v-model="form.password"
                                :class="{'p-invalid': form.errors.password}"
                            />
                            <small v-if="form.errors.password" class="p-invalid">
                                {{ form.errors.password }}
                            </small>
                        </div>
                        <div class="field">
                            <label for="color">Kolor</label>
                            <Dropdown
                                id="color"
                                class="w-full"
                                :options="colors"
                                option-value="value"
                                option-label="name"
                                placeholder="Wybierz kolor"
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
                        <div class="field">
                            <label for="lat">Szerokość geograficzna punktu końcowego</label>
                            <InputText
                                id="lat"
                                class="w-full"
                                v-model="form.lat"
                                :class="{'p-invalid': form.errors.lat}"
                            />
                            <small v-if="form.errors.lat" class="p-invalid">
                                {{ form.errors.lat }}
                            </small>
                        </div>
                        <div class="field">
                            <label for="long">Długość geograficzna punktu końcowego</label>
                            <InputText
                                id="long"
                                class="w-full"
                                v-model="form.long"
                                :class="{'p-invalid': form.errors.long}"
                            />
                            <small v-if="form.errors.long" class="p-invalid">
                                {{ form.errors.long }}
                            </small>
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
import Dropdown from "primevue/dropdown";
import FlashMessage from "../../Services/FlashMessage";

export default {
    name: "Create",
    components: {
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
                        this.flashSuccess('Zapisano kierowcę.');
                    }
                })
            } else {
                this.form.post(this.route('drivers.store'), {
                    onSuccess: () => {
                        this.flashSuccess('Dodano kierowcę.');
                    }
                })
            }
        }
    }
}
</script>

<style scoped>

</style>
