<script>
    import Card from "primevue/card";
    import {getEventParticipantRequest} from "../../api/FilterRequest";
    import {createLogOptionRequest} from "../../api/CreateLogOptionRequest";
    import {MESSAGES} from "../../common/messages";

    export default {
        data() {
          return {
              ownerUser: null,
              currentDate: new Date(),
          };
        },
        props: {
            userProps: Object,
        },
        components: {
            Card
        },
        methods: {
            getOwnerUser: async function ()
            {
                if (this.user != null && this.user.role === 'admin')
                {
                    let attributes = `user_id:${this.user.id}`;
                    await getEventParticipantRequest(attributes)
                        .then(async (response) => {
                            console.log(response);
                            const data = await response.data.result.original;
                            this.ownerUser = await data.attributes;
                        })
                        .catch(async (error) => {
                            console.log(error);
                            await createLogOptionRequest({
                                current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                                current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                                method: 'getEventParticipantRequest',
                                status: error.code,
                                request_data: attributes.toString(),
                                message: error.message
                            });
                            this.$emit('messageErrorEmit', MESSAGES.ERROR_DEFAULT);
                        });
                }
            },
        },
        async beforeMount() {
            await this.getOwnerUser();
        }
    }
</script>

<template>
    <section v-if="this.userProps !== null && this.userProps.role === 'athlete' && this.ownerUser" class="w-100 mt-5">
        <Card>
            <template #content>
                <div class="d-flex d-center">
                    <h4>Вас пригласил:</h4>
                </div>
                <h4>{{ this.ownerUser.last_name }} {{ this.ownerUser.first_name }}</h4>
                <div v-if="this.ownerUser.email">
                    <small>{{ this.ownerUser.email }}</small>
                </div>
                <div v-if="this.ownerUser.phone">
                    <small>{{ this.ownerUser.phone }}</small>
                </div>
            </template>
        </Card>
    </section>
</template>
