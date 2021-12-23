<template>
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title font-weight-bold">シフト申請一覧</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">
            <a :href="userShowLink(user.id)" v-if="user.id == authId()">Home</a>
            <a :href="userShowLink(user.id)" v-else>{{ user.username }}</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <a :href="attendanceShowLink(user.id)">出勤簿</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">シフト申請一覧</li>
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
            <div v-if="active_shift">
              <table class="table table-bordered table-responsive-md text-center">
                <thead>
                  <tr>
                    <th>区分</th>
                    <th>出勤時間</th>
                    <th>退勤時間</th>
                    <th>申請時間</th>
                    <th style="min-width: 150px">備考</th>
                    <th>承認</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ shiftItem(active_shift) }}</td>
                    <td>
                      <span v-if="active_shift.item == 1">{{ sliceTime(active_shift.start_at) }}</span>
                    </td>
                    <td>
                      <span v-if="active_shift.item == 1">{{ sliceTime(active_shift.end_at) }}</span>
                    </td>
                    <td>
                      <div class="mb-1">{{ isEdit(active_shift) }}</div>
                      <div>{{ createdAt(active_shift) }}</div>
                    </td>
                    <td>{{ active_shift.memo }}</td>
                    <td :class="approveClass(active_shift.approve)">{{ approve(active_shift.approve) }}</td>
                  </tr>
                </tbody>
              </table>
              <div class="text-right mt-3" v-if="user.id == authId() && !is_pending_shift">
                <a :href="shiftCreateLink(date)" class="btn btn-sm btn-gradient-info">修正申請</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row" v-if="not_active_shifts.length > 0">
      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <table class="table table-bordered table-responsive-md text-center">
              <thead>
                <tr>
                  <th>区分</th>
                  <th>出勤時間</th>
                  <th>退勤時間</th>
                  <th>申請時間</th>
                  <th style="min-width: 150px">備考</th>
                  <th>承認</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="shift in not_active_shifts">
                  <td>{{ shiftItem(shift) }}</td>
                  <td>
                    <span v-if="shift.item == 1">{{ sliceTime(shift.start_at) }}</span>
                  </td>
                  <td>
                    <span v-if="shift.item == 1">{{ sliceTime(shift.end_at) }}</span>
                  </td>
                  <td>
                    <div class="mb-1">{{ isEdit(shift) }}</div>
                    <div>{{ createdAt(shift) }}</div>
                  </td>
                  <td style="max-width: 200px">
                    <div class="over-x scrollbar-none">{{ shift.memo }}</div>
                  </td>
                  <td :class="approveClass(shift.approve)" style="width: 100px"  v-if="shift.approve == 0">
                    <div v-if="user.id == authId()">
                      <a :href="shiftEditLink(shift.id)" class="btn btn-sm btn-gradient-success">編集</a>
                      <form :action="shiftAction(shift.id)" class="d-inline-block" method="post">
                        <input type="hidden" name="_token" :value="csrfToken()">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-gradient-danger">削除</button>
                      </form>
                    </div>
                    <form :action="shiftApproveAction(shift.id)" method="post" v-else>
                      <input type="hidden" name="_token" :value="csrfToken()">
                      <input type="hidden" name="date" :value="date">
                      <button type="submit" name="ok" class="btn btn-sm btn-gradient-info">承認</button>
                      <button type="submit" class="btn btn-sm btn-gradient-danger">却下</button>
                    </form>
                  </td>
                  <td :class="approveClass(shift.approve)" v-else>
                    <div>{{ approve(shift.approve) }}</div>
                    <form :action="shiftApproveAction(shift.id)" method="post" class="mt-1" v-if="user.id != authId()">
                      <input type="hidden" name="_token" :value="csrfToken()">
                      <input type="hidden" name="date" :value="date">
                      <button type="submit" name="ok" class="btn btn-sm btn-gradient-info" v-if="shift.approve == 2">承認</button>
                      <button type="submit" class="btn btn-sm btn-gradient-danger" v-else>却下</button>
                    </form>
                  </td>
                </tr>
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

export default {
  props: ['user', 'date', 'active_shift', 'not_active_shifts', 'is_pending_shift'],
  mixins: [Data, Link],
  data(){
    return {
      'day': this.date,
    }
  },
  methods: {
    monthChange(){
      window.location = '/shift/' + this.user.id + '/' + this.day
    },
    shiftItem(shift){
      if(shift.item == 1){
        return '稼働'
      }else if(shift.item = 2){
        return '公休'
      }else if(shift.item = 3){
        return '有給'
      }
    },
    isEdit(shift){
      if(shift.is_edit){
        return '修正'
      }else{
        return '申請'
      }
    },
    createdAt(shift){
      var D = new Date(shift.created_at)
      var y = D.getFullYear()
      var m = D.getMonth() + 1
      var d = D.getDate()
      var h = D.getHours()
      var i = D.getMinutes()
      var s = D.getSeconds()

      return `${y}/${m}/${d} ${h}:${i}:${s}`
    },
  },
}
</script>
