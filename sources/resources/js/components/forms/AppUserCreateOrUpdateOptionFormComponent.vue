<script>
    import AppFormWrapperComponent from "../wrappers/AppFormWrapperComponent.vue";
    import InputText from "primevue/inputtext";
    import Button from "primevue/button";
    import { createOptionRequest, updateOptionRequest } from "../../api/OptionRequest";
    import {IDENTIFIER, MESSAGES} from "../../constant";
    import {createLogOptionRequest} from "../../api/CreateLogOptionRequest";

    export default {
        data() {
            return {
                currentDate: new Date(),
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
            }
        },
        props: {
            userOptionsProps: Object
        },
        components: {
            AppFormWrapperComponent,
            InputText,
            Button,
        },
        methods: {
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
                            this.$emit('messageSuccessEmit', MESSAGES.FORM_SUCCESS);
                        })
                        .catch(async (error) => {
                            console.log(error);
                            this.$emit('messageErrorEmit', MESSAGES.ERROR_ERROR);
                            await createLogOptionRequest({
                                current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                                current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                                method: 'userOptionsCreate',
                                status: error.code,
                                request_data: '',
                                message: error.message
                            })
                        });
                }
            },
            userOptionsUpdate: async function (idx)
            {
                if (this.options[idx].value)
                {
                    let attributes = this.options[idx];
                    await updateOptionRequest(attributes)
                        .then(async (response) => {
                            console.log(response);
                            const data = await response.data.result.original;
                            this.options[idx] = await data.attributes;
                        })
                        .catch(async (error) => {
                            console.log(error);
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
        watch: {
            userOptionsProps: {
                handler(newVal) {
                    this.options = newVal;
                },
                immediate: true,
                deep: true
            }
        }
    }
</script>

<template>
    <AppFormWrapperComponent>
        <section v-for="item in this.options"
                 :key="item.id"
                 class="d-flex d-center">
            <div v-if="item.name === 'Weight'" class="form-block w-100">
                <label for="name">Вес</label>
                <InputText type="number"
                           v-model="this.options[0].value"
                           class="w-100"
                           :value="item.value"
                           required />
            </div>
            <div v-else-if="item.name === 'Height'" class="form-block w-100">
                <label for="name">Рост</label>
                <InputText type="number"
                           v-model="this.options[1].value"
                           class="w-100"
                           :value="item.value"
                           required />
            </div>
        </section>
        <Button type="button"
                label="Обновить параметры"
                class="w-100 mt-3"
                severity="success"
                @click="this.setUserOptions"/>
    </AppFormWrapperComponent>
</template>

<style scoped>

</style>
