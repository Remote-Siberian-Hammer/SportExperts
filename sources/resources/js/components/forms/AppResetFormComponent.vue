<script>
    import AppFormWrapperComponent from "../wrappers/AppFormWrapperComponent.vue";
    import { resetToPasswordRequest } from "../../api/UserRequest";
    import { MESSAGES } from "../../constant";
    import { createLogOptionRequest } from "../../api/CreateLogOptionRequest";
    import Card from "primevue/card";
    import InputText from "primevue/inputtext";
    import Button from "primevue/button";

    export default {
        data() {
          return {
              email: null,
              currentDate: new Date(),
              error: []
          };
        },
        components: {
            Card,
            InputText,
            Button,
            AppFormWrapperComponent
        },
        methods: {
            resetNotification: async function() {
                const attributes = {
                    email: this.email
                };
                if (!attributes.email)
                {
                    this.$emit('messageErrorEmit', MESSAGES.NO_VALID_DATA);
                    return;
                }
                await resetToPasswordRequest(attributes)
                    .then(async (response) => {
                        console.log(response);
                        await this.$emit('messageSuccessEmit', MESSAGES.SEND_NOTIFICATION);
                    })
                    .catch(async (error) => {
                        console.log(error);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'resetToPasswordRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        await this.$emit('messageErrorEmit', MESSAGES.NO_VALID_DATA);
                    });
            }
        }
    }
</script>

<template>
    <AppFormWrapperComponent>
        <div class="form-block">
            <label for="#">Введите E-mail</label>
            <InputText type="email"
                       v-model="email"
                       class="w-100" />
        </div>
        <div class="form-block d-flex d-between">
            <Button type="button"
                    label="Отправить письмо"
                    class="w-30"
                    severity="success"
                    @click="resetNotification" />
        </div>
    </AppFormWrapperComponent>
</template>
