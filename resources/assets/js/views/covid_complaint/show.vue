<template>
  <div v-if="show">
    <div class="panel">
      <div class="panel-heading">
        <span class="panel-title">
          <strong>Complaint No :</strong>
          {{model.complaint_no}}
          <!--<span class="badge badge-primary">{{model.comp_status}}</span>-->
        </span>
        <div>
          <router-link :to="`/covid_complaint`" class="btn" title="Go Back">
            <i class="fa fa-arrow-left"></i>
          </router-link>
          <router-link :to="`/covid_complaint/${model.id}/edit`" class="btn" title="Edit">
            <i class="fa fa-pencil"></i>
          </router-link>
          <a @click.stop="deleteModel" class="btn btn-danger">
            <i class="fa fa-trash"></i>
          </a>
        </div>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col col-4">
            <div class="bg-grey">
              <strong>Mobile No</strong>
              <br />
              <br />
              <pre>{{model.mobile_no}}</pre>
              <hr />
              <strong>Name</strong>
              <br />
              <br />
              <pre>{{model.name}}</pre>
              <hr />
              <strong>Complaint Date</strong>
              <br />
              <br />
              <pre>{{model.complaint_datetime}}</pre>
              <hr />
               <strong>Complaint Type</strong>
              <br />
              <br />
              <pre>{{model.complaint_name}}</pre>
            </div>
          </div>
          <div class="col col-4">
            <div class="bg-grey">
              
             
            
              <strong>Ward No</strong>
              <br />
              <br />
              <pre>{{model.ward_no}}</pre>
              <hr />
              <strong>Street Name</strong>
              <br />
              <br />
              <pre>{{model.street_no}}</pre>
              <hr />
              <strong>Door No</strong>
              <br />
              <br />
              <pre>{{model.door_no}}</pre>
              <hr />
              <strong>Description</strong>
              <br />
              <br />
              <pre>{{model.description}}</pre>
            </div>
          </div>

          <div class="col col-4">
            <div class="bg-grey">
              
              <strong>SMS</strong>
              <br />
              <br />
              <pre>{{model.sms}}</pre>
                <hr />
              <strong>Shop Name</strong>
              <br />
              <br />
              <pre>{{model.shop_name}}</pre>
               <hr />
              <strong>Shop Address</strong>
              <br />
              <br />
              <pre>{{model.address}}</pre>
                <hr />
               <strong>Shop No</strong>
              <br />
              <br />
              <pre>{{model.shop_no}}</pre>
            </div>
          </div>
        </div>
        <div class="col col-12 large-sts" align="center">
          <status :id="model.status_id"></status>
        </div>
      </div>
    </div>
    <div class="panel" v-if="model.sms_list.length">
      <div class="panel-heading">
        <span class="panel-title">SMS Details</span>
      </div>
      <div class="row">
        <div class="col col-12">
          <table class="table">
            <thead>
              <tr>
                <th>SMS Date</th>
                <th>category</th>
                <th>Message</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in model.sms_list">
                <td class="width-2">{{item.sms_datetime}}</td>
                <td class="width-3">{{item.category}}</td>
                <td class="width-3">{{item.user_type}}</td>
                <td class="width-6">{{item.message}}</td>               
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
import { get, byMethod } from "../../lib/api";
import Status from "../../components/status/Complaints.vue";
export default {
  components: { Status },
  data() {
    return {
      show: false,
      model: {
        currency: {},
        items: []
      }
    };
  },
  beforeRouteEnter(to, from, next) {
    get(`/api/covid_complaint/${to.params.id}`).then(res => {
      next(vm => vm.setData(res));
    });
    // catch 422
  },
  beforeRouteUpdate(to, from, next) {
    this.show = false;
    get(`/api/covid_complaint/${to.params.id}`).then(res => {
      this.setData(res);
      next();
    });
    //catch 422
  },
  methods: {
    deleteModel() {
      const r = confirm("Are you sure!");
      if (r != true) {
        return;
      }
      this.$bar.start();
      byMethod("delete", `/api/covid_complaint/${this.model.id}`)
        .then(({ data }) => {
          if (data.deleted) {
            this.$router.push("/covid_complaint");
            this.$message.success(`You have successfully deleted complaint`);
          }
          this.$bar.finish();
        })
        .catch(err => {});
    },
    setData(res) {
      Vue.set(this.$data, "model", res.data.data);
      this.$title.set(`covid_complaint - ${this.model.item_code}`);
      this.$bar.finish();
      this.show = true;
    }
  }
};
</script>
