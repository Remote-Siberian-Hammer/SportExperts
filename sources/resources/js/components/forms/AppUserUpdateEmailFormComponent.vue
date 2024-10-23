<script>
    import AppFormWrapperComponent from "../wrappers/AppFormWrapperComponent.vue";
    import InputText from "primevue/inputtext";
    import Button from "primevue/button";
    import {IDENTIFIER, MESSAGES} from "../../constant";
    import {updateUserRequest} from "../../api/UserRequest";
    import {createLogOptionRequest} from "../../api/CreateLogOptionRequest";

    export default {
        data() {
            return {
                currentDate: new Date(),
                email: null,
                errors: [],
            }
        },
        props: {
            userEmailProps: Object
        },
        components: {
            AppFormWrapperComponent,
            InputText,
            Button,
        },
        methods: {
            isValid: function(fields) {
                this.errors = fields
            },
            userEmailUpdate: async function ()
            {
                let attributes = {
                    id: window.$cookies.get(IDENTIFIER),
                    email: this.email
                };
                console.log(attributes);
                await updateUserRequest(attributes)
                    .then((response) => {
                        console.log(response);
                        if ('error' in response.data) {
                            this.isValid(response.data.error.data);
                            return;
                        }
                        this.$emit('messageSuccessEmit', MESSAGES.FORM_SUCCESS);
                    })
                    .catch(async (error) => {
                        console.log(error);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'updateUserRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        this.$emit('messageErrorEmit', MESSAGES.ERROR_ERROR);
                    });
            },
        },
        watch: {
            userEmailProps: {
                handler(newVal) {
                    this.email = newVal;
                },
                immediate: true,
                deep: true
            }
        }
    }
</script>

<template>
    <AppFormWrapperComponent>
        <section class="d-flex d-center">
            <div class="form-block w-100">
                <label for="name">Адрес электронной почты</label>
                <InputText type="email"
                           v-model="this.email"
                           class="w-100"
                           :value="this.email"
                           :invalid="this.errors !== null && 'email' in this.errors" />
                <section id="errorField" v-if="this.errors !== null && 'email' in this.errors">
                    <small v-for="error in this.errors.email">
                        <i class="pi pi-times-circle"></i> {{ error }}
                    </small>
                </section>
            </div>
        </section>
        <Button type="button"
                label="Сохранить"
                class="w-100 mt-3"
                severity="success"
                @click="this.userEmailUpdate" />
    </AppFormWrapperComponent>
</template>
