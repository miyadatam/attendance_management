<template>
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title font-weight-bold">出勤簿</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">
            <a :href="userShowLink(user.id)" v-if="user.id == authId()">Home</a>
            <a :href="userShowLink(user.id)" v-else>{{ user.username }}</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">出勤簿</li>
        </ol>
      </nav>
    </div>
    <input type="month" name="month" form="shift-form" class="form-control mb-3 col-lg-3 col-md-6 col-12" v-model="month" @change="monthChange">
    <!-- シフト登録済 -->
    <div v-if="attendances.length > 0">
      <div class="row">
        <div class="col-lg-6 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Days</h4>
              <table class="table">
                <tbody>
                  <tr><td>実働日数</td><td>{{ work_count }}日</td></tr>
                  <tr><td>公休日数</td><td>{{ holiday_count }}日</td></tr>
                  <tr><td>有給日数</td><td>{{ paid_holiday_count }}日</td></tr>
                  <tr><td>早出日数</td><td>{{ early_work_count }}日</td></tr>
                  <tr><td>残業日数</td><td>{{ overtime_work_count }}日</td></tr>
                  <tr><td>欠勤日数</td><td>{{ absence_count }}日</td></tr>
                  <tr><td>遅刻日数</td><td>{{ behind_time_count }}日</td></tr>
                  <tr><td>早退日数</td><td>{{ early_leave_count }}日</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-lg-6 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Time</h4>
              <table class="table">
                <tbody>
                  <tr><td>実働時間</td><td>{{ total_work_time }}時間</td></tr>
                  <tr><td>深夜時間</td><td>{{ total_midnight_work_time }}時間</td></tr>
                  <tr><td>休憩時間</td><td id="total-break-time">{{ total_break_time }}時間</td></tr>
                  <tr><td>早出時間</td><td>{{ total_early_time }}時間</td></tr>
                  <tr><td>残業時間</td><td>{{ total_overtime }}時間</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Attendance</h4>
            <table class="table table-responsive-md text-center">
              <thead>
                <tr class="align-middle">
                  <th class="align-middle">日</th>
                  <th class="align-middle">区分</th>
                  <th class="align-middle">シフト</th>
                  <th class="align-middle">出勤</th>
                  <th class="align-middle">退勤</th>
                  <th class="align-middle">休憩</th>
                  <th class="align-middle">実稼働時間<br>(深夜時間)</th>
                  <th class="align-middle">早出時間</th>
                  <th class="align-middle">残業時間</th>
                  <th class="align-middle">備考</th>
                </tr>
              </thead>
              <tbody>
                <ShowDetail v-for="(attendance, index) in attendances" :key="attendance.id" :attendance="attendance" :user_id="user.id"
                :day="index + 1" :week="days[index + 1]['week']" :dayColorClass="days[index + 1]['class']" :items="items" :break_times="break_times" @break-time-update="breakTimeUpdate"></ShowDetail>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- シフト未登録 -->
    <div v-else>
      <div class="stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Shift</h4>
            <table class="table table-responsive-md text-center">
              <thead>
                <tr>
                  <th>日</th>
                  <th style="min-width: 100px">区分</th>
                  <th>出勤時間</th>
                  <th>退勤時間</th>
                  <th style="min-width: 140px">休憩時間</th>
                  <th>稼働時間</th>
                  <th style="min-width: 200px">備考</th>
                </tr>
              </thead>
              <tbody>
                <ShiftShowDetail v-for="(array, day) in days" :key="day" :colorClass="array.class" :day="day" :week="array.week" :items="items" :break_times="break_times"></ShiftShowDetail>
              </tbody>
            </table>
            <form action="/attendance" method="post" id="shift-form" class="row justify-content-between w-100 mx-auto px-3" ref="form">
              <div>
                <input type="hidden" name="_token" :value="csrfToken()">
              </div>
              <p class="text-danger text-center font-weight-bold" v-show="is_error">入力不備があります</p>
              <div>
                <button type="button" class="btn btn-gradient-info btn-md" @click="check">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Data from '../common/Data.vue'
import Link from '../common/Link.vue'
import ShiftShowDetail from './ShiftShowDetail.vue'
import ShowDetail from './ShowDetail.vue'

export default {
  props: ['user', 'month', 'days', 'attendances'],
  mixins: [Data, Link],
  components: {
    ShiftShowDetail,
    ShowDetail
  },
  mounted(){
    this.attendanceStatus()
  },
  data(){
    return {
      'work_count': 0,
      'holiday_count': 0,
      'paid_holiday_count': 0,
      'early_work_count': 0,
      'overtime_work_count': 0,
      'absence_count': 0,
      'behind_time_count': 0,
      'early_leave_count': 0,
      'total_work_time': 0,
      'total_midnight_work_time': 0,
      'total_break_time': 0,
      'total_early_time': 0,
      'total_overtime': 0,
      'is_error': false,
      'items': { 0: '', 1: '稼働', 2: '公休', 3: '有給' },
      'break_times': {
        '0分': 0, '15分': 0.25, '30分': 0.5, '45分': 0.75,
        '1時間': 1, '1時間15分': 1.25, '1時間30分': 1.5, '1時間45分': 1.75,
        '2時間': 2, '2時間15分': 2.25, '2時間30分': 2.5,'2時間45分': 2.75
      },
    }
  },
  methods: {
    monthChange(){
      var D = new Date(this.month)
      var y = D.getFullYear()
      var m = D.getMonth() + 1

      window.location = '/attendance/' + this.user.id + '/' + y + '/' + m
    },
    statusInit(){
      this.work_count = 0
      this.holiday_count = 0
      this.paid_holiday_count = 0
      this.early_work_count = 0
      this.overtime_work_count = 0
      this.absence_count = 0
      this.behind_time_count = 0
      this.early_leave_count = 0
      this.total_work_time = 0
      this.total_midnight_work_time = 0
      this.total_break_time = 0
      this.total_early_time = 0
      this.total_overtime = 0
    },
    attendanceStatus(){
      this.statusInit()

      var today = new Date().getDate()
      this.attendances.forEach(attendance => {
        var date = new Date(attendance.date).getDate()
        if(date > today) return

        var shift = attendance.active_shifts[0]
        var start_work_stamp = attendance.active_start_work_stamps[0]
        var end_work_stamp = attendance.active_end_work_stamps[0]
        var earlytime = attendance.active_earlytimes[0]
        var overtime = attendance.active_overtimes[0]

        if(shift.item == 1){

          if(start_work_stamp){
            this.behind_time_count += start_work_stamp.exception == 1 ? 1 : 0
          }

          if(end_work_stamp){
            this.early_leave_count += end_work_stamp.exception == 1 ? 1 : 0
          }

          if(date != today && (!start_work_stamp || !end_work_stamp)){
            this.absence_count++
          }else{
            this.work_count++

            var start_at = start_work_stamp.exception == 1 ? start_work_stamp.stamp_at : shift.start_at
            var end_at = end_work_stamp.exception == 1 ? end_work_stamp.stamp_at : shift.end_at

            this.total_work_time += this.workTime(start_at, end_at, attendance.break_time)
            this.total_break_time += attendance.break_time

            // 深夜時間の計算
            var y = new Date().getFullYear()
            var m = new Date().getMonth() + 1
            var d = new Date().getDate()
            var ymd = `${y}/${m}/${d}`

            var start = new Date(ymd + ' ' + start_at)
            var end = new Date(ymd + ' ' + end_at)
            // 22時
            var time22 = new Date(ymd + ' 22:00:00')
            d = new Date().getDate() + 1
            ymd = `${y}/${m}/${d}`
            // 29時(次の日の朝5時)
            var time29 = new Date(ymd + ' 05:00:00')
            // 退勤時間のほうが出勤時間よりも早い = 日をまたいでいるので 退勤時間に+1日する
            if(end_at < start_at){
              end = new Date(ymd + ' ' + end_at)
            }
            while(1){
              start.setHours(start.getHours() + 1)
              if(time22 < start && start <= time29){
                this.total_midnight_work_time++
              }
              if(end < start){
                var over_midnight_time = start.getTime() - end.getTime();
                if(time22 < end && end <= time29){
                  this.total_midnight_work_time -= Math.abs(over_midnight_time) / (60 * 60 * 1000)
                }
                return false
              }
            }

            if(earlytime){
              this.total_early_time += this.workTime(earlytime.start_at, earlytime.end_at, 0)
            }

            if(overtime){
              this.total_overtime += this.workTime(overtime.start_at, overtime.end_at, 0)
            }
          }

        }else if(shift.item == 2){
          this.holiday_count++
        }else{
          this.paid_holiday_count++
        }
      })
    },
    breakTimeUpdate(work_time, break_time){
      this.total_work_time += work_time
      this.total_break_time += break_time
    },
    check(){
      this.is_error = false
      var elements = document.getElementsByClassName('shift-tr')
      for(let i = 0 ; i < elements.length; i++){
        if(elements[i].querySelector('[name="items[]"]').value == 0){

          this.is_error = true
          elements[i].querySelector('[name="items[]"]').classList.add('border-danger')

        }else if(elements[i].querySelector('[name="items[]"]').value == 1){

          if(elements[i].querySelector('[name="start_at[]"]').value == ''){
            this.is_error = true
            elements[i].querySelector('[name="start_at[]"]').classList.add('border-danger')
          }

          if(elements[i].querySelector('[name="end_at[]"]').value == ''){
            this.is_error = true
            elements[i].querySelector('[name="end_at[]"]').classList.add('border-danger')
          }
        }
      }

      if(this.is_error){
        return false
      }

      this.$refs.form.submit()
    }
  },
}
</script>
