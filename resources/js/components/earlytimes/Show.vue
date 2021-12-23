<template>
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title font-weight-bold">早出申請一覧</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">
            <a :href="userShowLink(user.id)" v-if="user.id == authId()">Home</a>
            <a :href="userShowLink(user.id)" v-else>{{ user.username }}</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <a :href="attendanceShowLink(user.id)">出勤簿</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">早出申請一覧</li>
        </ol>
      </nav>
    </div>
    <div class="row">
      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="row justify-content-center">
              <div class="col-md-6 col-lg-4 mb-3">
                <input type="date" class="form-control" v-model="day" @change="monthChange">
              </div>
            </div>
            <div v-if="start_at && end_at">
              <div class="text-center mb-4 font-weight-bold">
                <div>出勤時間</div>
                <div>{{ workStampStatus }}</div>
              </div>
              <div v-if="active_earlytime">
                <table class="table table-bordered table-responsive-md text-center">
                  <thead>
                    <tr>
                      <th>開始時間</th>
                      <th>終了時間</th>
                      <th>申請時間</th>
                      <th style="min-width: 180px">備考</th>
                      <th>承認</th>
                    </tr>
                  </thead>
                  <tbody>
                    <ShowDetail :earlytime="active_earlytime" :user="user" :date="date"></ShowDetail>
                  </tbody>
                </table>
              </div>
              <div class="text-right mt-3" v-if="user.id == authId() && !is_pending_earlytime">
                <a :href="earlyTimeCreateLink(date)" class="btn btn-sm btn-gradient-info">申請</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row" v-if="not_active_earlytimes.length > 0">
      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <table class="table table-bordered table-responsive-md text-center">
              <thead>
                <tr>
                  <th>開始時間</th>
                  <th>終了時間</th>
                  <th>申請時間</th>
                  <th style="min-width: 180px">備考</th>
                  <th>承認</th>
                </tr>
              </thead>
              <tbody>
                <ShowDetail v-for="(earlytime, index) in not_active_earlytimes" :key="index"
                :earlytime="earlytime" :user="user" :date="date"></ShowDetail>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import Data from '../common/Data.vue'
import Link from '../common/Link.vue'

import ShowDetail from './ShowDetail.vue'

export default {
  props: [
    'user', 'date', 'start_at', 'end_at',
    'active_earlytime', 'not_active_earlytimes', 'is_pending_earlytime',
  ],
  mixins: [Data, Link],
  components:{
    ShowDetail
  },
  data(){
    return {
      'day': this.date,
    }
  },
  computed: {
    workStampStatus(){
      return `${this.start_at}~${this.end_at}`
    },
  },
  methods: {
    monthChange(){
      window.location = '/earlytime/' + this.user.id + '/' + this.day
    },
  },
}
</script>
