<template>
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title font-weight-bold">打刻申請一覧</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">
            <a :href="userShowLink(user.id)" v-if="user.id == authId()">Home</a>
            <a :href="userShowLink(user.id)" v-else>{{ user.username }}</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <a :href="attendanceShowLink(user.id)">出勤簿</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">打刻申請一覧</li>
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
            <div class="my-3 text-center font-weight-bold" v-if="shift">{{ shiftStatus }}</div>
            <div class="my-3 text-center font-weight-bold" v-else>
              <div class="mb-3">{{ year }}年{{ month }}月のシフトが登録されていません</div>
              <div v-if="user.id == authId()"><a :href="attendanceShowLink(user.id, year, month)">こちら</a>からシフトを登録してください</div>
            </div>
            <div v-if="active_start_work_stamp || active_end_work_stamp">
              <table class="table table-bordered table-responsive-md text-center">
                <thead>
                  <tr>
                    <th>区分</th>
                    <th>項目</th>
                    <th>打刻時間</th>
                    <th>申請時間</th>
                    <th style="min-width: 150px">備考</th>
                    <th>承認</th>
                  </tr>
                </thead>
                <tbody>
                  <ShowDetail v-if="active_start_work_stamp" :work_stamp="active_start_work_stamp" :shift="shift" :user="user"></ShowDetail>
                  <ShowDetail v-if="active_end_work_stamp" :work_stamp="active_end_work_stamp" :shift="shift" :user="user"></ShowDetail>
                </tbody>
              </table>
            </div>
            <div class="text-right mt-3" v-if="user.id == authId() && shift && shift.item == 1 &&  (isOverToday || active_start_work_stamp && active_end_work_stamp) && (!is_pending_start_workstamp || !is_pending_end_workstamp)">
              <a :href="workStampCreateLink(date)" class="btn btn-sm btn-gradient-info">修正申請</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row" v-if="not_active_workstamps.length > 0">
      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <table class="table table-bordered table-responsive-md text-center">
              <thead>
                <tr>
                  <th>区分</th>
                  <th>項目</th>
                  <th>打刻時間</th>
                  <th>申請時間</th>
                  <th style="min-width: 150px">備考</th>
                  <th>承認</th>
                </tr>
              </thead>
              <tbody>
                <ShowDetail v-for="(work_stamp, index) in not_active_workstamps" :key="index"
                :work_stamp="work_stamp" :shift="shift" :date="date" :user="user"></ShowDetail>
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
    'user', 'date', 'shift', 'active_start_work_stamp',
    'active_end_work_stamp', 'not_active_workstamps',
    'is_pending_start_workstamp', 'is_pending_end_workstamp'
  ],
  mixins: [Data, Link],
  components: {
    ShowDetail
  },
  data(){
    return {
      'day': this.date,
    }
  },
  computed: {
    year(){
      return new Date(this.date).getFullYear()
    },
    month(){
      return new Date(this.date).getMonth() + 1
    },
    shiftStatus(){
      if(this.shift.item == 1){
        return `稼働 ${this.sliceTime(this.shift.start_at)} ~ ${this.sliceTime(this.shift.end_at)}`
      }else if(this.shift.item == 2){
        return '公休'
      }else{
        return '有給'
      }
    },
    isOverToday(){
      var today = new Date(new Date().setHours(0, 0, 0, 0))
      var D = new Date(new Date(this.date).setHours(0, 0, 0, 0))

      return D < today ? true : false
    },
  },
  methods: {
    monthChange(){
      window.location = '/work_stamp/' + this.user.id + '/' + this.day
    },
  },
}
</script>
