<template>
  <div v-if="show">
    <div class="panel">
      <div class="panel-heading">
        <span class="panel-title">{{title}} Complaint</span>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col col-4">
            <div class="form-group">
              <label>
                Complaint No
                <small>(Auto Generated)</small>
              </label>
              <span class="form-control">{{form.complaint_no}}</span>
            </div>
           
            
             <div class="form-group">
              <label>Name</label>
              <input type="text" class="form-control" v-model="form.name" placeholder="Enter Name" />
              <error-text :error="error.name"></error-text>
            </div>
            <div class="form-group" v-if="!form.assess_list.length">
              <label>Assessment No</label>
              <input
                type="text"
                class="form-control"                            
                v-model="form.assessment_no"
                placeholder="Enter Assessment No"
              />
              <error-text :error="error.assessment_no"></error-text>
            </div>
            <div class="form-group">
              <label>Mobile No <small>(Primary Mobile)</small></label>
              <input
                type="text"
                class="form-control"
                v-model="form.mobile_no"
                placeholder="Enter Mobile No"
              />
              <error-text :error="error.mobile_no"></error-text>
            </div>
             <div class="form-group">
              <label>Mobile No 2</label>
              <input
                type="text"
                class="form-control"
                v-model="form.mobile_no_2"
                placeholder="Enter Mobile No"
              />
              <error-text :error="error.mobile_no_2"></error-text>
            </div>
            
             <div class="form-group">
              <label>Mobile No 3</label>
              <input
                type="text"
                class="form-control"                          
                v-model="form.mobile_no_3"
                placeholder="Enter Mobile No"
              />
              <error-text :error="error.mobile_no_3"></error-text>
            </div>
   

          </div>
          <div class="col col-4">
            <!-- <div class="form-group">
              <label>Department</label>
              <typeahead
                :initial="form.dept"
                :url="DepartmentURL"
                @input="onDepartmentUpdate"
                placeholder="Select Department"
              ></typeahead>
              <error-text :error="error.dept"></error-text>
            </div>-->
           
            <div class="form-group">
              <label>Ward No</label>
              <input
                type="text"
                class="form-control"
                v-model="form.ward_no"
                placeholder="Enter Ward No"
              />
              <error-text :error="error.ward_no"></error-text>
            </div>
            <div class="form-group">
              <label>Street Name</label>
              <input
                type="text"
                class="form-control"
                v-model="form.street_no"
                placeholder="Enter Street Name"
              />

              <error-text :error="error.street_no"></error-text>
            </div>
            <div class="form-group">
              <label>Door No</label>
              <input
                type="text"
                class="form-control"
                v-model="form.door_no"
                placeholder="Enter Door No"
              />
              <error-text :error="error.door_no"></error-text>
            </div>
          </div>

          <div class="col col-4">
             

            <div class="form-group">
              <label>Remarks</label>
              <input
                type="text"
                class="form-control"
                v-model="form.description"
                placeholder="Enter Remarks"
              />
            </div>
             <div class="form-group">
              <label>Complaint Type</label>
              <typeahead
                :initial="form.type"
                :url="ComplaintTypeURL"
                @input="onComplaintTypeUpdate"
                placeholder="Select Complaint Type"
              ></typeahead>
              <error-text :error="error.type"></error-text>
            </div>
            <div class="form-group" v-if="isEdit">
              <label>Complaint Status</label>
              <typeahead
                :initial="form.status"
                :url="ComplaintStatusURL"
                @input="onComplaintStatusUpdate"
                placeholder="Select Complaint Status"
              ></typeahead>
              <error-text :error="error.status_id"></error-text>
            </div>
          </div>
        </div>
      </div>
      <div class="panel-footer">
        <spinner v-if="isProcessing"></spinner>
        <div class="btn-group" v-else>
          <button :disabled="isProcessing" @click="save" class="btn btn-primary">Save</button>
          <button
            :disabled="isProcessing"
            v-if="!isEdit"
            @click="saveAndNew"
            class="btn btn-secondary"
          >Save and New</button>
          <router-link
            :disabled="isProcessing"
            :to="`${resource}/${$route.params.id}`"
            class="btn"
            v-if="isEdit"
          >Cancel</router-link>
          <router-link :disabled="isProcessing" :to="`${resource}`" class="btn" v-else>Cancel</router-link>
        </div>
      </div>
    </div>
    <div class="panel" v-if="isEdit && shop_list.length && form.status_id != 4">
      <div class="panel-heading">
        <span class="panel-title">Shop Details</span>
      </div>
      <div class="row">
        <div class="col col-12">
          <table class="table">
            <thead>
              <tr>
                <th>Category</th>
                <th>Shop Name</th>
                <th>Address</th>
                <th>Mobile No</th>
                <th>SMS</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in shop_list">
                <td class="width-2">{{item.category_name}}</td>
                <td class="width-2">{{item.shop_name}}</td>
                <td class="width-2">{{item.address}}</td>
                <td class="width-2">{{item.shop_no}}</td>
                <td class="width-2">
                  <button
                    :disabled="isProcessing"
                    v-if="isEdit"                    
                    @click="saveAndSendSMS(item.shop_id,item.shop_no,item.address)"
                    class="btn btn-secondary btn-sm"
                  >Send SMS</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
        <div class="panel" v-if="isEdit && form.assess_list.length">
      <div class="panel-heading">
        <span class="panel-title">Assessment Details</span>
      </div>
      <div class="row">
        <div class="col col-12">
          <table class="table">
            <thead>
              <tr>
                <th>Assessment No</th>
                <th>Owner Name</th>
                <th>Ward No</th>
                <th>Ward No</th>
                <th>Door No</th>
                <th>Mobile No</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in form.assess_list">
                <td class="width-2">{{item.assessment_no}}</td>
                <td class="width-2">{{item.owner_name}}</td>
                <td class="width-2">{{item.ward_no}}</td>
                <td class="width-2">{{item.street_no}}</td>
                <td class="width-2">{{item.door_no}}</td>
                <td class="width-2">{{item.mobile_no}} {{item.mobile_no_2}} {{item.mobile_no_3}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
import Vue from "vue";
import ErrorText from "../../components/form/ErrorText.vue";
import Typeahead from "../../components/form/Typeahead.vue";
import Spinner from "../../components/loading/Spinner.vue";
import { get, byMethod, post } from "../../lib/api";
import { form } from "../../lib/mixins";

function initializeUrl(to) {
  let urls = {
    create: `/api/covid_complaint/create`,
    edit: `/api/covid_complaint/${to.params.id}/edit`,
    clone: `/api/covid_complaint/${to.params.id}/edit?mode=clone`
  };

  return urls[to.meta.mode] || urls["create"];
}

export default {
  components: { ErrorText, Typeahead, Spinner },
  mixins: [form],

  data() {
    return {
      resource: "/covid_complaint",
      store: "/api/covid_complaint",
      method: "POST",
      title: "Create",
      message: "You have successfully created Complaint!",
      DepartmentURL: "/api/search/department",
      ComplaintTypeURL: "/api/search/Covid19Category",
      ComplaintStatusURL: "/api/search/complaintStatus",
      WardNoURL: "/api/search/WardNo",
      StreetNoURL: "/api/search/StreetNo",
      DoorNoURL: "/api/search/DoorNo",
      form: {
        assess_list : {}
      },
      shop_list: {},
      assess_list: {},
    };
  },
  created() {
    if (this.mode === "edit") {
      this.store = `/api/covid_complaint/${this.$route.params.id}`;
      this.message = "You have successfully updated Complaint!";
      this.method = "PUT";
      this.title = "Edit";
    }
  },
  beforeRouteEnter(to, from, next) {
    get(initializeUrl(to)).then(res => {
      next(vm => vm.setData(res));
    });
    // catch 422
  },
  beforeRouteUpdate(to, from, next) {
    this.show = false;

    get(initializeUrl(to)).then(res => {
      this.setData(res);
      next();
    });
    //catch 422
  },
  methods: {
    save() {
      this.submit(data => {
        this.success();
        this.$router.push(`${this.resource}/${data.id}`);
      });
    },
    saveAndNew() {
      this.submit(data => {
        const id = Math.random()
          .toString(36)
          .substring(7);
        this.endProcessing();
        this.success();
        this.$router.push(`${this.resource}/create?new=${id}`);
      });
    },
    saveAndSendSMS(shop_id,mobile_no,address) {
      this.submit(data => {
        this.success();
        this.sendSMS(shop_id,mobile_no,address);
        this.$router.push(`${this.resource}/${data.id}`);
      });
    },
    onDepartmentUpdate(e) {
      const department = e.target.value;
      Vue.set(this.form, "dept", department);
      Vue.set(this.form, "type", null);
    },
    onWardNoUpdate(e) {
      const ward_no = e.target.value;
      Vue.set(this.form, "ward_no", ward_no.ward_no);
      Vue.set(this.form, "ward_no", ward_no);
      Vue.set(this.form, "street_no", null);
      Vue.set(this.form, "door_no", null);
    },
    onStreetNoUpdate(e) {
      const street_no = e.target.value;
      Vue.set(this.form, "street_no", street_no.street_no);
      Vue.set(this.form, "street_no", street_no);
    },
    onDoorNoUpdate(e) {
      const door_no = e.target.value;
      Vue.set(this.form, "door_no", door_no.door_no);
      Vue.set(this.form, "door_no", door_no);
    },
    onComplaintTypeUpdate(e) {
      let complaint = e.target.value;
      
      Vue.set(this.form, "type", complaint.id);
      Vue.set(this.form, "type", complaint);
     
      this.shopData(complaint.category_name);
    },
    onComplaintStatusUpdate(e) {
      const comp_status = e.target.value;
      Vue.set(this.form, "status", comp_status.id);
      Vue.set(this.form, "status", comp_status);
    },
    setData(res) {
      Vue.set(this.$data, "form", res.data.form);
      this.$title.set(`complaint ${this.title}`);
      this.$bar.finish();
      this.show = true;
      this.shopData(this.form.complaint_name);
    },
    shopData(complaint_name) {     
      post("/shopData", { complaint_name: complaint_name })
        .then(res => {
          this.shop_list = res.data.form;
        })
        .catch(err => {});
    },
    setAssessData() {
      const assess = this.form.mobile_no;
      post("/assessData", { mobile_no: assess })
        .then(res => {
          let all_data = res.data.form[0];
          if (res.data.form.length) {
            Vue.set(this.form, "name", all_data.owner_name);
            Vue.set(this.form, "ward_no", all_data.ward_no);
            Vue.set(this.form, "street_no", all_data.street_no);
            Vue.set(this.form, "door_no", all_data.door_no);
            Vue.set(this.form, "assessment_no", all_data.assessment_no);
          } else {
            Vue.set(this.form, "name", undefined);
            Vue.set(this.form, "ward_no", undefined);
            Vue.set(this.form, "street_no", undefined);
            Vue.set(this.form, "door_no", undefined);
            Vue.set(this.form, "assessment_no", undefined);
          }
        })
        .catch(err => {});
    },
    sendSMS(shop_id,mobile_no,address) {     
     
        post("/sendShopSMS", { shop_id : shop_id, mobile_no: mobile_no ,address: address, complaint_no : this.form.complaint_no , user_mobile_no :  this.form.mobile_no })
          .then(res => {
          this.$message.success(`SMS Successfully Send`);
          Vue.set(this.form, "status", {status_id: 2, status_name: "Inprocess", text: "Inprocess"});        
          })
          .catch(err => {});
      }
  
  }
};
</script>
