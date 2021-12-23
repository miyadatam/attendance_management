<template>
  <tr>
    <td>{{ section }}</td>
    <td>{{ item }}</td>
    <td>{{ stampAt }}</td>
    <td>
      <div class="mb-1">{{ isEdit }}</div>
      <div>{{ createdAt }}</div>
    </td>
    <td>{{ work_stamp.memo }}</td>
    <td :class="approveClass(work_stamp.approve)" style="width: 100px"  v-if="work_stamp.approve == 0">
      <div v-if="user.id == authId()">
        <a :href="workStampEditLink(work_stamp.id)" class="btn btn-sm btn-gradient-success">編集</a>
        <form :action="workStampAction(work_stamp.id)" class="d-inline-block" method="post">
          <input type="hidden" name="_token" :value="csrfToken()">
          <input type="hidden" name="_method" value="DELETE">
          <button type="submit" class="btn btn-sm btn-gradient-danger">削除</button>
        </form>
      </div>
      <form :action="workStampApproveAction(work_stamp.id)" method="post" v-else>
        <input type="hidden" name="_token" :value="csrfToken()">
        <input type="hidden" name="date" :value="date">
        <input type="hidden" name="is_start" :value="is_start">
        <button type="submit" name="ok" class="btn btn-sm btn-gradient-info">承認</button>
        <button type="submit" class="btn btn-sm btn-gradient-danger">却下</button>
      </form>
    </td>
    <td :class="approveClass(work_stamp.approve)" v-else-if="work_stamp.approve == 1">{{ approve(work_stamp.approve) }}</td>
    <td :class="approveClass(work_stamp.approve)" v-else>
      <div>{{ approve(work_stamp.approve) }}</div>
      <form :action="workStampApproveAction(work_stamp.id)" method="post" class="mt-1" v-if="user.id != authId()">
        <input type="hidden" name="_token" :value="csrfToken()">
        <input type="hidden" name="date" :value="date">
        <input type="hidden" name="is_start" :value="is_start">
        <button type="submit" name="ok" class="btn btn-sm btn-gradient-info">承認</button>
      </form>
    </td>
  </tr>
</template>

<script>
import Data from '../common/Data.vue'
import Link from '../common/Link.vue'

export default {
  props: ['user', 'work_stamp', 'shift', 'date'],
  mixins: [Data, Link],
  data(){
    return {
      'is_start': ''
    }
  },
  mounted(){
    this.is_start = this.work_stamp.is_start ? true : false
  },
  computed: {
    section(){
      if(this.work_stamp.is_start){
        return '出勤'
      }else{
        return '退勤'
      }
    },
    item(){
      if(this.work_stamp.exception == 1){
        return this.work_stamp.is_start ? '遅刻' : '早退'
      }else{
        return ''
      }
    },
    stampAt(){
      if(this.work_stamp.exception == 1){
        return this.sliceTime(this.work_stamp.stamp_at)
      }else{
        return this.work_stamp.is_start ? this.sliceTime(this.shift.start_at) : this.sliceTime(this.shift.end_at)
      }
    },
    isEdit(){
      return this.work_stamp.is_edit ? '申請' : '打刻'
    },
    createdAt(){
      var D = new Date(this.work_stamp.created_at)
      var y = D.getFullYear()
      var m = D.getMonth() + 1
      var d = D.getDate()
      var h = D.getHours()
      var i = D.getMinutes()
      var s = D.getSeconds()

      return `${y}/${this.timeChange(m.toString())}/${this.timeChange(d.toString())} ${this.timeChange(h.toString())}:${this.timeChange(i.toString())}:${this.timeChange(s.toString())}`
    },
  }
}
</script>
