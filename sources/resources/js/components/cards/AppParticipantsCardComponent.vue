<script>
    import {listInvitedRequest} from "../../api/InvitedRequest";
    import {createLogOptionRequest} from "../../api/CreateLogOptionRequest";
    import Card from "primevue/card";
    import DataTable from "primevue/datatable";
    import Column from "primevue/column";
    import Button from "primevue/button";
    import {IDENTIFIER} from "../../common/fields";

    export default {
        data() {
          return {
              userInvites: [],
              currentDate: new Date(),
          };
        },
        props: {
            userProps: Object,
            baseUrlProps: String
        },
        components: {
            Card,
            DataTable,
            Column,
            Button
        },
        methods: {
            getUserInvites: async function ()
            {
                let attributes = {
                    who_user_id: window.$cookies.get(IDENTIFIER)
                };
                await listInvitedRequest(attributes)
                    .then(async (response) => {
                        console.log(response);
                        const data = await response.data.result.original;
                        this.userInvites = await data.attributes;
                    })
                    .catch(async (error) => {
                        console.log(error);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'listInvitedRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                    });
            },
        },
        async beforeMount() {
            await this.getUserInvites();
        },
    }
</script>

<template>
    <section v-if="this.userProps !== null && this.userProps.role === 'admin' && this.userInvites !== null && this.userInvites.length > 0" class="w-100 mt-5 mb-5">
        <Card>
            <section>
                <a href="#">
                    <Button type="button" label="Полный список спортсменов" severity="primary"/>
                </a>
            </section>
            <template #header>
                <div class="d-flex d-center">
                    <h4>Ваши спортсмены:</h4>
                </div>
            </template>
            <template #content>
                <DataTable :value="this.userInvites" class="mobile-none">
                    <Column header="">
                        <template #body>
                            <Image src="images/athlete_default_avatar.png" width="30" />
                        </template>
                    </Column>
                    <Column field="users.first_name" header="Имя"></Column>
                    <Column field="users.last_name" header="Фамилия"></Column>
                    <!-- <Column v-if="this.userProps" header="">
                        <template #body>
                            <a :href="this.baseUrlProps + 'invite/detail?user_id=' + this.userProps.id">
                                <Button type="button" label="Подробнее" severity="secondary"/>
                            </a>
                        </template>
                    </Column> -->
                </DataTable>
            </template>
            <template #footer>
                <section>
                    <a :href="this.baseUrlProps + 'invite'">
                        <Button icon="pi pi-refresh"
                                class="w-100"
                                type="button"
                                label="Показать все"
                                severity="primary"/>
                    </a>
                </section>
            </template>
            <!--    <section v-if="this.events && this.events[this.response.data].length > 9" class="mt-5 mb-5">-->
            <!--    <Paginator :rows="9" :totalRecords="120"></Paginator>-->
            <!--    </section>-->
        </Card>
    </section>
</template>

<style scoped>
    #table .p-card-body{
        padding: 0.7em!important;
    }
</style>
