<script>
    import AppFormWrapperComponent from "../wrappers/AppFormWrapperComponent.vue";
    import {IDENTIFIER, MESSAGES} from "../../constant";
    import {createOptionRequest, getOptionRequest} from "../../api/OptionRequest";
    import {createLogOptionRequest} from "../../api/CreateLogOptionRequest";
    import Dialog from "primevue/dialog";
    import InputText from "primevue/inputtext";
    import Button from "primevue/button";

    export default {
        data() {
            return {
                currentDate: new Date(),
                visible: true,
                userOptionView: false,
                options: [
                    {
                        user_id: window.$cookies.get(IDENTIFIER),
                        entity: 'user',
                        name: 'Weight',
                        value: null,
                        type: 'string',
                    },
                    {
                        user_id: window.$cookies.get(IDENTIFIER),
                        entity: 'user',
                        name: 'Height',
                        value: null,
                        type: 'string',
                    },
                ],
                user: null
            };
        },
        props: {
            userProps: Object
        },
        components: {
            AppFormWrapperComponent,
            Dialog,
            InputText,
            Button
        },
        methods: {
            getUserOptions: async function()
            {
                let attributes = {
                    user_id: this.user.id
                };
                await getOptionRequest(attributes)
                    .then(async (response) => {
                        const data = await response.data.result.original;
                        this.options = await data.attributes;
                        if (this.options.length === 0)
                        {
                            this.userOptionView = true;
                        }
                    })
                    .catch(async (error) => {
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'getOptionRequest',
                            status: error.code,
                            request_data: '',
                            message: error.message
                        })
                    });
            },
            userOptionsCreate: async function (idx)
            {
                if (this.options[idx].value)
                {
                    let attributes = this.options[idx];
                    await createOptionRequest(attributes)
                        .then(async (response) => {
                            console.log(response);
                            const data = await response.data.result.original;
                            this.options[idx] = await data.attributes;
                            this.messageSuccess = MESSAGES.FORM_SUCCESS;
                        })
                        .catch(async (error) => {
                            console.log(error);
                            await createLogOptionRequest({
                                current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                                current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                                method: 'userOptionsCreate',
                                status: error.code,
                                request_data: '',
                                message: error.message
                            })
                            this.messageError = MESSAGES.ERROR_ERROR;
                        });
                }
            },
            userOptionsUpdate: async function (idx)
            {
                if (this.options[idx].value)
                {
                    let attributes = this.options[idx];
                    await createOptionRequest(attributes)
                        .then(async (response) => {
                            const data = await response.data.result.original;
                            this.options[idx] = await data.attributes;
                            this.messageSuccess = MESSAGES.FORM_SUCCESS;
                        })
                        .catch(async (error) => {
                            this.messageError = MESSAGES.ERROR_ERROR;
                            await createLogOptionRequest({
                                current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                                current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                                method: 'userOptionsUpdate',
                                status: error.code,
                                request_data: '',
                                message: error.message
                            })
                        });
                }
            },
            setUserOptions: async function ()
            {
                for (let i=0; i < this.options.length; i++)
                {
                    if (Object.keys(this.options[i]).includes('id'))
                    {
                        await this.userOptionsUpdate(i);
                    }
                    else
                    {
                        await this.userOptionsCreate(i);
                    }
                }
            },
        },
        async beforeMount() {
            if (this.user.role === 'athlete') {
                await this.getUserOptions();
            }
        },
        watch: {
            userProps: {
                handler(value) {
                    this.user = value;
                },
                immediate: true,
                deep: true
            }
        }
    }
</script>

<template>
    <Dialog
        v-if="this.user && this.user.role === 'athlete' && this.userOptionView"
        v-model:visible="visible"
        modal
        header="Укажите ваши параметры"
        :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <section>
            <AppFormWrapperComponent>
                <small>Параметры учитываются в алгоритме жеребьёвки. Их желательно заполнить.</small>
                <section class="d-flex d-center">
                    <div class="form-block w-100">
                        <label for="name">Рост</label>
                        <InputText type="number"
                                   v-model="this.options[1].value"
                                   class="w-100"
                                   :value="this.options[1].value"
                                   required />
                    </div>
                </section>
                <section class="d-flex d-center">
                    <div class="form-block w-100">
                        <label for="name">Вес</label>
                        <InputText type="number"
                                   v-model="this.options[0].value"
                                   class="w-100"
                                   :value="this.options[0].value"
                                   required />
                    </div>
                </section>
                <Button type="button"
                        label="Добавить параметры"
                        class="w-100 mt-3"
                        severity="success"
                        @click="this.setUserOptions" />
            </AppFormWrapperComponent>
        </section>
    </Dialog>
</template>

<style scoped>

</style>
