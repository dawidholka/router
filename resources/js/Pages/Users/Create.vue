<template>
    <div>
        <AppLayout>
            <div class="grid">
                <div class="col-6 col-offset-3">
                    <div class="card">
                        <h5 v-if="!pageUser">{{ $t('users.addUser') }}</h5>
                        <h5 v-else>{{ $t('users.editUser') }}</h5>
                        <div class="field">
                            <label for="name">{{ $t('common.name') }}</label>
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
                            <label for="login">{{ $t('common.email') }}</label>
                            <InputText
                                id="login"
                                class="w-full"
                                v-model="form.email"
                                :class="{'p-invalid': form.errors.email}"
                            />
                            <small v-if="form.errors.email" class="p-invalid">
                                {{ form.errors.email }}
                            </small>
                        </div>
                        <div class="field">
                            <label for="password">{{ $t('common.password') }}</label>
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
                            <label for="admin">{{ $t('common.admin') }}</label>
                            <Dropdown
                                id="admin"
                                class="w-full"
                                :options="adminOptions"
                                option-value="value"
                                option-label="name"
                                :placeholder="$t('common.chooseOption')"
                                v-model="form.admin"
                                :class="{'p-invalid': form.errors.admin}"
                            />
                            <small v-if="form.errors.admin" class="p-invalid">
                                {{ form.errors.admin }}
                            </small>
                        </div>

                    </div>
                    <div class="flex justify-content-end mt-3">
                        <Button
                            :label="$t('common.cancel')"
                            class="p-button-secondary p-button-outlined p-button-lg mr-3"
                            @click="cancel"
                        />
                        <Button
                            :label="$t('common.submit')"
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
        pageUser: {
            type: Object,
            default: null
        },
    },
    data() {
        return {
            form: this.$inertia.form({
                'name': this.pageUser?.name ?? null,
                'email': this.pageUser?.email ?? null,
                'password': null,
                'admin': this.pageUser?.admin ?? false,
            }),
            adminOptions: [
                {name: this.$t('common.no'), value: false},
                {name: this.$t('common.yes'), value: true}
            ]
        }
    },
    methods: {
        cancel() {
            this.$inertia.get(this.route('users.index'));
        },
        submit() {
            if (this.pageUser) {
                this.form.put(this.route('users.update', this.pageUser.id), {
                    onSuccess: () => {
                        this.flashSuccess(this.$t('users.userSaved'));
                    }
                })
            } else {
                this.form.post(this.route('users.store'), {
                    onSuccess: () => {
                        this.flashSuccess(this.$t('users.userAdded'));
                    }
                })
            }
        }
    }
}
</script>

<style scoped>

</style>
