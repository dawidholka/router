<template>
    <Head title="Log in"/>

    <MinimalLayout :title="$t('auth.login')">
        <template #content>
            <div class="fluid">
                <div class="field">
                    <label for="email">
                        {{ $t("common.email")}}
                    </label>
                    <InputText id="email"
                               v-model="form.email"
                               type="email"
                               required="true"
                               :class="{'p-invalid': form.errors.email}"
                               @keyup.enter="submit"
                    />
                    <small v-if="form.errors.email" class="p-invalid">
                        {{ form.errors.email }}
                    </small>
                </div>
                <div class="field">
                    <label for="password">
                        {{ $t("common.password")}}
                    </label>
                    <InputText id="email"
                               v-model="form.password"
                               required="true"
                               type="password"
                               autocomplete="current-password"
                               @keyup.enter="submit"
                               :class="{'p-invalid': form.errors.password}"
                    />
                    <small v-if="form.errors.password" class="p-invalid">
                        {{ form.errors.password }}
                    </small>
                </div>
                <div class="field">
                    <Button :label="$t('auth.signIn')"
                            icon="pi pi-sign-in"
                            type="submit"
                            @click="submit"
                            :loading="form.processing"
                    />
                </div>
            </div>
        </template>
    </MinimalLayout>
</template>

<script>
import {Head, Link} from '@inertiajs/inertia-vue3';
import MinimalLayout from "../../Layouts/MinimalLayout";
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';

export default {
    components: {
        MinimalLayout,
        Head,
        Link,
        Button,
        InputText
    },

    props: {
        canResetPassword: Boolean,
        status: String
    },

    data() {
        return {
            form: this.$inertia.form({
                email: '',
                password: '',
                remember: false
            })
        }
    },

    methods: {
        submit() {
            this.form
                .transform(data => ({
                    ...data,
                    remember: this.form.remember ? 'on' : ''
                }))
                .post(this.route('login'), {
                    onFinish: () => this.form.reset('password'),
                })
        }
    }
}
</script>
