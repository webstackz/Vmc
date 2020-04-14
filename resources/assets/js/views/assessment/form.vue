<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Shop Details</span>
            </div>
            <div class="panel-body">
               
                <table class="item-table">
                    <thead>
                        <tr>
                            
                            <th>Category</th>
                            <th>Shop Name</th>
                            <th>Address</th>
                            <th>Mobile No</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(item, index) in form.items">
                            <tr>
                               <!-- <td :class="['width-3', errors(`items.${index}.ward_no`)]">
                                    <typeahead :initial="item.ward_no" 
                                        @input="onWardUpdated(item, index, $event)"
                                        :url="WardNoURL"
                                    >
                                    </typeahead>
                                    <error-text :error="error[`items.${index}.ward_no`]"></error-text>
                                </td>-->
                                <td :class="['width-3', errors(`items.${index}.covid19_category`)]">
                                    <typeahead :initial="item.covid19_category" 
                                        @input="onCategoryUpdated(item, index, $event)"
                                        :url="Covid19CategoryURL"
                                    >
                                    </typeahead>
                                    <!--<error-text :error="error[`items.${index}.covid19_category`]"></error-text>-->
                                </td>
                                <td :class="['width-3', errors(`items.${index}.shop_name`)]">
                                    <input type="text" class="form-control" v-model="item.shop_name">
                                    <!-- <error-text :error="error[`items.${index}.shop_name`]"></error-text>-->
                                </td>
                                <td :class="['width-3', errors(`items.${index}.address`)]">
                                    <input type="text" class="form-control" v-model="item.address">
                                    <!--<error-text :error="error[`items.${index}.address`]"></error-text>-->
                                </td>
                                <td :class="['width-3', errors(`items.${index}.mobile_no`)]">
                                    <input type="text" class="form-control" v-model="item.mobile_no">
                                    <!--<error-text :error="error[`items.${index}.mobile_no`]"></error-text>-->
                                </td>
                                <td>
                                    <button class="item-remove" @click="removeProduct(item, index)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            
                        </template>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="item-empty">
                                <button class="btn btn-success btn-sm " @click="addNewLine">
                                    Add Shop
                                </button>
                            </td>
                           
                        </tr>
                       
                    </tfoot>
                </table>
                <hr>
               
            </div>
            <div class="panel-footer">
                <spinner v-if="isProcessing"></spinner>
                <div class="btn-group" v-else>
                    <button :disabled="isProcessing" @click="save" class="btn btn-primary">
                        Save
                    </button>
                    <button :disabled="isProcessing" v-if="!isEdit"
                        @click="saveAndNew" class="btn btn-secondary">
                        Save and New
                    </button>
                    <router-link :disabled="isProcessing" :to="`${resource}/${$route.params.id}`"
                        class="btn" v-if="isEdit">
                        Cancel
                    </router-link>
                    <router-link :disabled="isProcessing" :to="`${resource}`"
                        class="btn" v-else>
                        Cancel
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import ErrorText from '../../components/form/ErrorText.vue'
    import Typeahead from '../../components/form/Typeahead.vue'
    import FileUpload from '../../components/form/FileUpload.vue'
    import Spinner from '../../components/loading/Spinner.vue'
    import { get, byMethod } from '../../lib/api'
    import { form } from '../../lib/mixins'

    function initializeUrl (to) {
        let urls = {
            'create': `/api/sales_orders/create`,
            'edit': `/api/sales_orders/${to.params.id}/edit`,
            'clone': `/api/sales_orders/${to.params.id}/edit?mode=clone`,
            'sales_order': `/api/quotations/${to.params.id}/edit?mode=sales_order`,
        }

        return (urls[to.meta.mode] || urls['create'])
    }

    export default {
        components: { ErrorText, Typeahead, Spinner, FileUpload },
        mixins: [ form ],
        data () {
            return {
                resource: '/sales_orders',
                store: '/api/sales_orders',
                method: 'POST',
                title: 'Add',
                message: 'You have successfully created sales order!',
                currencyURL: '/api/search/currencies',
                productURL: '/api/search/products',
                clientURL: '/api/search/clients',
                WardNoURL: '/api/search/WardNo',
                Covid19CategoryURL: '/api/search/Covid19Category',
            }
        },

        
        beforeRouteEnter(to, from, next) {
            get(initializeUrl(to), to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false

            get(initializeUrl(to), to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            onDocument(e) {
                Vue.set(this.$data.form, 'document', e.target.value)
            },
            removeProduct(item, index) {
                if(this.form.items.length > 1) {
                    this.form.items.splice(index, 1)
                }
            },
            addNewLine() {
                this.form.items.push({
                    'ward_no': null,
                    'covid19_category': null,
                    'shop_name': null,
                    'address': null,
                    'mobile_no': null,
                })
            },
            onWardUpdated(item, index, e) {
                const ward = e.target.value               
                // Vue.set(this.form.items[index], 'ward_no', ward.ward_no)
                Vue.set(this.form.items[index], 'ward_no', ward)
            }, 
            onCategoryUpdated(item, index, e) {
                const category = e.target.value               
                //Vue.set(this.form.items[index], 'covid19_category', category.category_id)
                Vue.set(this.form.items[index], 'covid19_category', category)

            },
            save() {
                this.submitMultipartForm(this.form, (data) => {
                    this.success()
                    this.$router.push(`${this.resource}`)
                })
            },
            saveAndNew() {
                this.submitMultipartForm(this.form, (data) => {
                    const id = Math.random().toString(36).substring(7)
                    this.endProcessing()
                    this.success()
                    this.$router.push(`${this.resource}/create?new=${id}`)
                })
            },
            onCurrencyUpdate(e) {
                const currency = e.target.value

                Vue.set(this.form, 'currency_id', currency.id)
                Vue.set(this.form, 'currency', currency)
            },
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Shop Details ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
