<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Covid-19 Shop Details</span>
            <router-link slot="create" to="/covid_config/create" class="btn btn-primary">
                Add Shop
            </router-link>
            <tr slot-scope="props">
                    <td class="width-2">{{ props.item.shop_id }}</td>      
                    <td class="width-2">{{ props.item.category_name }}</td>                    
                    <td class="width-2">{{ props.item.shop_name }}</td>
                    <td class="width-2">{{ props.item.address }}</td>
                    <td class="width-2">{{ props.item.shop_no }}</td>
                   
                </tr>
        </panel>
    </div>
</template>
<script type="text/javascript">
    import Panel from '../../components/search/panel.vue'
    import { get } from '../../lib/api'

    export default {
        components: { Panel },
        data() {
            return {
                resource: '/covid_config',
                heading: [
                    {title: 'ID', name: 'shop_id', sort: true},
                    {title: 'Category', name: 'category_name', sort: true},
                    {title: 'Shop Name', name: 'shop_name', sort: true},
                    {title: 'Address', name: 'address', sort: false},
                    {title: 'Mobile No', name: 'shop_no', sort: true},                   
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/covid_config', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/covid_config', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set(`Covid Config`)
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
