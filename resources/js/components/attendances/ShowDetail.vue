<template>
  <tr>
    <td :class="dayColorClass">{{ day }}日({{ week }})</td>
    <td>{{ item }}</td>
    <td v-if="shift.item == 1">{{ sliceTime(shift.start_at) }}<br>~{{ sliceTime(shift.end_at) }}</td>
    <td v-else></td>
    <td>{{ startAt ? sliceTime(startAt) : '' }}<span v-if="start_work_stamp && start_work_stamp.exception == 1"><br>(遅刻)</span></td>
    <td>{{ endAt ? sliceTime(endAt) : '' }}<span v-if="end_work_stamp && end_work_stamp.exception == 1"><br>(早退)</span></td>
    <td v-if="user_id == authId() && end_work_stamp">
      <select name="break_time" class="form-control" v-model="break_time" @change="put()">
        <option v-for="(val, break_time) in break_times" :value="val">{{ break_time }}</option>
      </select>
    </td>
    <td v-else></td>
    <td>
      <div>{{ workTimeString }}</div>
      <div>{{ midnightTimeString }}</div>
    </td>
    <td>{{ earlytimeString }}</td>
    <td>{{ overtimeString }}</td>
    <td v-if="user_id == authId()">
      <input type="text" class="form-control" style="min-width: 150px" v-model="memo" @blur="put">
    </td>
    <td class="over-x" style="max-width: 200px" v-else></td>
  </tr>
</template>

<script>
import Data from '../common/Data.vue'
import Link from '../common/Link.vue'

export default {
  props: ['user_id', 'dayColorClass', 'day', 'week', 'attendance', 'items', 'break_times'],
  mixins: [Data, Link],
  data(){
    return {
      'shift': this.attendance.active_shifts[0],
      'start_work_stamp': this.attendance.active_start_work_stamps[0],
      'end_work_stamp': this.attendance.active_end_work_stamps[0],
      'earlytime': this.attendance.active_earlytimes[0],
      'overtime': this.attendance.active_overtimes[0],
      'memo': this.attendance.memo,
      'break_time': 0,
    }
  },
  mounted(){
    if(this.item != '欠勤'){
      this.break_time = this.attendance.break_time
    }
  },
  watch: {
    break_time: function(new_break_time, old_break_time){
      var old_work_time = this.workTime(this.startAt, this.endAt, old_break_time)
      var new_work_time = this.workTime(this.startAt, this.endAt, new_break_time)

      var work_time = new_work_time - old_work_time
      var break_time = new_break_time - old_break_time


      this.$emit('break-time-update', work_time, break_time)
    }
  },
  computed: {
    item(){
      if(this.shift.item != 1){
        return this.items[this.shift.item]
      }else{
        var today = new Date().getDate()
        var date = new Date(this.attendance.date).getDate()

        if(date < today && !this.start_work_stamp && !this.end_work_stamp){
          return '欠勤'
        }else{
          return this.items[this.shift.item]
        }
      }
    },
    startAt(){
      if(this.start_work_stamp){
        if(this.start_work_stamp.exception == 1){
          return this.start_work_stamp.stamp_at
        }else{
          return this.shift.start_at
        }
      }
    },
    endAt(){
      if(this.end_work_stamp){
        if(this.end_work_stamp.exception == 1){
          return this.end_work_stamp.stamp_at
        }else{
          return this.shift.end_at
        }
      }
    },
    workTimeString(){
      var work_time = this.workTime(this.startAt, this.endAt, this.break_time)
      return work_time ? `${work_time}時間` : ''
    },
    midnightTimeString(){
      if(this.startAt && this.endAt){
        // 深夜時間の計算
        var y = new Date().getFullYear()
        var m = new Date().getMonth() + 1
        var d = new Date().getDate()
        var ymd = `${y}/${m}/${d}`

        var start = new Date(ymd + ' ' + this.startAt)
        var end = new Date(ymd + ' ' + this.endAt)
        // 22時
        var time22 = new Date(ymd + ' 22:00:00')
        d = new Date().getDate() + 1
        ymd = `${y}/${m}/${d}`
        // 29時(次の日の朝5時)
        var time29 = new Date(ymd + ' 05:00:00')
        // 退勤時間のほうが出勤時間よりも早い = 日をまたいでいるので 退勤時間に+1日する
        if(this.endAt < this.startAt){
          end = new Date(ymd + ' ' + this.endAt)
        }

        var midnight_time = 0
        while(1){
          start.setHours(start.getHours() + 1)
          if(time22 < start && start <= time29){
            midnight_time++
          }
          if(end < start){
            var over_midnight_time = start.getTime() - end.getTime();
            if(time22 < end && end <= time29){
              midnight_time -= Math.abs(over_midnight_time) / (60 * 60 * 1000)
            }
            return `(${midnight_time}時間)`
          }
        }

      }
    },
    earlytimeString(){
      if(this.shift.item == 1 && this.startAt && this.endAt){
        var start_at = this.earlytime ? this.earlytime.start_at : null
        var end_at = this.earlytime ? this.earlytime.end_at : null

        var time = this.workTime(start_at, end_at, 0)
        return time ? `${this.workTime(start_at, end_at, 0)}時間` : '0時間'
      }
    },
    overtimeString(){
      if(this.shift.item == 1 && this.startAt && this.endAt){
        var start_at = this.overtime ? this.overtime.start_at : null
        var end_at = this.overtime ? this.overtime.end_at : null

        var time = this.workTime(start_at, end_at, 0)
        return time ? `${this.workTime(start_at, end_at, 0)}時間` : '0時間'
      }
    }
  },
  methods: {
    put(){
      axios.put('/attendance/' + this.attendance.id, {
        'break_time': this.break_time,
        'memo': this.memo,
      })
      .catch(() => {
        window.location = '/user/' + this.user_id
      })
    },
  }
}

</script>
