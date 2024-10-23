<script>
    import AppFormWrapperComponent from "../wrappers/AppFormWrapperComponent.vue";
    import InputMask from "primevue/inputmask";
    import Button from "primevue/button";
    import {IDENTIFIER, MESSAGES} from "../../constant";
    import {updateUserRequest} from "../../api/UserRequest";
    import {createLogOptionRequest} from "../../api/CreateLogOptionRequest";

    export default {
        data()
        {
            return {
                currentDate: new Date(),
                phone: null,
                errors: []
            };
        },
        props: {
            userPhoneProps: Object
        },
        components: {
            AppFormWrapperComponent,
            InputMask,
            Button

        },
        methods: {
            isValid: function(fields) {
                this.errors = fields
            },
            userPhoneUpdate: async function ()
            {
                let attributes = {
                    id: window.$cookies.get(IDENTIFIER),
                    phone: this.phone
                };
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
            userPhoneProps: {
                handler(newVal) {
                    this.phone = newVal;
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
                <label for="name">Ваш номер телефона</label>
                <InputMask type="tel"
                           v-model="this.phone"
                           class="w-100"
                           :value="this.phone"
                           mask="+7 (999) 999-99-99"
                           :invalid="this.errors !== null && 'phone' in this.errors" />
                <section id="errorField" v-if="this.errors !== null && 'phone' in this.errors">
                    <small v-for="error in this.errors.phone">
                        <i class="pi pi-times-circle"></i> {{ error }}
                    </small>
                </section>
            </div>
        </section>
        <Button type="button"
                label="Сохранить"
                class="w-100 mt-3"
                severity="success"
                @click="this.userPhoneUpdate" />
    </AppFormWrapperComponent>
</template>
